<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultancy;
use App\Models\Contact;
use App\Models\DomainExtension;
use App\Models\HostingPlan;
use App\Models\HostingServer;
use App\Models\License;
use App\Models\StolenDomain;
use App\Models\User;
use App\Models\UserDomain;
use App\Models\UserHosting;
use App\Services\CreativePayService;
use App\Services\LicenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CreativePayAppManageController extends Controller
{
    public function __construct(private CreativePayService $pay) {}

    public function menu(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return $this->unauthorized();
        }

        $admin = $this->pay->resolveAdminFromAppRequest($request);
        $dashboard = app(\App\Services\CreativePayAppDashboardService::class)->build($admin);

        return response()->json([
            'ok'     => true,
            'counts' => array_merge($this->legacyMenuCounts(), $dashboard['counts']),
        ]);
    }

    public function dashboard(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return $this->unauthorized();
        }

        $admin = $this->pay->resolveAdminFromAppRequest($request);
        $payload = app(\App\Services\CreativePayAppDashboardService::class)->build($admin);

        return response()->json(array_merge(['ok' => true], $payload));
    }

    private function legacyMenuCounts(): array
    {
        return [
            'domain_ext'            => DomainExtension::count(),
            'user_domains'          => UserDomain::count(),
            'user_hostings'         => UserHosting::count(),
            'user_hostings_pending' => UserHosting::where('status', 'Pending')->count(),
        ];
    }

    public function users(Request $request)
    {
        if (!$this->pay->validateAdminAppRequest($request)) {
            return $this->unauthorized();
        }

        $users = User::orderBy('name')->limit(500)->get(['id', 'name', 'email', 'phone']);

        return response()->json(['ok' => true, 'items' => $users]);
    }

    public function alerts(Request $request)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $watermarks = [
            'consultancy_max_id'    => (int) (Consultancy::max('id') ?? 0),
            'contact_max_id'        => (int) (Contact::max('id') ?? 0),
            'ticket_max_id'         => (int) (DB::table('tickets')->max('id') ?? 0),
            'ticket_reply_max_id'   => (int) (DB::table('ticket_replies')->max('id') ?? 0),
        ];

        if ($request->boolean('init')) {
            return response()->json([
                'ok'         => true,
                'alerts'     => [],
                'watermarks' => $watermarks,
            ]);
        }

        $sinceConsultancy = (int) $request->get('since_consultancy_id', 0);
        $sinceContact = (int) $request->get('since_contact_id', 0);
        $sinceTicket = (int) $request->get('since_ticket_id', 0);
        $sinceTicketReply = (int) $request->get('since_ticket_reply_id', 0);

        $alerts = [];

        Consultancy::query()
            ->where('id', '>', $sinceConsultancy)
            ->where('status', 0)
            ->orderBy('id')
            ->limit(20)
            ->get()
            ->each(function ($row) use (&$alerts) {
                $alerts[] = [
                    'type'       => 'consultancy',
                    'id'         => $row->id,
                    'title'      => 'New Free Consultancy',
                    'body'       => trim($row->name . ' • ' . $row->phone),
                    'created_at' => $row->created_at?->format('Y-m-d H:i'),
                ];
            });

        Contact::query()
            ->where('id', '>', $sinceContact)
            ->where('status', 0)
            ->orderBy('id')
            ->limit(20)
            ->get()
            ->each(function ($row) use (&$alerts) {
                $subject = $row->subject ?: 'Contact message';
                $alerts[] = [
                    'type'       => 'contact',
                    'id'         => $row->id,
                    'title'      => 'New Contact Message',
                    'body'       => trim($row->name . ' — ' . $subject),
                    'created_at' => $row->created_at?->format('Y-m-d H:i'),
                ];
            });

        DB::table('tickets')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.id', 'tickets.subject', 'tickets.status', 'tickets.created_at', 'users.name as user_name')
            ->where('tickets.id', '>', $sinceTicket)
            ->orderBy('tickets.id')
            ->limit(20)
            ->get()
            ->each(function ($row) use (&$alerts) {
                $alerts[] = [
                    'type'       => 'ticket',
                    'event'      => 'new',
                    'id'         => $row->id,
                    'title'      => 'New Support Ticket',
                    'body'       => trim(($row->user_name ?: 'User') . ' — ' . $row->subject),
                    'created_at' => $row->created_at,
                ];
            });

        DB::table('ticket_replies')
            ->join('tickets', 'ticket_replies.ticket_id', '=', 'tickets.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select(
                'ticket_replies.id',
                'ticket_replies.ticket_id',
                'ticket_replies.message',
                'ticket_replies.created_at',
                'tickets.subject',
                'users.name as user_name'
            )
            ->where('ticket_replies.id', '>', $sinceTicketReply)
            ->whereNull('ticket_replies.admin_id')
            ->orderBy('ticket_replies.id')
            ->limit(20)
            ->get()
            ->each(function ($row) use (&$alerts) {
                $preview = mb_strimwidth(strip_tags((string) $row->message), 0, 80, '…');
                $alerts[] = [
                    'type'       => 'ticket',
                    'event'      => 'reply',
                    'id'         => (int) $row->ticket_id,
                    'reply_id'   => (int) $row->id,
                    'title'      => 'Ticket Reply',
                    'body'       => trim(($row->user_name ?: 'User') . ' — ' . $row->subject . ': ' . $preview),
                    'created_at' => $row->created_at,
                ];
            });

        return response()->json([
            'ok'         => true,
            'alerts'     => $alerts,
            'watermarks' => $watermarks,
        ]);
    }

    // ─── Consultancy ───────────────────────────────────────────

    public function consultancyIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = Consultancy::orderByDesc('id')->limit(200)->get()->map(fn ($r) => [
            'id'         => $r->id,
            'name'       => $r->name,
            'email'      => $r->email,
            'phone'      => $r->phone,
            'message'    => $r->message,
            'status'     => (int) $r->status,
            'status_label' => (int) $r->status === 1 ? 'Completed' : 'Pending',
            'created_at' => $r->created_at?->format('Y-m-d H:i'),
        ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function consultancyStore(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'name'  => 'required|max:255',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row = Consultancy::create([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'email'   => $request->email,
            'message' => $request->message,
            'status'  => (int) ($request->status ?? 0),
        ]);

        return response()->json(['ok' => true, 'message' => 'Consultancy created', 'item' => $row]);
    }

    public function consultancyUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $row = Consultancy::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'  => 'sometimes|required|max:255',
            'phone' => 'sometimes|required|max:20',
            'email' => 'nullable|email|max:255',
            'message' => 'nullable|string',
            'status' => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row->update($request->only(['name', 'phone', 'email', 'message', 'status']));

        return response()->json(['ok' => true, 'message' => 'Consultancy updated']);
    }

    public function consultancyDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        Consultancy::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    // ─── Contact ───────────────────────────────────────────────

    public function contactIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = Contact::orderByDesc('id')->limit(200)->get()->map(fn ($r) => [
            'id'         => $r->id,
            'name'       => $r->name,
            'email'      => $r->email,
            'subject'    => $r->subject,
            'message'    => $r->message,
            'status'     => (int) $r->status,
            'status_label' => (int) $r->status === 1 ? 'Read' : 'Unread',
            'created_at' => $r->created_at?->format('Y-m-d H:i'),
        ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function contactStore(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'name'    => 'required|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|max:255',
            'message' => 'required|string',
            'status'  => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status'  => (int) ($request->status ?? 0),
        ]);

        return response()->json(['ok' => true, 'message' => 'Contact message created']);
    }

    public function contactUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $row = Contact::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name'    => 'sometimes|required|max:255',
            'email'   => 'sometimes|required|email|max:255',
            'subject' => 'nullable|max:255',
            'message' => 'sometimes|required|string',
            'status'  => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row->update($request->only(['name', 'email', 'subject', 'message', 'status']));

        return response()->json(['ok' => true, 'message' => 'Contact updated']);
    }

    public function contactDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        Contact::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    // ─── Support Tickets ───────────────────────────────────────

    public function ticketIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = DB::table('tickets')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.*', 'users.name as user_name', 'users.email as user_email')
            ->orderByDesc('tickets.updated_at')
            ->limit(200)
            ->get()
            ->map(fn ($t) => [
                'id'         => $t->id,
                'user_id'    => $t->user_id,
                'user_name'  => $t->user_name,
                'subject'    => $t->subject,
                'message'    => $t->message,
                'priority'   => $t->priority ?? 'normal',
                'status'     => $t->status,
                'created_at' => $t->created_at,
                'updated_at' => $t->updated_at,
            ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function ticketShow(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $ticket = DB::table('tickets')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.*', 'users.name as user_name', 'users.email as user_email')
            ->where('tickets.id', $id)
            ->first();

        if (!$ticket) {
            return response()->json(['ok' => false, 'message' => 'Not found'], 404);
        }

        $replies = DB::table('ticket_replies')
            ->where('ticket_id', $id)
            ->orderBy('id')
            ->get()
            ->map(fn ($r) => [
                'id'         => $r->id,
                'message'    => $r->message,
                'is_admin'   => !empty($r->admin_id),
                'created_at' => $r->created_at,
            ]);

        return response()->json(['ok' => true, 'ticket' => $ticket, 'replies' => $replies]);
    }

    public function ticketStore(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|exists:users,id',
            'subject'  => 'required|max:255',
            'message'  => 'required|string',
            'priority' => 'nullable|in:low,normal,high',
            'status'   => 'nullable|in:pending,open,replied,closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $id = DB::table('tickets')->insertGetId([
            'user_id'    => $request->user_id,
            'subject'    => $request->subject,
            'message'    => $request->message,
            'priority'   => $request->priority ?? 'normal',
            'status'     => $request->status ?? 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Ticket created', 'id' => $id]);
    }

    public function ticketUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'subject'  => 'sometimes|required|max:255',
            'message'  => 'sometimes|required|string',
            'priority' => 'nullable|in:low,normal,high',
            'status'   => 'nullable|in:pending,open,replied,closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $updates = array_filter($request->only(['subject', 'message', 'priority', 'status']));
        $updates['updated_at'] = now();

        DB::table('tickets')->where('id', $id)->update($updates);

        return response()->json(['ok' => true, 'message' => 'Ticket updated']);
    }

    public function ticketReply(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), ['message' => 'required|string']);
        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $admin = $this->pay->resolveAdminFromAppRequest($request);

        DB::table('ticket_replies')->insert([
            'ticket_id'  => $id,
            'admin_id'   => $admin?->id,
            'user_id'    => null,
            'message'    => $request->message,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('tickets')->where('id', $id)->update([
            'status'     => 'replied',
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Reply sent']);
    }

    public function ticketUpdateStatus(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,open,replied,closed',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        DB::table('tickets')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true, 'message' => 'Status updated']);
    }

    public function ticketDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        DB::transaction(function () use ($id) {
            DB::table('ticket_replies')->where('ticket_id', $id)->delete();
            DB::table('tickets')->where('id', $id)->delete();
        });

        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    // ─── Domain Extensions (TLD pricing) ───────────────────────

    public function domainExtIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = DomainExtension::orderBy('extension')->get();

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function domainExtStore(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $validator = Validator::make($request->all(), [
            'extension'           => 'required|string|max:20',
            'registration_price'  => 'required|numeric|min:0',
            'renewal_price'       => 'required|numeric|min:0',
            'transfer_price'      => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row = DomainExtension::create($request->only([
            'extension', 'registration_price', 'renewal_price', 'transfer_price',
        ]));

        return response()->json(['ok' => true, 'message' => 'Domain extension created', 'item' => $row]);
    }

    public function domainExtUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $row = DomainExtension::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'extension'           => 'sometimes|required|string|max:20',
            'registration_price'  => 'sometimes|required|numeric|min:0',
            'renewal_price'       => 'sometimes|required|numeric|min:0',
            'transfer_price'      => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row->update($request->only([
            'extension', 'registration_price', 'renewal_price', 'transfer_price',
        ]));

        return response()->json(['ok' => true, 'message' => 'Updated']);
    }

    public function domainExtDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        DomainExtension::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    // ─── User Domains ──────────────────────────────────────────

    public function userDomainIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = UserDomain::with('user')->orderByDesc('id')->limit(200)->get()->map(fn ($d) => [
            'id'                 => $d->id,
            'user_id'            => $d->user_id,
            'user_name'          => $d->user?->name,
            'domain'             => $d->domain,
            'status'             => $d->status,
            'ns1'                => $d->ns1,
            'ns2'                => $d->ns2,
            'ns3'                => $d->ns3,
            'ns4'                => $d->ns4,
            'registration_date'  => $d->registration_date?->format('Y-m-d'),
            'next_due_date'      => $d->next_due_date?->format('Y-m-d'),
            'recurring_amount'   => $d->recurring_amount,
        ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function userDomainUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $row = UserDomain::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string|max:50',
            'ns1'    => 'nullable|string|max:255',
            'ns2'    => 'nullable|string|max:255',
            'ns3'    => 'nullable|string|max:255',
            'ns4'    => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $row->update($request->only(['status', 'ns1', 'ns2', 'ns3', 'ns4']));

        return response()->json(['ok' => true, 'message' => 'User domain updated']);
    }

    public function userDomainDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        UserDomain::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    // ─── User Hostings (cPanel accounts) ───────────────────────

    public function userHostingIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $query = UserHosting::with(['user', 'plan', 'server']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('domain', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('ip_address', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%")
                            ->orWhere('phone', 'LIKE', "%{$search}%");
                    });
            });
        }

        $items = $query->orderByDesc('id')->limit(200)->get()->map(fn ($h) => [
            'id'            => $h->id,
            'user_id'       => $h->user_id,
            'user_name'     => $h->user?->name,
            'user_email'    => $h->user?->email,
            'domain'        => $h->domain,
            'plan_id'       => $h->plan_id,
            'plan_name'     => $h->plan?->name,
            'server_id'     => $h->server_id,
            'server_name'   => $h->server?->name,
            'ip_address'    => $h->ip_address,
            'username'      => $h->username,
            'password'      => $h->password,
            'amount'        => $h->amount,
            'billing_cycle' => $h->billing_cycle,
            'billing_years' => $h->billing_years,
            'status'        => $h->status,
            'next_due_date' => $h->next_due_date?->format('Y-m-d'),
            'suspended_at'  => $h->suspended_at?->format('Y-m-d H:i'),
            'created_at'    => $h->created_at?->format('Y-m-d H:i'),
        ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function hostingPlanIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = HostingPlan::orderBy('name')->get(['id', 'name', 'server_id']);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function hostingServerIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = HostingServer::orderBy('name')->get(['id', 'name', 'hostname']);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function userHostingUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $hosting = UserHosting::with('server')->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'domain'        => 'sometimes|required|string|max:255',
            'plan_id'       => 'sometimes|required|exists:hosting_plans,id',
            'server_id'     => 'sometimes|required|exists:hosting_servers,id',
            'ip_address'    => 'nullable|string|max:45',
            'username'      => 'nullable|string|max:100',
            'password'      => 'nullable|string|max:255',
            'status'        => 'nullable|in:Active,Pending,Suspended,Terminated',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $newStatus = $request->input('status', $hosting->status);
        $server = $hosting->server;

        if ($request->has('status') && $server && $hosting->username) {
            $whmResult = $this->applyWhmStatusChange($server, $hosting->username, $newStatus);
            if ($whmResult !== null) {
                return response()->json(['ok' => false, 'message' => $whmResult], 422);
            }
        }

        $hosting->update($request->only([
            'domain', 'plan_id', 'server_id', 'ip_address', 'username', 'password', 'status',
        ]));

        return response()->json(['ok' => true, 'message' => 'Hosting updated']);
    }

    public function userHostingDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        UserHosting::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    protected function applyWhmStatusChange($server, string $cpUser, string $newStatus): ?string
    {
        $apiFunction = match ($newStatus) {
            'Suspended' => 'suspendacct',
            'Active'    => 'unsuspendacct',
            default     => '',
        };

        if ($apiFunction === '') {
            return null;
        }

        $whmUser = trim($server->username);
        $whmHash = str_replace(["\r", "\n", " "], '', $server->access_hash);
        $hostname = preg_replace('#^https?://|/$#', '', trim($server->hostname));
        $cpUser = trim($cpUser);
        $apiUrl = "https://{$hostname}:2087/json-api/{$apiFunction}?api.version=1&user={$cpUser}";

        try {
            $response = Http::withoutVerifying()
                ->timeout(15)
                ->withHeaders(['Authorization' => "whm $whmUser:$whmHash"])
                ->get($apiUrl);

            $result = $response->json();
            if (isset($result['metadata']['result']) && (int) $result['metadata']['result'] === 0) {
                return 'Server rejected: ' . ($result['metadata']['reason'] ?? 'Unknown error');
            }
        } catch (\Exception $e) {
            return 'Server connection error: ' . $e->getMessage();
        }

        return null;
    }

    // ─── Licenses ──────────────────────────────────────────────

    public function licenseIndex(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $items = License::with('user')->orderByDesc('id')->limit(200)->get()->map(fn ($l) => [
            'id'            => $l->id,
            'user_id'       => $l->user_id,
            'user_name'     => $l->user?->name,
            'user_email'    => $l->user?->email,
            'domain_name'   => $l->domain_name,
            'license_key'   => $l->license_key,
            'signature_key' => $l->signature_key,
            'site_path'     => $l->site_path,
            'expiry_date'   => $l->expiry_date?->format('Y-m-d'),
            'is_lifetime'   => $l->expiry_date === null,
            'status'        => (int) $l->status,
            'status_label'  => (int) $l->status === 1 ? 'Active' : 'Inactive',
        ]);

        return response()->json(['ok' => true, 'items' => $items]);
    }

    public function licenseShow(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $license = License::with('user')->findOrFail($id);

        return response()->json(['ok' => true, 'item' => $license]);
    }

    public function licenseStore(Request $request)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $normalizedDomain = License::normalizeHostInput($request->domain_name);
        if (!$normalizedDomain) {
            return response()->json(['ok' => false, 'message' => 'Invalid domain'], 422);
        }

        $request->merge(['domain_name' => $normalizedDomain]);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'required|exists:users,id',
            'domain_name' => 'required|unique:licenses,domain_name',
            'validity'    => 'required|integer|in:0,1,2,3',
            'site_path'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $licenseKey   = LicenseService::generateLicenseKey();
        $signatureKey = LicenseService::generateSignature($licenseKey, $normalizedDomain);
        $sitePath     = trim($request->site_path ?? '');

        $license = License::create([
            'user_id'       => $request->user_id,
            'domain_name'   => $normalizedDomain,
            'license_key'   => $licenseKey,
            'signature_key' => $signatureKey,
            'site_path'     => $sitePath ?: null,
            'expiry_date'   => LicenseService::expiryDate((int) $request->validity),
            'status'        => 1,
        ]);

        if ($sitePath !== '') {
            LicenseService::writeToEnv($sitePath, $licenseKey, $signatureKey);
        }

        LicenseService::pushToClientSite(
            $normalizedDomain,
            $licenseKey,
            $signatureKey,
            $license->expiry_date ? $license->expiry_date->toDateString() : null
        );

        return response()->json([
            'ok'      => true,
            'message' => 'License generated',
            'item'    => $license->fresh('user'),
        ]);
    }

    public function licenseUpdate(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();

        $license = License::findOrFail($id);
        $normalizedDomain = License::normalizeHostInput($request->domain_name ?? $license->domain_name);

        if (!$normalizedDomain) {
            return response()->json(['ok' => false, 'message' => 'Invalid domain'], 422);
        }

        $request->merge(['domain_name' => $normalizedDomain]);

        $validator = Validator::make($request->all(), [
            'user_id'     => 'sometimes|required|exists:users,id',
            'domain_name' => 'sometimes|required|unique:licenses,domain_name,' . $id,
            'status'      => 'sometimes|required|in:0,1',
            'site_path'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['ok' => false, 'message' => $validator->errors()->first()], 422);
        }

        $license->update($request->only(['user_id', 'domain_name', 'status', 'site_path']));

        if ($license->site_path && $license->license_key && $license->signature_key) {
            LicenseService::writeToEnv($license->site_path, $license->license_key, $license->signature_key);
        }

        LicenseService::pushToClientSite(
            $license->domain_name,
            $license->license_key,
            $license->signature_key,
            $license->expiry_date ? $license->expiry_date->toDateString() : null
        );

        return response()->json(['ok' => true, 'message' => 'License updated']);
    }

    public function licenseDelete(Request $request, $id)
    {
        if (!$this->auth($request)) return $this->unauthorized();
        License::findOrFail($id)->delete();
        return response()->json(['ok' => true, 'message' => 'Deleted']);
    }

    protected function auth(Request $request): bool
    {
        return $this->pay->validateAdminAppRequest($request);
    }

    protected function unauthorized()
    {
        return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
    }
}
