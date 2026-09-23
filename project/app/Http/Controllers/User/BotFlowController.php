<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\BotFlow;
use App\Models\FacebookPage;
use App\Models\UserChatbotProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BotFlowController extends Controller
{
    /**
     * Display list of bot flows
     */
    public function index()
    {
        $flows = BotFlow::where('user_id', Auth::id())
            ->with('facebookPage')
            ->latest()
            ->paginate(10);
        
        return view('user.chatbot.flows.index', compact('flows'));
    }

    /**
     * Show create bot flow page
     */
    public function create()
    {
        $pages = FacebookPage::where('user_id', Auth::id())
            ->where('is_connected', true)
            ->get();
        
        $userProducts = UserChatbotProduct::where('user_id', Auth::id())
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'price']);
        
        return view('user.chatbot.flows.create', compact('pages', 'userProducts'));
    }

    /**
     * Store new bot flow
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facebook_page_id' => 'nullable|exists:facebook_pages,id',
            'flow_data' => 'required',
            'trigger_type' => 'required|in:keyword,button,menu',
            'trigger_values' => 'nullable',
        ]);

        $flowData = $request->flow_data;
        if (is_string($flowData)) {
            $flowData = json_decode($flowData, true);
        }
        if (!is_array($flowData)) {
            $flowData = [];
        }

        $triggerValues = $request->trigger_values;
        if (is_string($triggerValues)) {
            $triggerValues = json_decode($triggerValues, true);
        }

        $flow = BotFlow::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'facebook_page_id' => $request->facebook_page_id ?: null,
            'flow_data' => $flowData,
            'trigger_type' => $request->trigger_type,
            'trigger_values' => $triggerValues,
            'is_active' => false,
        ]);

        return redirect()
            ->route('user.chatbot.flows.edit', $flow->id)
            ->with('success', 'Bot Flow তৈরি হয়েছে!');
    }

    /**
     * Show edit bot flow page
     */
    public function edit($id)
    {
        $flow = BotFlow::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $pages = FacebookPage::where('user_id', Auth::id())
            ->where('is_connected', true)
            ->get();
        
        $userProducts = UserChatbotProduct::where('user_id', Auth::id())
            ->where('status', true)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get(['id', 'name', 'price']);
        
        return view('user.chatbot.flows.edit', compact('flow', 'pages', 'userProducts'));
    }

    /**
     * Update bot flow
     */
    public function update(Request $request, $id)
    {
        $flow = BotFlow::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facebook_page_id' => 'nullable|exists:facebook_pages,id',
            'flow_data' => 'required',
            'trigger_type' => 'required|in:keyword,button,menu',
            'trigger_values' => 'nullable',
        ]);

        $flowData = $request->flow_data;
        if (is_string($flowData)) {
            $flowData = json_decode($flowData, true);
        }
        if (!is_array($flowData)) {
            $flowData = [];
        }

        $triggerValues = $request->trigger_values;
        if (is_string($triggerValues)) {
            $triggerValues = json_decode($triggerValues, true);
        }

        $flow->update([
            'name' => $request->name,
            'description' => $request->description,
            'facebook_page_id' => $request->facebook_page_id ?: null,
            'flow_data' => $flowData,
            'trigger_type' => $request->trigger_type,
            'trigger_values' => $triggerValues,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Bot Flow আপডেট হয়েছে!');
    }

    /**
     * Delete bot flow
     */
    public function destroy($id)
    {
        $flow = BotFlow::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $flow->delete();

        return redirect()
            ->route('user.chatbot.flows.index')
            ->with('success', 'Bot Flow ডিলিট হয়েছে!');
    }

    /**
     * Toggle bot flow active status
     */
    public function toggleStatus($id)
    {
        $flow = BotFlow::where('user_id', Auth::id())
            ->findOrFail($id);
        
        $flow->is_active = !$flow->is_active;
        $flow->save();

        $status = $flow->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়';
        
        return redirect()
            ->back()
            ->with('success', "Bot Flow {$status} করা হয়েছে!");
    }
}
