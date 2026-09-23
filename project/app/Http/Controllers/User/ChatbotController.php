<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FacebookPage;
use App\Models\FacebookAppSetting;
use App\Models\BotReply;
use App\Models\BotFlow;
use App\Models\ChatbotConversationState;
use App\Models\CommentAutomation;
use App\Models\Product;
use App\Models\UserChatbotProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Facebook Webhook Verification (GET Request)
     * 
     * এটি Facebook webhook verify করার জন্য ব্যবহৃত হয়
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function verifyWebhook(Request $request)
    {
        // এডমিন প্যানেলে সেট করা Verify Token ব্যবহার করুন (ডাটাবেস থেকে)
        $setting = FacebookAppSetting::getSetting();
        $verifyToken = $setting->verify_token ?? config('services.facebook.verify_token', '');
        
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && !empty($verifyToken) && $token === $verifyToken) {
            Log::info('Facebook Webhook verified successfully');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('Facebook Webhook verification failed', [
            'mode' => $mode,
            'token_received' => $token ? '(set)' : '(empty)',
            'expected_length' => $verifyToken ? strlen($verifyToken) : 0,
        ]);
        
        return response('Forbidden', 403);
    }

    /**
     * Handle Facebook Webhook Events (POST Request)
     * 
     * এটি Facebook থেকে আসা সব events হ্যান্ডেল করে
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        try {
            $data = $request->all();
            
            // Log incoming webhook data
            Log::info('Facebook Webhook received', ['data' => $data]);

            // Facebook এর object property চেক করা
            if (!isset($data['object'])) {
                return response('Invalid request', 400);
            }

            // যদি page object হয় তাহলে entries process করা
            if ($data['object'] === 'page' && !empty($data['entry'])) {
                foreach ($data['entry'] as $entry) {
                    $pageId = $entry['id'] ?? null;
                    if (!$pageId) {
                        continue;
                    }
                    // Facebook Page টেবিল থেকে page খুঁজে বের করা (page_id string বা number উভয় চেক)
                    $facebookPage = FacebookPage::where('page_id', (string) $pageId)
                        ->where('is_connected', true)
                        ->first();

                    if (!$facebookPage) {
                        Log::warning('Facebook page not found or disconnected', ['page_id' => $pageId]);
                        continue;
                    }

                    // Messaging event হ্যান্ডেল করা
                    if (isset($entry['messaging'])) {
                        foreach ($entry['messaging'] as $event) {
                            try {
                                $this->handleMessagingEvent($event, $facebookPage);
                            } catch (\Throwable $e) {
                                Log::error('handleMessagingEvent error', [
                                    'error' => $e->getMessage(),
                                    'file' => $e->getFile(),
                                    'line' => $e->getLine(),
                                    'trace' => $e->getTraceAsString(),
                                    'sender' => $event['sender']['id'] ?? null,
                                ]);
                                report($e);
                                $this->sendSimpleTextMessage(
                                    $event['sender']['id'],
                                    'দুঃখিত, কিছু একটা সমস্যা হয়েছে। আবার চেষ্টা করুন।',
                                    $facebookPage->page_access_token
                                );
                            }
                        }
                    }

                    // Feed/Comment event হ্যান্ডেল করা (কমেন্টের উত্তর দিতে feed সাবস্ক্রাইব + pages_manage_engagement পারমিশন দরকার)
                    if (isset($entry['changes'])) {
                        Log::info('Chatbot: Feed webhook received', ['page_id' => $pageId, 'changes_count' => count($entry['changes'])]);
                        foreach ($entry['changes'] as $change) {
                            $field = $change['field'] ?? '';
                            $value = $change['value'] ?? [];
                            if ($field === 'feed' && isset($value['item']) && $value['item'] === 'comment') {
                                // শুধু নতুন কমেন্টে রিপ্লাই (verb = add), edit/remove এ রিপ্লাই না
                                $verb = $value['verb'] ?? 'add';
                                if ($verb === 'add') {
                                    $this->handleCommentEvent($value, $facebookPage);
                                } else {
                                    Log::info('Chatbot: Comment event skipped (not new comment)', ['verb' => $verb]);
                                }
                            }
                        }
                    }
                }
            }

            return response('EVENT_RECEIVED', 200);
        } catch (\Throwable $e) {
            Log::error('Webhook handling error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            report($e); // Laravel এরর লগে জমা হবে (storage/logs/laravel.log)
            return response('Internal Server Error', 500);
        }
    }

    /**
     * Handle Messaging Events (Inbox Messages)
     * 
     * ইনবক্স মেসেজ হ্যান্ডেল করা এবং auto-reply পাঠানো
     * 
     * @param array $event
     * @param FacebookPage $facebookPage
     * @return void
     */
    private function handleMessagingEvent($event, $facebookPage)
    {
        \Log::info('=== Chatbot: handleMessagingEvent Start ===', [
            'page_id' => $facebookPage->id,
            'page_name' => $facebookPage->page_name,
            'event_keys' => array_keys($event)
        ]);

        $senderId = $event['sender']['id'];
        $pageAccessToken = $facebookPage->page_access_token;

        // Get Started বা মেনু বাটন ক্লিক (postback) হ্যান্ডেল করা
        if (isset($event['postback'])) {
            $this->sendTypingOn($senderId, $pageAccessToken);
            $payload = $event['postback']['payload'] ?? '';
            \Log::info('Chatbot: Postback received', ['sender_id' => $senderId, 'payload' => $payload]);

            if ($payload === 'GET_STARTED') {
                try {
                    \Log::info('Chatbot: Processing GET_STARTED for senderId: ' . $senderId);
                    $this->sendWelcomeFromBotFlowOrDefault($facebookPage, $senderId, $pageAccessToken);
                    \Log::info('Chatbot: GET_STARTED handler finished (check send logs for Graph API success)');
                } catch (\Exception $e) {
                    \Log::error('Chatbot: GET_STARTED Error', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $this->sendSimpleTextMessage($senderId, 'দুঃখিত, সমস্যা হয়েছে। আবার চেষ্টা করুন।', $pageAccessToken);
                }
                return;
            }
            if ($payload === 'PRODUCTS' || $payload === 'প্রোডাক্ট_দেখুন') {
                $this->sendProductCarouselResponse($senderId, $pageAccessToken, $facebookPage, true);
                return;
            }
            if ($payload === 'SUPPORT' || $payload === 'সাপোর্ট') {
                $this->sendSimpleTextMessage($senderId, 'সাপোর্টের জন্য আমাদের সাথে যোগাযোগ করুন। ধন্যবাদ!', $pageAccessToken);
                return;
            }
            if ($payload === 'ORDER' || $payload === 'অর্ডার') {
                $this->sendSimpleTextMessage($senderId, 'প্রোডাক্ট দেখতে "প্রোডাক্ট" বা "list" লিখুন।', $pageAccessToken);
                return;
            }
            // বট ফ্লোতে থাকলে বাটন ক্লিক (postback) দিয়ে পরবর্তী নোড খুলুন
            if ($this->advanceBotFlowConversationByPostback($facebookPage, $senderId, $payload, $pageAccessToken)) {
                return;
            }
            // স্টেট নেই – প্রথমে ফ্লোর প্রথম বাটন নোডে ম্যাচ, তারপর ফ্লোর যেকোনো বাটন নোডে ম্যাচ (যাতে "ইকমার্স"/"নিউজ পেপার" ইত্যাদি যেকোনো সময় ক্লিক করলেও রিপ্লাই আসে)
            if ($this->tryHandlePostbackFromFirstButtonNode($facebookPage, $senderId, $payload, $pageAccessToken)) {
                return;
            }
            $flow = BotFlow::where('facebook_page_id', $facebookPage->id)->where('is_active', true)->first();
            if ($flow && $flow->flow_data && $this->tryMatchPayloadAgainstAnyFlowButtonNode($flow, $flow->flow_data, $facebookPage, $senderId, $payload, $pageAccessToken)) {
                return;
            }
            // কোনো ম্যাচ না হলে কোনো রিপ্লাই দেবেন না
            return;
        }

        // যদি message না থাকে বা echo থাকে (নিজের পাঠানো মেসেজ এড়ানোর জন্য)
        if (!isset($event['message']) || isset($event['message']['is_echo'])) {
            return;
        }

        $messageText = $event['message']['text'] ?? '';
        // কুইক রিপ্লাই বাটন ট্যাপ করলে payload আসে
        $quickReplyPayload = $event['message']['quick_reply']['payload'] ?? null;
        if ($quickReplyPayload) {
            $this->sendTypingOn($senderId, $pageAccessToken);
            if ($quickReplyPayload === 'PRODUCTS' || $quickReplyPayload === 'প্রোডাক্ট_দেখুন') {
                $this->sendProductCarouselResponse($senderId, $pageAccessToken, $facebookPage, true);
                return;
            }
            if ($quickReplyPayload === 'SUPPORT' || $quickReplyPayload === 'সাপোর্ট') {
                $this->sendSimpleTextMessage($senderId, 'সাপোর্টের জন্য আমাদের সাথে যোগাযোগ করুন। ধন্যবাদ!', $pageAccessToken);
                return;
            }
            if ($quickReplyPayload === 'ORDER' || $quickReplyPayload === 'অর্ডার') {
                $this->sendSimpleTextMessage($senderId, 'প্রোডাক্ট দেখতে "প্রোডাক্ট" বা "list" লিখুন।', $pageAccessToken);
                return;
            }
        }

        // যদি message খালি হয় তাহলে return (অ্যাটাচমেন্ট ইত্যাদি পরে হ্যান্ডল করা যাবে)
        if (empty($messageText)) {
            return;
        }

        // রিপ্লাই দেওয়ার আগে টাইপিং ইন্ডিকেটর দেখান
        $this->sendTypingOn($senderId, $pageAccessToken);

        // ১. আগে কীওয়ার্ড দিয়ে Bot Flow খুলা চেক করুন – মেসেজ কোনো ফ্লোর কীওয়ার্ড ম্যাচ করলে সেই ফ্লো খুলে স্টেট সেভ (পুরনো স্টেট থাকলেও কীওয়ার্ড প্রাধান্য পাবে)
        if ($this->sendFirstMessageFromMatchingBotFlow($facebookPage, $senderId, $messageText, $pageAccessToken)) {
            return;
        }

        // ২. বট ফ্লো কনভারসেশন: যদি ইউজার কোনো ফ্লোতে আছে তাহলে পরবর্তী নোড পাঠান ও স্টেট আপডেট করুন
        if ($this->advanceBotFlowConversation($facebookPage, $senderId, $messageText, $pageAccessToken)) {
            return;
        }

        Log::info('Processing message', [
            'sender_id' => $senderId,
            'message' => $messageText,
            'page_id' => $facebookPage->page_id
        ]);

        // বিশেষ keyword চেক করা (Products বা List) – শুধু চ্যাটবট প্রোডাক্ট
        $lowerMessage = strtolower(trim($messageText));
        if (in_array($lowerMessage, ['products', 'list', 'প্রোডাক্ট', 'লিস্ট', 'product'])) {
            $this->sendProductCarouselResponse($senderId, $pageAccessToken, $facebookPage, true);
            return;
        }

        // ৩. Bot replies টেবিল থেকে keyword ম্যাচিং (শুধু আসল কীওয়ার্ড মিল)
        $botReplies = BotReply::where('facebook_page_id', $facebookPage->id)->get();
        $botReply = $botReplies->first(function ($reply) use ($messageText) {
            return $this->messageMatchesKeyword($messageText, (string) ($reply->keyword ?? ''));
        });

        if ($botReply) {
            if ($botReply->reply_type === 'product_carousel') {
                $this->sendProductCarouselResponse($senderId, $pageAccessToken, $facebookPage, true);
            } else {
                $this->sendMessage($senderId, $botReply, $pageAccessToken);
            }
            return;
        }

        // ৪. কোনো কীওয়ার্ড মিললে না — নীরব (কোনো ডিফল্ট রিপ্লাই নয়)
    }

    /**
     * Get Started ক্লিক করলে বট ফ্লো ওপেন: প্রথম নোড পাঠান ও কনভারসেশন স্টেট সেভ করুন (স্টেপ বাই স্টেপ ফ্লো চালু)
     */
    private function sendWelcomeFromBotFlowOrDefault($facebookPage, $senderId, $pageAccessToken)
    {
        \Log::info('Chatbot: sendWelcomeFromBotFlowOrDefault called', [
            'page_id' => $facebookPage->id,
            'sender_id' => $senderId
        ]);

        // Get Started এ ক্লিক করলেই এই পেজের অ্যাক্টিভ বট ফ্লো খুলবে (কোনো কীওয়ার্ড লাগবে না)
        $flow = BotFlow::where('facebook_page_id', $facebookPage->id)
            ->where('is_active', true)
            ->first();
        
        if (!$flow || !$flow->flow_data) {
            \Log::warning('Chatbot: No active bot flow found for page (Get Started)', ['page_id' => $facebookPage->id]);
            $this->sendSimpleTextMessage(
                $senderId,
                'Welcome! 😊 How can I help? Write or select an option from the menu.',
                $pageAccessToken
            );
            return;
        }

        \Log::info('Chatbot: Active bot flow found (Get Started)', ['flow_id' => $flow->id, 'flow_name' => $flow->name]);

        $flowData = $flow->flow_data;
        $firstNodeIds = $this->getFirstNodeIdsAfterStart($flowData);

        if ($firstNodeIds === []) {
            \Log::warning('Chatbot: No first node found after start node');
            $this->sendSimpleTextMessage($senderId, 'স্বাগতম! কিভাবে সাহায্য করতে পারি?', $pageAccessToken);
            return;
        }

        \Log::info('Chatbot: Sending first node(s) content', ['node_ids' => $firstNodeIds, 'count' => count($firstNodeIds)]);

        try {
            $sent = false;
            foreach ($firstNodeIds as $nid) {
                if ($this->sendNodeContentById($flowData, $nid, $senderId, $pageAccessToken, $facebookPage->user_id)) {
                    $sent = true;
                }
            }
            \Log::info('Chatbot: First node(s) sent', ['success' => $sent]);
        } catch (\Exception $e) {
            \Log::error('Chatbot: Error sending first node', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            throw $e;
        }

        $lastNodeId = $firstNodeIds[count($firstNodeIds) - 1];
        ChatbotConversationState::updateOrCreate(
            [
                'sender_id' => (string) $senderId,
                'facebook_page_id' => $facebookPage->id,
            ],
            [
                'flow_id' => $flow->id,
                'current_node_id' => $lastNodeId,
            ]
        );
    }

    /**
     * এই পেজের অ্যাক্টিভ বট ফ্লো থেকে স্টার্ট নোডের পরের নোডের মেসেজ পাঠান (keyword ম্যাচের জন্য, স্টেট ছাড়া)
     */
    private function sendFirstMessageFromBotFlow($facebookPage, $senderId, $pageAccessToken)
    {
        $flow = BotFlow::where('facebook_page_id', $facebookPage->id)
            ->where('is_active', true)
            ->first();
        if (!$flow || !$flow->flow_data) {
            return false;
        }
        return $this->sendFirstNodeMessageFromFlowData($flow->flow_data, $senderId, $pageAccessToken);
    }

    /**
     * কনভারসেশন স্টেট থাকলে বর্তমান নোডের পরের নোড পাঠান ও স্টেট আপডেট; ফ্লো এন্ড হলে স্টেট মুছুন
     */
    private function advanceBotFlowConversation($facebookPage, $senderId, $messageText, $pageAccessToken): bool
    {
        $state = ChatbotConversationState::where('sender_id', (string) $senderId)
            ->where('facebook_page_id', $facebookPage->id)
            ->first();
        if (!$state) {
            return false;
        }
        $flow = BotFlow::find($state->flow_id);
        if (!$flow || !$flow->flow_data) {
            $state->delete();
            return false;
        }
        $flowData = $flow->flow_data;
        $currentNode = $this->getNodeFromFlowData($flowData, $state->current_node_id);
        $class = $currentNode['class'] ?? $currentNode['type'] ?? '';

        // কোনো কিওয়ার্ড/ম্যাচ না হলে কোনো মেসেজ যাবে না। শুধু বাটন নোডে মেসেজ বাটনের টাইটেলের সাথে মিললেই পরবর্তী নোডে যাব; টেক্সট নোডে টেক্সট দিয়ে অ্যাডভান্স করব না
        if ($class === 'button') {
            $nextNodeId = $this->getNextNodeIdFromFlowDataByButtonPayload($flowData, $state->current_node_id, $messageText);
        } else {
            $nextNodeId = null; // টেক্সট/অন্যান্য নোড: টেক্সট মেসেজে অ্যাডভান্স করব না (কোনো ম্যাচ না হলে কোনো রিপ্লাই নাই)
        }

        if ($nextNodeId === null) {
            $state->delete();
            return true; // কোনো ম্যাচ না হলে কোনো রিপ্লাই দেবেন না
        }
        $sent = $this->sendNodeContentById($flowData, $nextNodeId, $senderId, $pageAccessToken, $facebookPage->user_id);
        if (!$sent) {
            $state->delete();
            return true;
        }
        $state->current_node_id = $nextNodeId;
        $state->save();
        return true;
    }

    /**
     * flow_data থেকে নোড অ্যারে নিন (drawflow structure)
     */
    private function getFlowDataArray(array $flowData): array
    {
        return $flowData['drawflow']['Home']['data'] ?? $flowData['data'] ?? [];
    }

    /**
     * স্টার্ট নোডের পরের প্রথম নোডের আইডি দিন
     */
    private function getFirstNodeIdAfterStart(array $flowData): ?string
    {
        $ids = $this->getFirstNodeIdsAfterStart($flowData);
        return $ids === [] ? null : $ids[0];
    }

    /**
     * স্টার্ট নোড থেকে যতগুলো নোডে কানেকশন আছে সব আইডি অ্যারে। এক ফ্লোতে স্টার্ট থেকে দুই নোড (২ ও ৩) থাকলে দুটোই পাঠাতে হবে।
     */
    private function getFirstNodeIdsAfterStart(array $flowData): array
    {
        $data = $this->getFlowDataArray($flowData);
        if (empty($data)) {
            return [];
        }
        $startId = null;
        foreach ($data as $nodeId => $node) {
            $class = $node['class'] ?? $node['type'] ?? '';
            if ($class === 'start') {
                $startId = (string) $nodeId;
                break;
            }
        }
        if ($startId === null) {
            return [];
        }
        return $this->getNextNodeIdsFromFlowData($flowData, $startId);
    }

    /**
     * বর্তমান নোডের পরের নোড আইডি (কানেকশন অনুযায়ী); নেইলে null
     */
    private function getNextNodeIdFromFlowData(array $flowData, string $currentNodeId): ?string
    {
        $ids = $this->getNextNodeIdsFromFlowData($flowData, $currentNodeId);
        return $ids === [] ? null : $ids[0];
    }

    /**
     * বর্তমান নোড থেকে যতগুলো নোডে কানেকশন আছে সব আইডি অ্যারে (ক্রমানুসারে)। স্টার্ট থেকে একাধিক নোড থাকলে সব পাঠাতে ব্যবহার।
     */
    private function getNextNodeIdsFromFlowData(array $flowData, string $currentNodeId): array
    {
        $data = $this->getFlowDataArray($flowData);
        $node = $data[$currentNodeId] ?? $data['node-' . $currentNodeId] ?? null;
        if (!$node || !is_array($node)) {
            return [];
        }
        $outputs = $node['outputs'] ?? [];
        $ids = [];
        $seen = [];
        foreach ($outputs as $out) {
            $conns = is_array($out) ? ($out['connections'] ?? []) : [];
            foreach ($conns as $c) {
                $nextId = $c['node'] ?? $c['output'] ?? null;
                if ($nextId !== null) {
                    $nextId = (string) $nextId;
                    if (!isset($seen[$nextId])) {
                        $seen[$nextId] = true;
                        $ids[] = $nextId;
                    }
                }
            }
        }
        return $ids;
    }

    /**
     * বাটন নোডে কতগুলো বাটন দেখাতে হবে – আউটপুট বিন্দুর সংখ্যা ও মোট কানেকশন দুটোই দেখা হয়।
     * data.buttons-এ যতগুলো টাইটেল আছে আর আউটপুট বিন্দু যতগুলো, তার সাথে মোট কানেকশন মিলিয়ে কাউন্ট।
     */
    private function getButtonNodeOutputCount(array $node): int
    {
        $outputs = $node['outputs'] ?? [];
        $maxKeyIndex = 0;
        $totalConnections = 0;
        foreach ($outputs as $k => $out) {
            if (preg_match('/^output_(\d+)$/i', (string) $k, $m)) {
                $maxKeyIndex = max($maxKeyIndex, (int) $m[1]);
                $conns = is_array($out) ? ($out['connections'] ?? []) : [];
                $totalConnections += count($conns);
            }
        }
        $rawButtons = $node['data']['buttons'] ?? [];
        $rawCount = is_array($rawButtons) ? count($rawButtons) : 0;
        $count = max($maxKeyIndex, $totalConnections);
        if ($maxKeyIndex >= 1 && $rawCount === $maxKeyIndex) {
            $count = $maxKeyIndex;
        }
        return min(3, max(1, $count));
    }

    /**
     * বাটন ইন্ডেক্স (০, ১, ২) অনুযায়ী ওই আউটপুটের প্রথম কানেকশনের নোড আইডি (পিছনের কম্প্যাটের জন্য)।
     */
    private function getNextNodeIdByButtonIndex(array $node, int $buttonIndex): ?string
    {
        $ids = $this->getNextNodeIdsByButtonIndex($node, $buttonIndex);
        return $ids[0] ?? null;
    }

    /**
     * বাটন ইন্ডেক্স অনুযায়ী ওই আউটপুটের সব কানেকশনের নোড আইডি অ্যারে।
     * একটা বাটনে দুইটা কানেকশন থাকলে দুইটা নোডের কন্টেন্টই ক্রমে পাঠানো হবে।
     */
    private function getNextNodeIdsByButtonIndex(array $node, int $buttonIndex): array
    {
        $outputs = $node['outputs'] ?? [];
        $outputKey = 'output_' . ($buttonIndex + 1);
        if (!isset($outputs[$outputKey])) {
            $outputKey = 'Output_' . ($buttonIndex + 1);
        }
        if (isset($outputs[$outputKey])) {
            $out = $outputs[$outputKey];
            $conns = is_array($out) ? ($out['connections'] ?? []) : [];
            $ids = [];
            foreach ($conns as $c) {
                if (!is_array($c)) continue;
                $nextId = $c['node'] ?? $c['nodeId'] ?? $c['targetNode'] ?? $c['target'] ?? $c['inputNode'] ?? $c['output'] ?? null;
                if ($nextId !== null && $nextId !== '') {
                    $ids[] = (string) $nextId;
                }
            }
            if (!empty($ids)) {
                return $ids;
            }
        }
        $keys = [];
        foreach (array_keys($outputs) as $k) {
            if (preg_match('/^output_(\d+)$/i', (string) $k, $m)) {
                $keys[(int) $m[1]] = $k;
            }
        }
        ksort($keys);
        $allConnections = [];
        foreach ($keys as $key) {
            $out = $outputs[$key];
            $conns = is_array($out) ? ($out['connections'] ?? []) : [];
            foreach ($conns as $c) {
                if (!is_array($c)) continue;
                $nextId = $c['node'] ?? $c['nodeId'] ?? $c['targetNode'] ?? $c['target'] ?? $c['inputNode'] ?? $c['output'] ?? null;
                if ($nextId !== null && $nextId !== '') {
                    $allConnections[] = (string) $nextId;
                }
            }
        }
        $one = $allConnections[$buttonIndex] ?? null;
        return $one !== null ? [$one] : [];
    }

    /**
     * বাটন নোডের জন্য কার্যকর বাটন লিস্ট – আউটপুট সংখ্যা অনুযায়ী।
     * data.buttons-এ যত আছে + যত আউটপুট আছে তত বাটন (কম থাকলে "বিকল্প ২"/"Option 2" দিয়ে পূরণ)।
     * এতে দুইটা কানেকশন থাকলে দুইটা বাটনই মেসেঞ্জারে আসে।
     */
    private function getEffectiveButtonsForButtonNode(array $flowData, string $nodeId): array
    {
        $node = $this->getNodeFromFlowData($flowData, $nodeId);
        if (!$node || !is_array($node) || (($node['class'] ?? $node['type'] ?? '') !== 'button')) {
            return [];
        }
        $nodeData = $node['data'] ?? [];
        $rawButtons = $nodeData['buttons'] ?? [];
        if (!is_array($rawButtons)) {
            $rawButtons = [];
        }
        $rawButtons = array_values($rawButtons);
        $outputCount = $this->getButtonNodeOutputCount($node);
        $effective = [];
        $placeholders = ['বিকল্প ১', 'বিকল্প ২', 'বিকল্প ৩'];
        for ($i = 0; $i < $outputCount; $i++) {
            $btn = $rawButtons[$i] ?? null;
            $title = $btn && isset($btn['title']) ? trim((string) $btn['title']) : '';
            if ($title === '') {
                $title = $placeholders[$i] ?? ('Option ' . ($i + 1));
            }
            $effective[] = [
                'title' => $title,
                'type' => $btn['type'] ?? 'postback',
                'url' => $btn['url'] ?? '',
            ];
        }
        return $effective;
    }

    /**
     * বাটন টাইটেল/পে‌লোড ম্যাচের জন্য নর্মালাইজ – যাতে ইউনিকোড ভিন্নতা (য/য় ইত্যাদি) বা জিরো-উইডথ কারেক্টারে ম্যাচ ফেইল না হয়।
     */
    private function normalizeButtonTitleForMatch(string $s): string
    {
        $s = preg_replace('/\s+/u', ' ', trim($s));
        $s = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}\x{00AD}]/u', '', $s);
        if (function_exists('normalizer_normalize')) {
            $s = (string) normalizer_normalize($s, \Normalizer::FORM_C);
        }
        return $s;
    }

    /**
     * বাটন নোডে কোন বাটন ক্লিক হয়েছে (payload) সেই অনুযায়ী পরবর্তী নোড আইডি দিন।
     * drawflow-এ প্রতিটি বাটনের জন্য আলাদা output থাকে (Output_1, Output_2, ...)।
     * আউটপুট সংখ্যা অনুযায়ী কার্যকর বাটন ব্যবহার করা হয় যাতে দুই/তিন কানেকশন থাকলে সব বাটন ম্যাচ হয়।
     */
    private function getNextNodeIdFromFlowDataByButtonPayload(array $flowData, string $currentNodeId, string $payload): ?string
    {
        Log::info('[Chatbot Button] getNextNodeIdByPayload START', [
            'current_node_id' => $currentNodeId,
            'payload' => $payload,
            'payload_length' => strlen($payload),
        ]);

        $data = $this->getFlowDataArray($flowData);
        $node = $data[$currentNodeId] ?? $data['node-' . $currentNodeId] ?? null;
        if (!$node || !is_array($node)) {
            Log::warning('[Chatbot Button] Node not found in flow data', ['current_node_id' => $currentNodeId, 'data_keys' => array_keys($data)]);
            return null;
        }
        $class = $node['class'] ?? $node['type'] ?? '';
        if ($class !== 'button') {
            Log::warning('[Chatbot Button] Current node is not button', ['class' => $class]);
            return null;
        }
        $buttons = $this->getEffectiveButtonsForButtonNode($flowData, $currentNodeId);
        if (empty($buttons)) {
            Log::warning('[Chatbot Button] No effective buttons for node', ['current_node_id' => $currentNodeId]);
            return null;
        }

        $buttonTitles = array_map(fn($b) => $b['title'] ?? '', $buttons);
        Log::info('[Chatbot Button] Button titles in flow', ['titles' => $buttonTitles]);

        $payloadTrim = trim($payload);
        $payloadNorm = preg_replace('/\s+/u', ' ', $payloadTrim);
        $payloadForMatch = $this->normalizeButtonTitleForMatch($payloadNorm);
        $buttonIndex = null;
        foreach ($buttons as $index => $btn) {
            $title = trim((string) ($btn['title'] ?? ''));
            if ($title === '') {
                continue;
            }
            $titleNorm = preg_replace('/\s+/u', ' ', $title);
            $titleShort = trim(mb_substr($title, 0, 20));
            $titleShortNorm = preg_replace('/\s+/u', ' ', $titleShort);
            $titleForMatch = $this->normalizeButtonTitleForMatch($titleNorm);
            $titleShortForMatch = $this->normalizeButtonTitleForMatch($titleShortNorm);
            if ($title === $payloadTrim || $titleShort === $payloadTrim
                || $titleNorm === $payloadNorm || $titleShortNorm === $payloadNorm
                || $titleForMatch === $payloadForMatch || $titleShortForMatch === $payloadForMatch
                || (mb_strlen($payloadForMatch) >= 2 && (mb_strpos($titleForMatch, $payloadForMatch) !== false || mb_strpos($payloadForMatch, $titleForMatch) !== false))) {
                $buttonIndex = $index;
                break;
            }
        }
        if ($buttonIndex === null) {
            Log::warning('[Chatbot Button] Payload did not match any button', [
                'payload_trim' => $payloadTrim,
                'titles' => $buttonTitles,
                'hint' => 'Check exact string match and 20-char truncate match',
            ]);
            return null;
        }

        Log::info('[Chatbot Button] Matched button index', ['button_index' => $buttonIndex]);

        $nextNodeIds = $this->getNextNodeIdsByButtonIndex($node, $buttonIndex);
        if (!empty($nextNodeIds)) {
            Log::info('[Chatbot Button] Next node(s) found', ['next_node_ids' => $nextNodeIds, 'count' => count($nextNodeIds)]);
            return $nextNodeIds[0];
        }
        Log::warning('[Chatbot Button] No connection for button index', ['button_index' => $buttonIndex]);
        return null;
    }

    /**
     * পে‌লোড ম্যাচ করলে ওই বাটনের সব কানেকশনের নোড আইডি অ্যারে (এক বাটনে একাধিক কানেকশন থাকলে সব নোড)।
     */
    private function getNextNodeIdsFromFlowDataByButtonPayload(array $flowData, string $currentNodeId, string $payload)
    {
        $data = $this->getFlowDataArray($flowData);
        $node = $data[$currentNodeId] ?? $data['node-' . $currentNodeId] ?? null;
        if (!$node || !is_array($node) || (($node['class'] ?? $node['type'] ?? '') !== 'button')) {
            return null;
        }
        $buttons = $this->getEffectiveButtonsForButtonNode($flowData, $currentNodeId);
        if (empty($buttons)) {
            return null;
        }
        $payloadTrim = trim($payload);
        $payloadNorm = preg_replace('/\s+/u', ' ', $payloadTrim);
        $payloadForMatch = $this->normalizeButtonTitleForMatch($payloadNorm);
        $buttonIndex = null;
        foreach ($buttons as $index => $btn) {
            $title = trim((string) ($btn['title'] ?? ''));
            if ($title === '') continue;
            $titleNorm = preg_replace('/\s+/u', ' ', $title);
            $titleShort = trim(mb_substr($title, 0, 20));
            $titleShortNorm = preg_replace('/\s+/u', ' ', $titleShort);
            $titleForMatch = $this->normalizeButtonTitleForMatch($titleNorm);
            $titleShortForMatch = $this->normalizeButtonTitleForMatch($titleShortNorm);
            if ($title === $payloadTrim || $titleShort === $payloadTrim
                || $titleNorm === $payloadNorm || $titleShortNorm === $payloadNorm
                || $titleForMatch === $payloadForMatch || $titleShortForMatch === $payloadForMatch
                || (mb_strlen($payloadForMatch) >= 2 && (mb_strpos($titleForMatch, $payloadForMatch) !== false || mb_strpos($payloadForMatch, $titleForMatch) !== false))) {
                $buttonIndex = $index;
                break;
            }
        }
        if ($buttonIndex === null) {
            return null;
        }
        return $this->getNextNodeIdsByButtonIndex($node, $buttonIndex);
    }

    /**
     * Recursively ensure all string values in an array are valid UTF-8 for JSON encoding.
     * Fixes "Malformed UTF-8 characters" when sending Bengali/Unicode to Facebook API.
     */
    private function ensureValidUtf8($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'ensureValidUtf8'], $data);
        }
        if (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        }
        return $data;
    }

    /**
     * flow data থেকে নোড খুঁজে দিন – আইডি যেকোনো ফরম্যাটে থাকলে (node-xxx বা xxx) মিলবে
     */
    private function getNodeFromFlowData(array $flowData, string $nodeId): ?array
    {
        $data = $this->getFlowDataArray($flowData);
        $id = $nodeId;
        if ($node = $data[$id] ?? null) {
            return is_array($node) ? $node : null;
        }
        if (str_starts_with($id, 'node-')) {
            $id = substr($id, 5);
            if ($node = $data[$id] ?? null) {
                return is_array($node) ? $node : null;
            }
        }
        $id = 'node-' . $nodeId;
        if ($node = $data[$id] ?? null) {
            return is_array($node) ? $node : null;
        }
        return null;
    }

    /**
     * যে সব বাটন নোডের আউটপুট বর্তমান নোডে কানেক্ট আছে (যেখান থেকে ইউজার এই নোডে এসেছে) – সেই নোডগুলোর আইডি দিন।
     * কারেন্ট নোডে পে‌লোড ম্যাচ না হলে এই ইনকামিং নোডগুলোতে ম্যাচ চেষ্টা করা হয় যাতে ফ্লো ঠিক থাকে।
     */
    private function getIncomingButtonNodeIds(array $flowData, string $currentNodeId): array
    {
        $data = $this->getFlowDataArray($flowData);
        $currentNum = str_starts_with($currentNodeId, 'node-') ? substr($currentNodeId, 5) : $currentNodeId;
        $incoming = [];
        foreach ($data as $nodeId => $node) {
            if (!is_array($node)) {
                continue;
            }
            $class = $node['class'] ?? $node['type'] ?? '';
            if ($class !== 'button') {
                continue;
            }
            $outputs = $node['outputs'] ?? [];
            foreach ($outputs as $out) {
                $conns = is_array($out) ? ($out['connections'] ?? []) : [];
                foreach ($conns as $c) {
                    if (!is_array($c)) {
                        continue;
                    }
                    $targetId = $c['node'] ?? $c['nodeId'] ?? $c['targetNode'] ?? $c['target'] ?? $c['inputNode'] ?? $c['output'] ?? null;
                    if ($targetId === null || $targetId === '') {
                        continue;
                    }
                    $targetNum = str_starts_with((string) $targetId, 'node-') ? substr((string) $targetId, 5) : (string) $targetId;
                    if ($targetNum === $currentNum || $targetNum === $currentNodeId) {
                        $incoming[] = (string) $nodeId;
                        break 2;
                    }
                }
            }
        }
        return $incoming;
    }

    /**
     * যে বাটন নোডের কোনো আউটপুট বর্তমান নোডে কানেক্ট আছে (যেখান থেকে ইউজার এই নোডে এসেছে) – সেই বাটন নোডের আইডি দিন।
     * ২য়/৩য় বাটন ক্লিকের সময় স্টেট আগের নোডে (টেক্সট) থাকলে এই বাটন নোড খুঁজে পোস্টব্যাক হ্যান্ডল করতে হবে।
     */
    private function findButtonNodeConnectedToNode(array $flowData, string $currentNodeId): ?string
    {
        $data = $this->getFlowDataArray($flowData);
        $currentNum = str_starts_with($currentNodeId, 'node-') ? substr($currentNodeId, 5) : $currentNodeId;
        foreach ($data as $nodeId => $node) {
            if (!is_array($node)) {
                continue;
            }
            $class = $node['class'] ?? $node['type'] ?? '';
            if ($class !== 'button') {
                continue;
            }
            $outputs = $node['outputs'] ?? [];
            foreach ($outputs as $out) {
                $conns = is_array($out) ? ($out['connections'] ?? []) : [];
                foreach ($conns as $c) {
                    if (!is_array($c)) {
                        continue;
                    }
                    $targetId = $c['node'] ?? $c['nodeId'] ?? $c['targetNode'] ?? $c['target'] ?? $c['inputNode'] ?? $c['output'] ?? null;
                    if ($targetId === null || $targetId === '') {
                        continue;
                    }
                    $targetNum = str_starts_with((string) $targetId, 'node-') ? substr((string) $targetId, 5) : (string) $targetId;
                    if ($targetNum === $currentNum || $targetNum === $currentNodeId) {
                        return (string) $nodeId;
                    }
                }
            }
        }
        return null;
    }

    /**
     * পোস্টব্যাক (বাটন ক্লিক) দিয়ে বট ফ্লো কনভারসেশন অ্যাডভান্স করুন।
     * বর্তমান নোড বাটন হলে সেই বাটন নোড থেকেই পরবর্তী নোড নিন; বাটন না হলে যে বাটন নোড থেকে বর্তমান নোডে কানেক্ট আছে সেটা থেকে (২য়/৩য় বাটন ক্লিক)।
     */
    private function advanceBotFlowConversationByPostback($facebookPage, $senderId, string $payload, $pageAccessToken): bool
    {
        $state = ChatbotConversationState::where('sender_id', (string) $senderId)
            ->where('facebook_page_id', $facebookPage->id)
            ->first();
        if (!$state) {
            return false;
        }
        $flow = BotFlow::find($state->flow_id);
        if (!$flow || !$flow->flow_data) {
            $state->delete();
            return false;
        }
        $flowData = $flow->flow_data;
        $data = $this->getFlowDataArray($flowData);
        $currentNode = $data[$state->current_node_id] ?? $data['node-' . $state->current_node_id] ?? null;
        if (!$currentNode || !is_array($currentNode)) {
            $state->delete();
            return false;
        }
        $class = $currentNode['class'] ?? $currentNode['type'] ?? '';
        $nextNodeId = null;
        $sourceButtonNodeId = null; // যে বাটন নোড থেকে পে‌লোড ম্যাচ হয়েছে – এক বাটনে একাধিক কানেকশন থাকলে সব নোড পাঠাতে লাগবে

        if ($class === 'button') {
            $nextNodeId = $this->getNextNodeIdFromFlowDataByButtonPayload($flowData, $state->current_node_id, $payload);
            if ($nextNodeId !== null) {
                $sourceButtonNodeId = $state->current_node_id;
            }
            // কারেন্ট বাটন নোডে ম্যাচ না হলে: যে নোড থেকে এ নোডে কানেক্ট (ইনকামিং বাটন নোড), সেখানে পে‌লোড ম্যাচ চেষ্টা করুন – যাতে আগের স্ক্রিনের বাটন ক্লিকও কাজ করে
            if ($nextNodeId === null) {
                $incomingButtonNodes = $this->getIncomingButtonNodeIds($flowData, $state->current_node_id);
                foreach ($incomingButtonNodes as $incomingId) {
                    $nextNodeId = $this->getNextNodeIdFromFlowDataByButtonPayload($flowData, $incomingId, $payload);
                    if ($nextNodeId !== null) {
                        $sourceButtonNodeId = $incomingId;
                        Log::info('[Chatbot Button] Matched via incoming button node', [
                            'current_node_id' => $state->current_node_id,
                            'incoming_node_id' => $incomingId,
                            'payload' => $payload,
                        ]);
                        break;
                    }
                }
            }
        } else {
            // বর্তমান নোড বাটন না (যেমন টেক্সট) – মানে ইউজার প্রথম বাটন ক্লিক করে এখানে এসেছে, এখন ২য়/৩য় বাটন ক্লিক করছে। যে বাটন নোড থেকে এ নোডে কানেক্ট সেই বাটন নোড থেকে পে‌লোড অনুযায়ী পরবর্তী নোড নিন।
            $buttonNodeId = $this->findButtonNodeConnectedToNode($flowData, $state->current_node_id);
            if ($buttonNodeId !== null) {
                Log::info('[Chatbot Button] Current node is not button; using source button node for postback', [
                    'current_node_id' => $state->current_node_id,
                    'button_node_id' => $buttonNodeId,
                    'payload' => $payload,
                ]);
                $nextNodeId = $this->getNextNodeIdFromFlowDataByButtonPayload($flowData, $buttonNodeId, $payload);
                if ($nextNodeId !== null) {
                    $sourceButtonNodeId = $buttonNodeId;
                }
            }
            if ($nextNodeId === null) {
                $nextNodeId = $this->getNextNodeIdFromFlowData($flowData, $state->current_node_id);
            }
        }
        if ($nextNodeId === null) {
            // কারেন্ট/ইনকামিং এ ম্যাচ না হলে প্রথমে ফ্লোর প্রথম বাটন নোড (বাংলা/ইংরেজি), তারপর ফ্লোর যেকোনো বাটন নোডে ম্যাচ চেষ্টা
            if ($this->tryHandlePostbackFromFirstButtonNode($facebookPage, $senderId, $payload, $pageAccessToken)) {
                Log::info('[Chatbot Button] Matched via first button node of flow', ['payload' => $payload]);
                return true;
            }
            if ($this->tryMatchPayloadAgainstAnyFlowButtonNode($flow, $flowData, $facebookPage, $senderId, $payload, $pageAccessToken)) {
                return true;
            }
            Log::warning('[Chatbot Button] advanceBotFlow: nextNodeId null – কোনো রিপ্লাই দেওয়া হবে না', [
                'payload' => $payload,
                'current_node_id' => $state->current_node_id,
                'current_node_class' => $class,
            ]);
            $state->delete();
            return true; // কোনো ম্যাচ না হলে কোনো রিপ্লাই দেবেন না
        }
        // এক বাটনে একাধিক কানেকশন থাকলে সব নোডের কনটেন্ট ক্রমে পাঠান
        $nextNodeIds = $sourceButtonNodeId !== null
            ? ($this->getNextNodeIdsFromFlowDataByButtonPayload($flowData, $sourceButtonNodeId, $payload) ?? [])
            : [];
        if (empty($nextNodeIds)) {
            $nextNodeIds = [$nextNodeId];
        }
        $sent = false;
        foreach ($nextNodeIds as $nid) {
            if ($this->sendNodeContentById($flowData, $nid, $senderId, $pageAccessToken, $facebookPage->user_id)) {
                $sent = true;
            }
        }
        if (!$sent) {
            $state->delete();
            return true;
        }
        $state->current_node_id = $nextNodeIds[count($nextNodeIds) - 1];
        $state->save();
        return true;
    }

    /**
     * ফ্লোর যেকোনো বাটন নোডে পে‌লোড ম্যাচ করলে সেই বাটনের পরের নোড পাঠান ও স্টেট আপডেট।
     * যেকোনো জায়গা থেকে "ইকমার্স"/"নিউজ পেপার" ইত্যাদি ক্লিক করলেও সঠিক পথে নিয়ে যাবে।
     */
    private function tryMatchPayloadAgainstAnyFlowButtonNode($flow, array $flowData, $facebookPage, $senderId, string $payload, $pageAccessToken): bool
    {
        $data = $this->getFlowDataArray($flowData);
        foreach ($data as $nodeId => $node) {
            if (!is_array($node)) continue;
            $class = $node['class'] ?? $node['type'] ?? '';
            if ($class !== 'button') continue;
            $nextNodeIds = $this->getNextNodeIdsFromFlowDataByButtonPayload($flowData, (string) $nodeId, $payload);
            if (!empty($nextNodeIds)) {
                $sent = false;
                foreach ($nextNodeIds as $nid) {
                    if ($this->sendNodeContentById($flowData, $nid, $senderId, $pageAccessToken, $facebookPage->user_id ?? null)) {
                        $sent = true;
                    }
                }
                if ($sent) {
                    $lastNodeId = $nextNodeIds[count($nextNodeIds) - 1];
                    ChatbotConversationState::updateOrCreate(
                        ['sender_id' => (string) $senderId, 'facebook_page_id' => $facebookPage->id],
                        ['flow_id' => $flow->id, 'current_node_id' => $lastNodeId]
                    );
                    Log::info('[Chatbot Button] Matched via any flow button node', ['matched_node_id' => $nodeId, 'payload' => $payload, 'nodes_sent' => count($nextNodeIds)]);
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * স্টেট নেই থাকলেও বাটন ক্লিক কাজ করবে: অ্যাক্টিভ ফ্লোর প্রথম নোড যদি বাটন হয় এবং পে‌লোড কোনো বাটনের টাইটেলের সাথে মিলে যায় তাহলে সেই বাটনের পরের নোড পাঠান ও স্টেট সেভ করুন (টেক্সট লিখে স্টেট ডিলিট হওয়ার পরও বাটন ক্লিক করলে রিপ্লাই পাবে)
     */
    private function tryHandlePostbackFromFirstButtonNode($facebookPage, $senderId, string $payload, $pageAccessToken): bool
    {
        $flow = BotFlow::where('facebook_page_id', $facebookPage->id)
            ->where('is_active', true)
            ->first();
        if (!$flow || !$flow->flow_data) {
            return false;
        }
        $flowData = $flow->flow_data;
        $firstNodeId = $this->getFirstNodeIdAfterStart($flowData);
        if ($firstNodeId === null) {
            return false;
        }
        $firstNode = $this->getNodeFromFlowData($flowData, $firstNodeId);
        if (!$firstNode || (($firstNode['class'] ?? $firstNode['type'] ?? '') !== 'button')) {
            return false;
        }
        $nextNodeIds = $this->getNextNodeIdsFromFlowDataByButtonPayload($flowData, $firstNodeId, $payload);
        if (empty($nextNodeIds)) {
            return false;
        }
        $sent = false;
        foreach ($nextNodeIds as $nid) {
            if ($this->sendNodeContentById($flowData, $nid, $senderId, $pageAccessToken, $facebookPage->user_id)) {
                $sent = true;
            }
        }
        if (!$sent) {
            return false;
        }
        $lastNodeId = $nextNodeIds[count($nextNodeIds) - 1];
        ChatbotConversationState::updateOrCreate(
            [
                'sender_id' => (string) $senderId,
                'facebook_page_id' => $facebookPage->id,
            ],
            [
                'flow_id' => $flow->id,
                'current_node_id' => $lastNodeId,
            ]
        );
        return true;
    }

    /**
     * একটি নোডের কন্টেন্ট (টেক্সট/ইমেজ/প্রোডাক্ট) মেসেঞ্জারে পাঠান
     */
    private function sendNodeContentById(array $flowData, string $nodeId, $senderId, $pageAccessToken, $userId = null): bool
    {
        $node = $this->getNodeFromFlowData($flowData, $nodeId);
        if (!$node) {
            return false;
        }
        $class = $node['class'] ?? $node['type'] ?? '';
        $nodeData = $node['data'] ?? [];
        $text = $nodeData['text'] ?? '';
        if ($class === 'end') {
            return true;
        }
        if ($class === 'product' && $userId !== null) {
            $productIds = $nodeData['product_ids'] ?? [];
            if (!is_array($productIds)) {
                $productIds = [];
            }
            $products = UserChatbotProduct::where('user_id', $userId)
                ->whereIn('id', $productIds)
                ->where('status', true)
                ->orderBy('sort_order')
                ->limit(10)
                ->get();
            if ($products->isNotEmpty()) {
                $this->sendProductCarousel($senderId, $products, $pageAccessToken);
                return true;
            }
            $this->sendSimpleTextMessage($senderId, 'এই মুহূর্তে প্রোডাক্ট দেখানো যাচ্ছে না।', $pageAccessToken);
            return true;
        }
        if ($class === 'welcome' || $class === 'text') {
            if ($text !== '') {
                return $this->sendSimpleTextMessage($senderId, $text, $pageAccessToken);
            }
        }
        if ($class === 'button') {
            $effectiveButtons = $this->getEffectiveButtonsForButtonNode($flowData, $nodeId);
            $buttons = [];
            foreach ($effectiveButtons as $btn) {
                $title = trim((string) ($btn['title'] ?? ''));
                if ($title === '') continue;
                $button = ['title' => mb_substr($title, 0, 20)];
                if (($btn['type'] ?? 'postback') === 'web_url' && !empty($btn['url'] ?? '')) {
                    $button['type'] = 'web_url';
                    $button['url'] = $btn['url'];
                } else {
                    $button['type'] = 'postback';
                    $button['payload'] = $title;
                }
                $buttons[] = $button;
            }
            if (count($buttons) > 0) {
                $messageData = [
                    'recipient' => ['id' => $senderId],
                    'message' => [
                        'attachment' => [
                            'type' => 'template',
                            'payload' => [
                                'template_type' => 'button',
                                'text' => $text ?: 'Please choose:',
                                'buttons' => array_slice($buttons, 0, 3), // Max 3 buttons
                            ],
                        ],
                    ],
                ];
                $messageData = $this->ensureValidUtf8($messageData);
                Http::withToken($pageAccessToken)->post('https://graph.facebook.com/v18.0/me/messages', $messageData);
                return true;
            }
        }
        if ($class === 'image' && !empty($nodeData['imageUrl'])) {
            $messageData = [
                'recipient' => ['id' => $senderId],
                'message' => [
                    'attachment' => [
                        'type' => 'image',
                        'payload' => ['url' => $nodeData['imageUrl'], 'is_reusable' => true],
                    ],
                ],
            ];
            $messageData = $this->ensureValidUtf8($messageData);
            Http::withToken($pageAccessToken)->post('https://graph.facebook.com/v18.0/me/messages', $messageData);
            if (!empty($nodeData['caption'])) {
                $this->sendSimpleTextMessage($senderId, $nodeData['caption'], $pageAccessToken);
            }
            return true;
        }
        if ($text !== '') {
            $this->sendSimpleTextMessage($senderId, $text, $pageAccessToken);
            return true;
        }
        return false;
    }

    /**
     * মেসেজ টেক্সট দিয়ে ম্যাচ করা অ্যাক্টিভ বট ফ্লো থেকে প্রথম মেসেজ পাঠান
     */
    private function sendFirstMessageFromMatchingBotFlow($facebookPage, $senderId, $messageText, $pageAccessToken)
    {
        \Log::info('Chatbot: Looking for keyword matching bot flows', [
            'page_id' => $facebookPage->id,
            'message' => $messageText
        ]);

        // শুধু যেসব ফ্লোতে কীওয়ার্ড সেট আছে — খালি কীওয়ার্ডে ফ্লো খুলবে না
        $flows = BotFlow::where('facebook_page_id', $facebookPage->id)
            ->where('is_active', true)
            ->get();

        \Log::info('Chatbot: Found active flows for keyword check', ['count' => $flows->count()]);

        foreach ($flows as $flow) {
            $keywords = $flow->trigger_values ?? [];
            if (!is_array($keywords)) {
                $keywords = [];
            }

            \Log::info('Chatbot: Checking flow', [
                'flow_id' => $flow->id,
                'flow_name' => $flow->name,
                'keywords' => $keywords
            ]);

            foreach ($keywords as $kw) {
                if (!$this->messageMatchesKeyword($messageText, (string) $kw)) {
                    continue;
                }

                \Log::info('Chatbot: Keyword matched!', [
                    'keyword' => $kw,
                    'flow_id' => $flow->id
                ]);

                try {
                    $firstNodeIds = $this->getFirstNodeIdsAfterStart($flow->flow_data);
                    if ($firstNodeIds === []) {
                        \Log::warning('Chatbot: No first node in flow', ['flow_id' => $flow->id]);
                        continue;
                    }
                    $sent = false;
                    foreach ($firstNodeIds as $nid) {
                        if ($this->sendNodeContentById(
                            $flow->flow_data,
                            $nid,
                            $senderId,
                            $pageAccessToken,
                            $facebookPage->user_id ?? null
                        )) {
                            $sent = true;
                        }
                    }
                    if ($sent) {
                        $lastNodeId = $firstNodeIds[count($firstNodeIds) - 1];
                        ChatbotConversationState::updateOrCreate(
                            [
                                'sender_id' => (string) $senderId,
                                'facebook_page_id' => $facebookPage->id,
                            ],
                            [
                                'flow_id' => $flow->id,
                                'current_node_id' => $lastNodeId,
                            ]
                        );
                        \Log::info('Chatbot: Flow opened by keyword, state saved', ['flow_id' => $flow->id, 'first_node_ids' => $firstNodeIds]);
                        return true;
                    }
                    \Log::warning('Chatbot: Flow first node(s) send returned false', ['flow_id' => $flow->id, 'first_node_ids' => $firstNodeIds]);
                } catch (\Exception $e) {
                    \Log::error('Chatbot: Error sending flow message', [
                        'error' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }

        \Log::info('Chatbot: No keyword matched');
        return false;
    }

    /**
     * কীওয়ার্ড ম্যাচ: পুরো শব্দ/ফ্রেজ হিসেবে।
     * "hi" → "parchi" তে মিলবে না; "h" → "hello" তেও মিলবে না।
     */
    private function messageMatchesKeyword(string $messageText, string $keyword): bool
    {
        $message = mb_strtolower(trim($messageText), 'UTF-8');
        $keyword = mb_strtolower(trim($keyword), 'UTF-8');

        if ($message === '' || $keyword === '') {
            return false;
        }

        if ($message === $keyword) {
            return true;
        }

        // শব্দ/ইউনিকোড অক্ষরের সীমানা — আংশিক অক্ষর ম্যাচ নয়
        $pattern = '/(?<![\p{L}\p{N}_])' . preg_quote($keyword, '/') . '(?![\p{L}\p{N}_])/u';

        return (bool) preg_match($pattern, $message);
    }

    /**
     * flow_data (drawflow export) থেকে স্টার্ট নোডের পরের নোডের কন্টেন্ট পাঠান
     */
    private function sendFirstNodeMessageFromFlowData(array $flowData, $senderId, $pageAccessToken)
    {
        $data = $flowData['drawflow']['Home']['data'] ?? $flowData['data'] ?? [];
        if (empty($data) || !is_array($data)) {
            return false;
        }
        $startId = null;
        foreach ($data as $nodeId => $node) {
            $class = $node['class'] ?? $node['type'] ?? '';
            if ($class === 'start') {
                $startId = (string) $nodeId;
                break;
            }
        }
        if ($startId === null) {
            return false;
        }
        $outputs = $data[$startId]['outputs'] ?? [];
        $nextId = null;
        foreach ($outputs as $out) {
            $conns = is_array($out) ? ($out['connections'] ?? []) : [];
            foreach ($conns as $c) {
                $nextId = $c['node'] ?? $c['output'] ?? null;
                if ($nextId !== null) {
                    $nextId = (string) $nextId;
                    break 2;
                }
            }
        }
        if ($nextId === null) {
            return false;
        }
        $nextNode = $data[$nextId] ?? $data['node-' . $nextId] ?? null;
        if (!$nextNode || !is_array($nextNode)) {
            return false;
        }
        $class = $nextNode['class'] ?? $nextNode['type'] ?? '';
        $nodeData = $nextNode['data'] ?? [];
        $text = $nodeData['text'] ?? '';
        if ($class === 'welcome' || $class === 'text') {
            if ($text !== '') {
                $this->sendSimpleTextMessage($senderId, $text, $pageAccessToken);
                return true;
            }
        }
        if ($class === 'image' && !empty($nodeData['imageUrl'])) {
            $messageData = [
                'recipient' => ['id' => $senderId],
                'message' => [
                    'attachment' => [
                        'type' => 'image',
                        'payload' => ['url' => $nodeData['imageUrl'], 'is_reusable' => true],
                    ],
                ],
            ];
            if (!empty($nodeData['caption'])) {
                $this->sendSimpleTextMessage($senderId, $nodeData['caption'], $pageAccessToken);
            }
            $messageData = $this->ensureValidUtf8($messageData);
            $response = Http::withToken($pageAccessToken)->post('https://graph.facebook.com/v18.0/me/messages', $messageData);
            if ($response->successful()) {
                return true;
            }
        }
        if ($text !== '') {
            $this->sendSimpleTextMessage($senderId, $text, $pageAccessToken);
            return true;
        }
        return false;
    }

    /**
     * Handle Comment Events (Post Comments)
     * 
     * পোস্ট কমেন্ট হ্যান্ডেল করা এবং automation চালানো
     * 
     * @param array $commentData
     * @param FacebookPage $facebookPage
     * @return void
     */
    private function handleCommentEvent($commentData, $facebookPage)
    {
        $commentId = $commentData['comment_id'] ?? null;
        $commentText = trim((string) ($commentData['message'] ?? ''));
        $from = $commentData['from'] ?? [];
        $senderId = is_array($from) ? ($from['id'] ?? null) : null;

        if (!$commentId || !$senderId) {
            Log::warning('Chatbot: Comment event missing comment_id or sender', [
                'comment_id' => $commentId,
                'has_from' => !empty($from),
                'page_id' => $facebookPage->page_id
            ]);
            return;
        }

        Log::info('Chatbot: Processing comment', [
            'comment_id' => $commentId,
            'comment_text' => $commentText,
            'sender_id' => $senderId,
            'page_id' => $facebookPage->page_id
        ]);

        // Comment automations টেবিল থেকে trigger word ম্যাচিং (খালি কমেন্টও ম্যাচ করতে পারে যদি trigger_word খালি থাকে)
        $automation = CommentAutomation::where('facebook_page_id', $facebookPage->id)
            ->where(function ($query) use ($commentText) {
                $query->where('trigger_word', 'LIKE', '%' . strtolower($commentText) . '%')
                      ->orWhereRaw('LOWER(?) LIKE CONCAT("%", LOWER(trigger_word), "%")', [strtolower($commentText)]);
            })
            ->first();

        if (!$automation) {
            Log::info('Chatbot: No comment automation matched', ['comment_text' => $commentText, 'page_id' => $facebookPage->id]);
            return;
        }

        try {
            // পাবলিক রিপ্লাই (কমেন্টের নিচে রিপ্লাই) – pages_manage_engagement পারমিশন দরকার
            if (!empty(trim((string) $automation->reply_comment))) {
                $this->replyToComment($commentId, $automation->reply_comment, $facebookPage->page_access_token);
            }

            // প্রাইভেট মেসেজ (ইনবক্সে মেসেজ)
            if (!empty(trim((string) $automation->private_reply))) {
                $this->sendPrivateMessage($senderId, $automation->private_reply, $facebookPage->page_access_token);
            }
        } catch (\Throwable $e) {
            Log::error('Chatbot: handleCommentEvent error', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'comment_id' => $commentId
            ]);
            report($e);
        }
    }

    /**
     * Send Message to User (Inbox)
     * 
     * ইউজারকে ইনবক্সে মেসেজ পাঠানো
     * 
     * @param string $recipientId
     * @param BotReply $botReply
     * @param string $pageAccessToken
     * @return void
     */
    private function sendMessage($recipientId, $botReply, $pageAccessToken)
    {
        $messageData = [
            'recipient' => ['id' => $recipientId],
            'message' => []
        ];

        // Reply type অনুযায়ী message তৈরি করা
        switch ($botReply->reply_type) {
            case 'text':
                $messageData['message']['text'] = $botReply->reply_text;
                break;

            case 'image':
                $messageData['message']['attachment'] = [
                    'type' => 'image',
                    'payload' => [
                        'url' => $botReply->attachment['image_url'] ?? '',
                        'is_reusable' => true
                    ]
                ];
                break;

            case 'product_carousel':
                $messageData['message']['attachment'] = [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => $botReply->attachment['products'] ?? []
                    ]
                ];
                break;

            default:
                $messageData['message']['text'] = $botReply->reply_text;
                break;
        }

        // Facebook Graph API এ message পাঠানো (UTF-8 স্যানিটাইজ যাতে বাংলা/ইউনিকোড এনকোডিং এরর না হয়)
        $messageData = $this->ensureValidUtf8($messageData);
        $response = Http::withToken($pageAccessToken)
            ->post('https://graph.facebook.com/v18.0/me/messages', $messageData);

        if ($response->successful()) {
            Log::info('Message sent successfully', [
                'recipient_id' => $recipientId,
                'reply_type' => $botReply->reply_type
            ]);
        } else {
            Log::error('Failed to send message', [
                'recipient_id' => $recipientId,
                'error' => $response->json()
            ]);
        }
    }

    /**
     * Reply to a Comment (Public Reply)
     * 
     * কমেন্টে পাবলিক রিপ্লাই করা
     * 
     * @param string $commentId
     * @param string $replyText
     * @param string $pageAccessToken
     * @return void
     */
    private function replyToComment($commentId, $replyText, $pageAccessToken)
    {
        $payload = $this->ensureValidUtf8(['message' => $replyText]);
        $response = Http::withToken($pageAccessToken)
            ->post("https://graph.facebook.com/v18.0/{$commentId}/comments", $payload);

        if ($response->successful()) {
            Log::info('Chatbot: Comment reply sent successfully', ['comment_id' => $commentId]);
        } else {
            $errorBody = $response->json();
            $errorMsg = $errorBody['error']['message'] ?? $response->body();
            $isPermissionError = ($response->status() === 403 && (
                strpos($errorMsg, 'pages_manage_engagement') !== false ||
                strpos($errorMsg, 'App Review') !== false ||
                strpos($errorMsg, 'pages_read_user_content') !== false
            ));
            if ($isPermissionError) {
                Log::warning('Chatbot: Comment public reply failed – Facebook App Review required', [
                    'comment_id' => $commentId,
                    'hint' => 'কমেন্টে পাবলিক রিপ্লাই দিতে pages_manage_engagement পারমিশনের জন্য Meta App Review জরুরি। দেখুন: CHATBOT_COMMENT_APP_REVIEW.md',
                    'facebook_error' => $errorMsg,
                ]);
            } else {
                Log::error('Chatbot: Failed to reply to comment', [
                    'comment_id' => $commentId,
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'error' => $errorBody,
                ]);
            }
        }
    }

    /**
     * Send Private Message (Inbox Message)
     * 
     * ইউজারকে প্রাইভেট মেসেজ পাঠানো
     * 
     * @param string $recipientId
     * @param string $messageText
     * @param string $pageAccessToken
     * @return void
     */
    private function sendPrivateMessage($recipientId, $messageText, $pageAccessToken)
    {
        $messageData = [
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $messageText]
        ];
        $messageData = $this->ensureValidUtf8($messageData);
        $response = Http::withToken($pageAccessToken)
            ->post('https://graph.facebook.com/v18.0/me/messages', $messageData);

        if ($response->successful()) {
            Log::info('Private message sent successfully', [
                'recipient_id' => $recipientId
            ]);
        } else {
            Log::error('Failed to send private message', [
                'recipient_id' => $recipientId,
                'error' => $response->json()
            ]);
        }
    }

    /**
     * Get Facebook Page Details
     * 
     * Facebook page এর details নিয়ে আসা
     * 
     * @param string $pageId
     * @param string $pageAccessToken
     * @return array|null
     */
    public function getPageDetails($pageId, $pageAccessToken)
    {
        try {
            $response = Http::withToken($pageAccessToken)
                ->get("https://graph.facebook.com/v18.0/{$pageId}", [
                    'fields' => 'id,name,access_token,picture'
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to get page details', [
                'page_id' => $pageId,
                'error' => $response->json()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Exception getting page details', [
                'error' => $e->getMessage()
            ]);
            
            return null;
        }
    }

    /**
     * Subscribe App to Page (Enable Webhooks)
     * 
     * Page এ webhook subscription activate করা
     * 
     * @param string $pageId
     * @param string $pageAccessToken
     * @return bool
     */
    public function subscribeAppToPage($pageId, $pageAccessToken)
    {
        try {
            $response = Http::withToken($pageAccessToken)
                ->post("https://graph.facebook.com/v18.0/{$pageId}/subscribed_apps", [
                    'subscribed_fields' => 'messages,messaging_postbacks,feed'
                ]);

            if ($response->successful()) {
                Log::info('App subscribed to page successfully', ['page_id' => $pageId]);
                return true;
            }

            Log::error('Failed to subscribe app to page', [
                'page_id' => $pageId,
                'error' => $response->json()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception subscribing app to page', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Set Messenger Profile: শুধু Get Started বাটন + Greeting (একটা বাটন), ও চ্যাটে Persistent Menu
     * Ice Breakers সরানো হয়েছে যাতে ওয়েলকামে শুধু "Get Started" দেখা যায়, তারপর বট ফ্লো চালু হয়।
     *
     * @param string $pageAccessToken Page Access Token
     * @return bool
     */
    private function setMessengerProfile($pageAccessToken)
    {
        $graphUrl = 'https://graph.facebook.com/v18.0/me/messenger_profile';

        // ১. Ice Breakers মুছে দিন – তাহলে ওয়েলকামে শুধু Get Started বাটন দেখাবে
        try {
            $delResponse = Http::withToken($pageAccessToken)->delete($graphUrl, [
                'fields' => ['ice_breakers'],
            ]);
            if ($delResponse->successful()) {
                Log::info('Messenger Profile ice_breakers removed');
            }
        } catch (\Exception $e) {
            Log::warning('Could not remove ice_breakers', ['error' => $e->getMessage()]);
        }

        // ২. Get Started + Greeting (ওয়েলকামে শুধু একটা Get Started বাটন)
        $profileOk = false;
        try {
            $response = Http::withToken($pageAccessToken)->post($graphUrl, [
                'get_started' => [
                    'payload' => 'GET_STARTED',
                ],
                'greeting' => [
                    [
                        'locale' => 'default',
                        'text' => 'স্বাগতম! কিভাবে সাহায্য করতে পারি?',
                    ],
                ],
            ]);
            if (!$response->successful()) {
                Log::error('Messenger Profile (get_started/greeting) failed', ['error' => $response->json()]);
                $errCode = (int) ($response->json('error.code') ?? 0);
                if ($errCode === 190) {
                    return false;
                }
            } else {
                Log::info('Messenger Profile (get_started/greeting) set successfully');
                $profileOk = true;
            }
        } catch (\Exception $e) {
            Log::error('Exception setting get_started/greeting', ['error' => $e->getMessage()]);
        }

        // ৩. Persistent Menu – চ্যাটে মেনু আইকনে অপশন (ওয়েলকামে নয়)
        try {
            $menuResponse = Http::withToken($pageAccessToken)->post($graphUrl, [
                'persistent_menu' => [
                    [
                        'locale' => 'default',
                        'composer_input_disabled' => false,
                        'call_to_actions' => [
                            [
                                'type' => 'postback',
                                'title' => '🛒 প্রোডাক্ট',
                                'payload' => 'PRODUCTS',
                            ],
                            [
                                'type' => 'postback',
                                'title' => '📞 সাপোর্ট',
                                'payload' => 'SUPPORT',
                            ],
                            [
                                'type' => 'postback',
                                'title' => '📋 অর্ডার',
                                'payload' => 'ORDER',
                            ],
                        ],
                    ],
                ],
            ]);
            if (!$menuResponse->successful()) {
                Log::error('Messenger Profile (persistent_menu) failed', ['error' => $menuResponse->json()]);
            } else {
                Log::info('Messenger Profile (persistent_menu) set successfully');
            }
        } catch (\Exception $e) {
            Log::error('Exception setting persistent_menu', ['error' => $e->getMessage()]);
        }

        return $profileOk;
    }

    /**
     * Send Product Carousel Response
     * $onlyChatbotProducts true হলে শুধু চ্যাটবট প্রোডাক্ট (UserChatbotProduct), অ্যাডমিনের প্রোডাক্ট দেখাবে না।
     *
     * @param string $senderId
     * @param string $pageAccessToken
     * @param FacebookPage $facebookPage
     * @param bool $onlyChatbotProducts true = শুধু চ্যাটবটের প্রোডাক্ট (বাটন/মেনু/প্রোডাক্ট কীওয়ার্ডের জন্য)
     * @return void
     */
    private function sendProductCarouselResponse($senderId, $pageAccessToken, $facebookPage, $onlyChatbotProducts = false)
    {
        $userId = $facebookPage->user_id;
        $userProducts = UserChatbotProduct::where('user_id', $userId)
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        if ($userProducts->isNotEmpty()) {
            $this->sendProductCarousel($senderId, $userProducts, $pageAccessToken);
            return;
        }

        // চ্যাটবটের প্রোডাক্টই শুধু দেখাতে বললে অ্যাডমিন প্রোডাক্ট ফ্যালব্যাক করব না
        if ($onlyChatbotProducts) {
            $this->sendSimpleTextMessage(
                $senderId,
                'দুঃখিত, এই মুহূর্তে কোনো প্রোডাক্ট নেই। পরে আবার চেষ্টা করুন।',
                $pageAccessToken
            );
            return;
        }

        $products = Product::where('status', 1)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        if ($products->isEmpty()) {
            $this->sendSimpleTextMessage(
                $senderId,
                'দুঃখিত, এই মুহূর্তে কোনো প্রোডাক্ট নেই। পরে আবার চেষ্টা করুন।',
                $pageAccessToken
            );
            return;
        }

        $this->sendProductCarousel($senderId, $products, $pageAccessToken);
    }

    /**
     * Send Product Carousel to User (Product অথবা UserChatbotProduct দুটোই সাপোর্ট)
     */
    private function sendProductCarousel($senderId, $products, $pageAccessToken)
    {
        $elements = [];

        foreach ($products as $product) {
            if ($product instanceof UserChatbotProduct) {
                $imageUrl = $product->image
                    ? (filter_var($product->image, FILTER_VALIDATE_URL) ? $product->image : asset('uploads/chatbot-products/'.$product->image))
                    : asset_image('assets/images/noimage.png');
                $productUrl = $product->product_url ?: url('/');
            } else {
                $imageUrl = $product->photo
                    ? url('assets/images/products/' . $product->photo)
                    : url('assets/images/noimage.png');
                $productUrl = route('frontend.product.details', $product->slug ?? $product->id);
            }

            $price = number_format($product->price ?? 0, 0);
            $element = [
                'title' => mb_substr($product->name, 0, 80),
                'subtitle' => "মূল্য: ৳{$price}\n" . mb_substr($product->description ?? 'বিস্তারিত দেখতে ক্লিক করুন', 0, 80),
                'image_url' => $imageUrl,
                'buttons' => [
                    [
                        'type' => 'web_url',
                        'url' => $productUrl,
                        'title' => '🛒 অর্ডার করুন'
                    ],
                    [
                        'type' => 'web_url',
                        'url' => $productUrl,
                        'title' => '📋 বিস্তারিত দেখুন'
                    ]
                ]
            ];
            $elements[] = $element;
        }

        // Carousel message data তৈরি করা
        $messageData = [
            'recipient' => ['id' => $senderId],
            'message' => [
                'attachment' => [
                    'type' => 'template',
                    'payload' => [
                        'template_type' => 'generic',
                        'elements' => $elements
                    ]
                ]
            ]
        ];
        $messageData = $this->ensureValidUtf8($messageData);
        // Facebook Graph API এ carousel পাঠানো
        $response = Http::withToken($pageAccessToken)
            ->post('https://graph.facebook.com/v18.0/me/messages', $messageData);

        if ($response->successful()) {
            Log::info('Product carousel sent successfully', [
                'recipient_id' => $senderId,
                'product_count' => count($elements)
            ]);
        } else {
            Log::error('Failed to send product carousel', [
                'recipient_id' => $senderId,
                'error' => $response->json()
            ]);
        }
    }

    /**
     * টাইপিং ইন্ডিকেটর দেখান (রিপ্লাই পাঠানোর আগে কল করুন)
     * ইউজার "..." টাইপিং দেখবে যতক্ষণ বট রিপ্লাই না করে।
     */
    private function sendTypingOn($recipientId, $pageAccessToken)
    {
        $response = Http::withToken($pageAccessToken)
            ->post('https://graph.facebook.com/v18.0/me/messages', [
                'recipient' => ['id' => $recipientId],
                'sender_action' => 'typing_on',
            ]);
        if (!$response->successful()) {
            Log::debug('Chatbot: typing_on failed', ['recipient_id' => $recipientId, 'error' => $response->json()]);
        }
    }

    /**
     * Send Simple Text Message
     * 
     * সাধারণ টেক্সট মেসেজ পাঠানো (helper method)
     * 
     * @param string $recipientId
     * @param string $messageText
     * @param string $pageAccessToken
     * @return void
     */
    private function sendSimpleTextMessage($recipientId, $messageText, $pageAccessToken): bool
    {
        $messageData = [
            'recipient' => ['id' => $recipientId],
            'message' => ['text' => $messageText]
        ];
        $messageData = $this->ensureValidUtf8($messageData);
        $response = Http::withToken($pageAccessToken)
            ->post('https://graph.facebook.com/v18.0/me/messages', $messageData);

        if ($response->successful()) {
            Log::info('Simple text message sent', ['recipient_id' => $recipientId]);
            return true;
        }

        $error = $response->json('error') ?? [];
        Log::error('Failed to send simple text message', [
            'recipient_id' => $recipientId,
            'status' => $response->status(),
            'body' => $response->body(),
            'error' => $response->json()
        ]);

        // টোকেন নষ্ট হলে স্পষ্ট লগ — Get Started “সফল” দেখালেও ইউজার কিছু পায় না
        if ((int) ($error['code'] ?? 0) === 190) {
            Log::error('Chatbot: Page access token INVALID — reconnect Facebook page', [
                'recipient_id' => $recipientId,
                'message' => $error['message'] ?? null,
                'subcode' => $error['error_subcode'] ?? null,
            ]);
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | User Dashboard Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Chatbot Dashboard
     */
    public function botSettings()
    {
        $user = auth()->user();
        
        $connectedPages = FacebookPage::where('user_id', $user->id)->where('is_connected', true)->count();
        $totalReplies = BotReply::whereHas('facebookPage', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        $totalAutomations = CommentAutomation::whereHas('facebookPage', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();
        
        // Get chatbot packages
        $packages = \App\Models\ChatbotPackage::where('status', 1)->orderBy('price', 'asc')->get();
        
        return view('user.chatbot.dashboard', compact('connectedPages', 'totalReplies', 'totalAutomations', 'packages'));
    }

    /**
     * Connect Page - Show Form
     * Session এ fb_pages থাকলে পেজ ড্রপডাউন দেখাবে, না থাকলে Facebook কানেক্ট বাটন
     */
    public function connectPage()
    {
        $fbPagesList = session('fb_pages_list', []); // [{id, name}, ...] শুধু ড্রপডাউনের জন্য
        return view('user.chatbot.connect-page', compact('fbPagesList'));
    }

    /**
     * Facebook OAuth এ রিডাইরেক্ট করুন (ইউজার তার পেজ লিস্ট নিয়ে আসার জন্য)
     */
    public function redirectToFacebookConnect()
    {
        $setting = FacebookAppSetting::getSetting();
        if (!$setting->isConfigured()) {
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'এডমিন Facebook App কনফিগার করেনি। সাপোর্টে যোগাযোগ করুন।');
        }

        $redirectUri = route('user.chatbot.facebook.callback');
        $state = csrf_token();
        session(['fb_oauth_state' => $state]);

        $params = http_build_query([
            'client_id'     => $setting->app_id,
            'redirect_uri'  => $redirectUri,
            // চেটবট/মেসেঞ্জারের জন্য প্রয়োজনীয় স্কোপ। pages_manage_engagement শুধু পেজ কমেন্ট মডারেশনের —
            // Meta App-এ সেট/অ্যাপ্রুভ না থাকলে "Invalid Scopes" দেয় (আগে এই স্কোপ চাওয়া হত না)।
            'scope'         => 'pages_show_list,pages_messaging,pages_manage_metadata,pages_read_engagement,business_management',
            'response_type' => 'code',
            'state'         => $state,
        ]);

        return redirect()->away('https://www.facebook.com/' . $setting->graph_version . '/dialog/oauth?' . $params);
    }

    /**
     * Facebook OAuth callback – code দিয়ে টোকেন নিয়ে /me/accounts কল করে পেজ লিস্ট সেশন এ রাখা
     */
    public function facebookCallback(Request $request)
    {
        if ($request->get('error')) {
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'Facebook লগইন বাতিল বা অনুমতি দেওয়া হয়নি।');
        }

        $code = $request->get('code');
        $state = $request->get('state');
        if (!$code || $state !== session('fb_oauth_state')) {
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'অবৈধ রিকোয়েস্ট। আবার চেষ্টা করুন।');
        }
        session()->forget('fb_oauth_state');

        $setting = FacebookAppSetting::getSetting();
        if (!$setting->isConfigured()) {
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'Facebook App কনফিগার নেই।');
        }

        $redirectUri = route('user.chatbot.facebook.callback');

        // Code দিয়ে User Access Token নেওয়া
        $tokenResponse = Http::get('https://graph.facebook.com/' . $setting->graph_version . '/oauth/access_token', [
            'client_id'     => $setting->app_id,
            'client_secret' => $setting->app_secret,
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        ]);

        if (!$tokenResponse->successful() || !$tokenResponse->json('access_token')) {
            Log::error('Facebook token exchange failed', ['body' => $tokenResponse->json()]);
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'Facebook টোকেন নেওয়া যায়নি। আবার চেষ্টা করুন।');
        }

        $userAccessToken = $tokenResponse->json('access_token');

        // Short-lived → Long-lived user token (পেজ টোকেনও দীর্ঘস্থায়ী হয়)
        $longLived = Http::get('https://graph.facebook.com/' . $setting->graph_version . '/oauth/access_token', [
            'grant_type'        => 'fb_exchange_token',
            'client_id'         => $setting->app_id,
            'client_secret'     => $setting->app_secret,
            'fb_exchange_token' => $userAccessToken,
        ]);

        if ($longLived->successful() && $longLived->json('access_token')) {
            $userAccessToken = $longLived->json('access_token');
            Log::info('Facebook long-lived user token obtained');
        } else {
            Log::warning('Facebook long-lived token exchange failed; using short-lived token', [
                'body' => $longLived->json(),
            ]);
        }

        // ইউজারের পেজ লিস্ট (প্রতিটি পেজের access_token সহ) — সব পেজ পেজে নেওয়া
        $pages = [];
        $accountsUrl = 'https://graph.facebook.com/' . $setting->graph_version . '/me/accounts';
        $query = ['fields' => 'id,name,access_token', 'limit' => 100];

        do {
            $accountsResponse = Http::withToken($userAccessToken)->get($accountsUrl, $query);
            $data = $accountsResponse->json();

            if (!$accountsResponse->successful()) {
                Log::error('Facebook me/accounts failed', ['body' => $data]);
                $errMsg = $data['error']['message'] ?? 'পেজ লিস্ট লোড করা যায়নি।';
                return redirect()->route('user.chatbot.connect.page')
                    ->with('error', $errMsg);
            }

            if (!empty($data['error'])) {
                Log::warning('Facebook me/accounts error in body', ['body' => $data]);
                return redirect()->route('user.chatbot.connect.page')
                    ->with('error', $data['error']['message'] ?? 'Facebook থেকে এরর আসছে।');
            }

            foreach ($data['data'] ?? [] as $pageRow) {
                $pages[] = $pageRow;
            }

            $next = $data['paging']['next'] ?? null;
            if ($next) {
                $accountsUrl = $next;
                $query = [];
            } else {
                $accountsUrl = null;
            }
        } while ($accountsUrl);

        if (empty($pages)) {
            Log::info('Facebook me/accounts returned empty', ['response' => $data ?? []]);
            return redirect()->route('user.chatbot.connect.page')
                ->with('error', 'পেজ লিস্ট খালি এসেছে। নিশ্চিত করুন: (১) আপনি যে Facebook অ্যাকাউন্ট দিয়ে লগইন করেছেন সেই অ্যাকাউন্টে কমপক্ষে একটি পেজের অ্যাডমিন এক্সেস আছে। (২) অ্যাপ ডেভেলপমেন্ট মোডে থাকলে Facebook App Dashboard থেকে নিজেকে Tester হিসেবে যোগ করুন। (৩) লগইন করার সময় সব পারমিশন অনুমোদন করুন। (৪) পেজ যদি Business Manager এর সাথে যুক্ত থাকে তাহলে business_management পারমিশন দিতে হতে পারে।');
        }

        // সেশনে পূর্ণ ডাটা রাখা (পেজ সিলেক্ট করলে টোকেন বের করার জন্য)
        session(['fb_pages' => $pages]);
        // ভিউ এর জন্য শুধু id ও name
        session(['fb_pages_list' => array_map(fn($p) => ['id' => $p['id'], 'name' => $p['name'] ?? $p['id']], $pages)]);

        return redirect()->route('user.chatbot.connect.page')
            ->with('success', 'আপনার পেজগুলো লোড হয়েছে (' . count($pages) . 'টি)। নিচ থেকে পেজ সিলেক্ট করে «পেজ কানেক্ট করুন» চাপুন — শুধু লগইন করলে টোকেন সেভ হয় না।');
    }

    /**
     * Store Connected Page – ইউজার শুধু page_id সিলেক্ট করবে, টোকেন সেশন থেকে নেওয়া হবে
     */
    public function storeConnectedPage(Request $request)
    {
        $request->validate([
            'page_id' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $pageId = $request->page_id;

        $fbPages = session('fb_pages', []);
        $selected = collect($fbPages)->firstWhere('id', $pageId);

        if (!$selected || empty($selected['access_token'])) {
            return redirect()->back()->with('error', 'পেজ সিলেক্ট করুন অথবা আবার Facebook দিয়ে কানেক্ট করুন। সেশন শেষ হয়ে থাকতে পারে।');
        }

        $existing = FacebookPage::where('user_id', $user->id)->where('page_id', $pageId)->first();

        // পুরোনো পেজ রিকানেক্ট/টোকেন রিফ্রেশে লিমিট আটকাবে না
        $limit = (int) ($user->connected_pages_limit ?? 0);
        if ($limit > 0 && !$existing) {
            $currentCount = FacebookPage::where('user_id', $user->id)->where('is_connected', true)->count();
            if ($currentCount >= $limit) {
                return redirect()->back()->with('error', 'আপনার প্ল্যানে সর্বোচ্চ ' . $limit . ' টি পেজ কানেক্ট করা যাবে।');
            }
        }

        $pageAccessToken = $selected['access_token'];

        if ($existing) {
            $existing->update([
                'page_name'         => $selected['name'] ?? $pageId,
                'page_access_token' => $pageAccessToken,
                'is_connected'      => true,
            ]);
            $page = $existing->fresh();
        } else {
            $page = FacebookPage::create([
                'user_id'           => $user->id,
                'page_id'           => $pageId,
                'page_name'         => $selected['name'] ?? $pageId,
                'page_access_token' => $pageAccessToken,
                'is_connected'      => true,
            ]);
        }

        $this->subscribeAppToPage($pageId, $pageAccessToken);
        $profileOk = $this->setMessengerProfile($pageAccessToken);
        session()->forget(['fb_pages', 'fb_pages_list']);

        if ($page->tokenIsDefinitelyInvalid()) {
            return redirect()->back()->with(
                'error',
                'টোকেন সেভ হয়েছে কিন্তু Facebook এখনো অবৈধ বলছে। Meta App-এ পারমিশন Advanced Access আছে কিনা দেখুন, অথবা অন্য অ্যাডমিন অ্যাকাউন্ট দিয়ে আবার চেষ্টা করুন।'
            );
        }

        $msg = $existing
            ? 'পেজ আপডেট ও কানেক্ট হয়েছে। নতুন টোকেন সেভ হয়েছে।'
            : 'পেজ সফলভাবে কানেক্ট হয়েছে।';

        if ($profileOk === false) {
            $msg .= ' তবে Get Started বাটন সেট ব্যর্থ — «বাটন সেট করুন» আবার চাপুন।';
        } else {
            $msg .= ' Get Started বাটন সেট করা হয়েছে।';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Disconnect Page
     */
    public function disconnectPage($id)
    {
        $page = FacebookPage::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $page->update(['is_connected' => false]);
        return redirect()->back()->with('success', 'পেজ ডিসকানেক্ট করা হয়েছে।');
    }

    /**
     * Get Started / Ice Breakers / Persistent Menu আবার সেট করুন (ইতিমধ্যে কানেক্টেড পেজের জন্য)
     * এডমিন বা ইউজার পেজ রিকানেক্ট না করেই বাটন রিফ্রেশ করতে পারবে।
     */
    public function setButtonsForPage($id)
    {
        $page = FacebookPage::where('id', $id)->where('user_id', auth()->id())->where('is_connected', true)->firstOrFail();

        if ($page->tokenIsDefinitelyInvalid()) {
            return redirect()->back()->with(
                'error',
                "{$page->page_name} পেজের Facebook টোকেন নষ্ট। «Facebook দিয়ে কানেক্ট» → পেজ সিলেক্ট → «পেজ কানেক্ট করুন» চাপুন (শুধু লগইন যথেষ্ট নয়)।"
            );
        }

        $ok = $this->setMessengerProfile($page->page_access_token);
        if (!$ok) {
            return redirect()->back()->with(
                'error',
                "{$page->page_name} এ বাটন সেট ব্যর্থ। টোকেন নষ্ট হতে পারে — আবার Facebook দিয়ে কানেক্ট করে পেজ কানেক্ট করুন।"
            );
        }

        return redirect()->back()->with('success', "{$page->page_name} পেজে Get Started ও মেনু বাটন সেট করা হয়েছে। মেসেঞ্জারে পুরোনো চ্যাট ডিলিট করে আবার Message করুন।");
    }

    /**
     * Reply Setup - List & Create
     */
    public function replySetup()
    {
        return view('user.chatbot.reply-setup');
    }

    /**
     * Edit Reply - Show edit form
     */
    public function editReply($id)
    {
        $reply = BotReply::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('facebookPage')->findOrFail($id);
        return view('user.chatbot.reply-edit', compact('reply'));
    }

    /**
     * Store Reply
     */
    public function storeReply(Request $request)
    {
        $request->validate([
            'facebook_page_id' => 'required|exists:facebook_pages,id',
            'trigger_keyword' => 'required|string|max:255',
            'reply_type' => 'required|in:text,image,product_carousel',
            'reply_text' => 'nullable|string',
            'image_url' => 'nullable|url',
            'product_ids' => 'nullable|array',
            'product_ids.*' => [Rule::exists('user_chatbot_products', 'id')->where('user_id', auth()->id())],
        ]);

        $page = FacebookPage::where('id', $request->facebook_page_id)
            ->where('user_id', auth()->id())
            ->where('is_connected', true)
            ->firstOrFail();

        $attachment = null;
        if ($request->reply_type === 'image' && $request->image_url) {
            $attachment = ['image_url' => $request->image_url];
        }
        if ($request->reply_type === 'product_carousel' && !empty($request->product_ids)) {
            $products = UserChatbotProduct::where('user_id', auth()->id())
                ->whereIn('id', $request->product_ids)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get();
            $elements = [];
            foreach ($products as $p) {
                $imageUrl = $p->image
                    ? (filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : asset('uploads/chatbot-products/'.$p->image))
                    : asset_image('assets/images/noimage.png');
                $productUrl = $p->product_url ?: url('/');
                $elements[] = [
                    'title' => mb_substr($p->name, 0, 80),
                    'subtitle' => 'মূল্য: ৳' . number_format($p->price ?? 0, 0),
                    'image_url' => $imageUrl,
                    'buttons' => [
                        ['type' => 'web_url', 'url' => $productUrl, 'title' => 'অর্ডার করুন'],
                    ],
                ];
            }
            $attachment = ['products' => $elements];
        }

        BotReply::create([
            'facebook_page_id' => $page->id,
            'keyword' => trim($request->trigger_keyword),
            'reply_text' => $request->reply_text ?? '',
            'reply_type' => $request->reply_type,
            'attachment' => $attachment,
        ]);

        return redirect()->back()->with('success', 'অটো রিপ্লাই সংরক্ষণ হয়েছে!');
    }

    /**
     * Update Reply
     */
    public function updateReply(Request $request, $id)
    {
        $reply = BotReply::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $request->validate([
            'trigger_keyword' => 'required|string|max:255',
            'reply_type' => 'required|in:text,image,product_carousel',
            'reply_text' => 'nullable|string',
            'image_url' => 'nullable|url',
            'product_ids' => 'nullable|array',
            'product_ids.*' => [Rule::exists('user_chatbot_products', 'id')->where('user_id', auth()->id())],
        ]);

        $attachment = null;
        if ($request->reply_type === 'image' && $request->image_url) {
            $attachment = ['image_url' => $request->image_url];
        }
        if ($request->reply_type === 'product_carousel' && !empty($request->product_ids)) {
            $products = UserChatbotProduct::where('user_id', auth()->id())
                ->whereIn('id', $request->product_ids)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get();
            $elements = [];
            foreach ($products as $p) {
                $imageUrl = $p->image
                    ? (filter_var($p->image, FILTER_VALIDATE_URL) ? $p->image : asset('uploads/chatbot-products/'.$p->image))
                    : asset_image('assets/images/noimage.png');
                $productUrl = $p->product_url ?: url('/');
                $elements[] = [
                    'title' => mb_substr($p->name, 0, 80),
                    'subtitle' => 'মূল্য: ৳' . number_format($p->price ?? 0, 0),
                    'image_url' => $imageUrl,
                    'buttons' => [
                        ['type' => 'web_url', 'url' => $productUrl, 'title' => 'অর্ডার করুন'],
                    ],
                ];
            }
            $attachment = ['products' => $elements];
        }

        $reply->update([
            'keyword' => trim($request->trigger_keyword),
            'reply_text' => $request->reply_text ?? '',
            'reply_type' => $request->reply_type,
            'attachment' => $attachment,
        ]);

        return redirect()->back()->with('success', 'অটো রিপ্লাই আপডেট হয়েছে!');
    }

    /**
     * Delete Reply
     */
    public function deleteReply($id)
    {
        $reply = BotReply::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        $reply->delete();
        return redirect()->back()->with('success', 'অটো রিপ্লাই ডিলিট হয়েছে!');
    }

    /**
     * Comment Automation - List & Create
     */
    public function commentAutomation()
    {
        return view('user.chatbot.comment-automation');
    }

    /**
     * Edit Comment Automation - Show edit form
     */
    public function editCommentAutomation($id)
    {
        $automation = CommentAutomation::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->with('facebookPage')->findOrFail($id);
        return view('user.chatbot.comment-edit', compact('automation'));
    }

    /**
     * Store Comment Automation
     */
    public function storeCommentAutomation(Request $request)
    {
        $request->validate([
            'facebook_page_id' => 'required|exists:facebook_pages,id',
            'trigger_word' => 'required|string|max:255',
            'reply_comment' => 'nullable|string',
            'private_reply' => 'nullable|string',
        ]);

        $page = FacebookPage::where('id', $request->facebook_page_id)
            ->where('user_id', auth()->id())
            ->where('is_connected', true)
            ->firstOrFail();

        CommentAutomation::create([
            'facebook_page_id' => $page->id,
            'trigger_word' => trim($request->trigger_word),
            'reply_comment' => $request->reply_comment ? trim($request->reply_comment) : null,
            'private_reply' => $request->private_reply ? trim($request->private_reply) : null,
        ]);

        return redirect()->back()->with('success', 'কমেন্ট অটোমেশন সংরক্ষণ হয়েছে!');
    }

    /**
     * Update Comment Automation
     */
    public function updateCommentAutomation(Request $request, $id)
    {
        $automation = CommentAutomation::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);

        $request->validate([
            'trigger_word' => 'required|string|max:255',
            'reply_comment' => 'nullable|string',
            'private_reply' => 'nullable|string',
        ]);

        $automation->update([
            'trigger_word' => trim($request->trigger_word),
            'reply_comment' => $request->reply_comment ? trim($request->reply_comment) : null,
            'private_reply' => $request->private_reply ? trim($request->private_reply) : null,
        ]);

        return redirect()->back()->with('success', 'কমেন্ট অটোমেশন আপডেট হয়েছে!');
    }

    /**
     * Delete Comment Automation
     */
    public function deleteCommentAutomation($id)
    {
        $automation = CommentAutomation::whereHas('facebookPage', function ($q) {
            $q->where('user_id', auth()->id());
        })->findOrFail($id);
        $automation->delete();
        return redirect()->back()->with('success', 'কমেন্ট অটোমেশন ডিলিট হয়েছে!');
    }

    /**
     * Pricing Plans
     */
    public function pricing()
    {
        $packages = \App\Models\ChatbotPackage::where('status', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('user.chatbot.pricing', compact('packages'));
    }

    /**
     * Purchase Plan - Uddoktapay Payment
     */
    public function purchasePlan(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:chatbot_packages,id'
        ]);
        
        $user = auth()->user();
        $package = \App\Models\ChatbotPackage::findOrFail($request->package_id);
        
        // Check if package is active
        if (!$package->status) {
            return redirect()->back()->with('error', 'এই প্যাকেজটি বর্তমানে উপলব্ধ নেই!');
        }
        
        // Check user has email
        if (!$user->email) {
            return redirect()->back()->with('error', 'পেমেন্ট করতে আপনার প্রোফাইলে ইমেইল যুক্ত করুন।');
        }
        
        // Get CreativePay settings
        if (!app(\App\Services\CreativePayService::class)->isConfigured()) {
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়ে সেটআপ নেই। Admin → CreativePay সেটিংস করুন।');
        }
        
        // Prepare payment request
        $requestData = [
            'full_name'    => $user->name,
            'email'        => $user->email,
            'amount'       => $package->price,
            'metadata'     => [
                'user_id'    => $user->id,
                'package_id' => $package->id,
                'type'       => 'chatbot_package'
            ],
            'redirect_url' => route('user.chatbot.payment.success'),
            'cancel_url'   => route('user.chatbot.payment.cancel'),
            'webhook_url'  => route('user.chatbot.payment.webhook'),
        ];
        
        try {
            $result = app(\App\Services\CreativePayService::class)->createCheckout($requestData);
            if (!empty($result['payment_url'])) {
                return redirect()->away($result['payment_url']);
            }
            
            return redirect()->back()->with('error', 'পেমেন্ট গেটওয়ে রেসপন্স এরর।');
            
        } catch (\Exception $e) {
            Log::error('Chatbot Payment Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'সার্ভার কানেকশন এরর। আবার চেষ্টা করুন।');
        }
    }
    
    /**
     * Payment Success Callback (NO AUTH - Like Webhook)
     */
    public function paymentSuccess(Request $request)
    {
        // ==================== STEP 1: Initial Logging ====================
        Log::info('============ CHATBOT PAYMENT SUCCESS CALLBACK START ============');
        Log::info('Request Method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request IP: ' . $request->ip());
        Log::info('All Request Data:', $request->all());
        Log::info('Auth Check: ' . (auth()->check() ? 'YES - User ID: ' . auth()->id() : 'NO'));
        Log::info('Session ID: ' . session()->getId());
        
        // Get invoice ID from request
        $invoiceId = $request->get('invoice_id') ?? $request->input('invoice_id');
        
        Log::info('Invoice ID Received: ' . ($invoiceId ?? 'NULL'));
        
        if (!$invoiceId) {
            Log::error('ERROR: Invoice ID not found in request');
            return redirect()->route('user.chatbot.pricing')
                ->with('error', 'ইনভয়েস আইডি পাওয়া যায়নি।');
        }
        
        // ==================== STEP 2: Verify Payment ====================
        try {
            $result = app(\App\Services\CreativePayService::class)->verify($invoiceId);
            if (!$result) {
                Log::error('ERROR: CreativePay verify failed');
                return redirect()->route('user.chatbot.pricing')
                    ->with('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ।');
            }
            Log::info('CreativePay verify response:', $result);
            
            // ==================== STEP 3: Process Payment ====================
            if (isset($result['status'])) {
                $status = $result['status'];
                Log::info('Payment Status: ' . $status);
                
                if (in_array($status, ['COMPLETED', 'SUCCESS', 'PENDING'])) {
                    
                    // Parse metadata
                    $meta = is_string($result['metadata']) 
                        ? json_decode($result['metadata'], true) 
                        : $result['metadata'];
                    
                    Log::info('Parsed Metadata:', $meta ?? []);
                    
                    if (!isset($meta['user_id']) || !isset($meta['package_id'])) {
                        Log::error('ERROR: Metadata missing user_id or package_id');
                        return redirect()->route('user.chatbot.pricing')
                            ->with('error', 'পেমেন্ট ডাটা সম্পূর্ণ নয়।');
                    }
                    
                    // Activate package
                    try {
                        Log::info('Starting package activation...');
                        
                        DB::transaction(function () use ($meta, $invoiceId, $status, $result) {
                            $this->activateChatbotPackage(
                                $meta['user_id'], 
                                $meta['package_id'], 
                                $invoiceId, 
                                $status, 
                                $result['amount']
                            );
                        });
                        
                        Log::info('Package activation completed successfully');
                        
                        // Login the user automatically
                        $user = User::find($meta['user_id']);
                        if ($user) {
                            auth()->login($user);
                            Log::info('User logged in automatically: ' . $user->id);
                        }
                        
                        Log::info('============ PAYMENT SUCCESS - REDIRECTING ============');
                        
                        if ($status === 'PENDING') {
                            return redirect()->route('user.chatbot.settings')
                                ->with('success', 'পেমেন্ট পেন্ডিং! অনুমোদিত হলে প্যাকেজ সক্রিয় হবে।');
                        } else {
                            return redirect()->route('user.chatbot.settings')
                                ->with('success', 'পেমেন্ট সফল! চ্যাটবট প্যাকেজ সক্রিয় হয়েছে।');
                        }
                        
                    } catch (\Exception $e) {
                        Log::error('ERROR: Package activation failed');
                        Log::error('Exception: ' . $e->getMessage());
                        Log::error('Stack Trace: ' . $e->getTraceAsString());
                        
                        return redirect()->route('user.chatbot.pricing')
                            ->with('error', 'প্যাকেজ অ্যাক্টিভেশন এরর: ' . $e->getMessage());
                    }
                    
                } else {
                    Log::warning('Payment status not acceptable: ' . $status);
                }
            } else {
                Log::error('ERROR: No status in Uddoktapay response');
            }
            
            Log::error('============ PAYMENT VERIFICATION FAILED ============');
            return redirect()->route('user.chatbot.pricing')
                ->with('error', 'পেমেন্ট ভেরিফিকেশন ব্যর্থ।');
            
        } catch (\Exception $e) {
            Log::error('============ CRITICAL ERROR ============');
            Log::error('Exception Message: ' . $e->getMessage());
            Log::error('Exception File: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Stack Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('user.chatbot.pricing')
                ->with('error', 'পেমেন্ট ভেরিফিকেশন এরর: ' . $e->getMessage());
        }
    }
    
    /**
     * Payment Cancel Callback
     */
    public function paymentCancel()
    {
        return redirect()->route('user.chatbot.pricing')
            ->with('error', 'পেমেন্ট বাতিল করা হয়েছে।');
    }
    
    /**
     * Payment Webhook (for background processing)
     */
    public function paymentWebhook(Request $request)
    {
        try {
            $data = $request->all();
            Log::info('Chatbot Payment Webhook:', $data);
            
            if (isset($data['status']) && in_array($data['status'], ['COMPLETED', 'SUCCESS'])) {
                $meta = is_string($data['metadata']) ? json_decode($data['metadata'], true) : $data['metadata'];
                
                DB::transaction(function () use ($meta, $data) {
                    $this->activateChatbotPackage(
                        $meta['user_id'], 
                        $meta['package_id'], 
                        $data['invoice_id'], 
                        $data['status'],
                        $data['amount']
                    );
                });
            }
            
            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Chatbot Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
    
    /**
     * Activate Chatbot Package (With Detailed Logging)
     */
    private function activateChatbotPackage($userId, $packageId, $invoiceId, $status, $amount)
    {
        Log::info('--- ACTIVATE CHATBOT PACKAGE START ---');
        Log::info('User ID: ' . $userId);
        Log::info('Package ID: ' . $packageId);
        Log::info('Invoice ID: ' . $invoiceId);
        Log::info('Status: ' . $status);
        Log::info('Amount: ' . $amount);
        
        // Find user
        $user = User::find($userId);
        if (!$user) {
            Log::error('ERROR: User not found with ID: ' . $userId);
            throw new \Exception('User not found: ' . $userId);
        }
        Log::info('User found: ' . $user->name . ' (' . $user->email . ')');
        
        // Find package
        $package = \App\Models\ChatbotPackage::find($packageId);
        if (!$package) {
            Log::error('ERROR: Package not found with ID: ' . $packageId);
            throw new \Exception('Package not found: ' . $packageId);
        }
        Log::info('Package found: ' . $package->name . ' - Price: ' . $package->price);
        
        // Log current user data
        Log::info('User data BEFORE update:', [
            'active_plan' => $user->active_plan,
            'plan_expiry' => $user->plan_expiry,
            'connected_pages_limit' => $user->connected_pages_limit,
            'monthly_message_limit' => $user->monthly_message_limit,
        ]);
        
        // Update user plan
        try {
            $user->active_plan = $package->name;
            $user->plan_expiry = now()->addDays($package->duration_days);
            $user->connected_pages_limit = $package->page_limit;
            $user->monthly_message_limit = $package->message_limit;
            $user->messages_sent_this_month = 0; // Reset counter
            
            $saved = $user->save();
            
            Log::info('User save result: ' . ($saved ? 'SUCCESS' : 'FAILED'));
            
            // Refresh and log updated data
            $user->refresh();
            Log::info('User data AFTER update:', [
                'active_plan' => $user->active_plan,
                'plan_expiry' => $user->plan_expiry,
                'connected_pages_limit' => $user->connected_pages_limit,
                'monthly_message_limit' => $user->monthly_message_limit,
            ]);
            
            Log::info('--- CHATBOT PACKAGE ACTIVATION SUCCESS ---');
            Log::info('Package: ' . $package->name);
            Log::info('User: ' . $user->name . ' (ID: ' . $user->id . ')');
            Log::info('Invoice: ' . $invoiceId);
            Log::info('Amount: ' . $amount);
            
        } catch (\Exception $e) {
            Log::error('ERROR: Failed to save user data');
            Log::error('Exception: ' . $e->getMessage());
            throw $e;
        }
    }
}
