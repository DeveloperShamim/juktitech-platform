<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StolenDomain;
use App\Models\UnauthorizedLog;
use App\Services\CreativePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreativePayAppStolenController extends Controller
{
    public function __construct(private CreativePayService $pay) {}

    public function index(Request $request)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $items = StolenDomain::whereIn('id', function ($q) {
            $q->selectRaw('MAX(id)')
                ->from('stolen_domains')
                ->groupBy('domain_name');
        })->latest()->limit(500)->get()->map(fn (StolenDomain $row) => $this->formatRow($row));

        return response()->json([
            'ok'     => true,
            'items'  => $items,
            'counts' => [
                'total'   => StolenDomain::count(),
                'pending' => StolenDomain::where('status', 0)->count(),
                'blocked' => StolenDomain::where('is_blocked', 1)->count(),
            ],
        ]);
    }

    public function clearCache(Request $request, $id)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $record = StolenDomain::findOrFail($id);

        if (!empty($record->is_blocked)) {
            return response()->json([
                'ok'      => false,
                'message' => '"' . $record->domain_name . '" is blocked. Unblock first.',
            ], 422);
        }

        $newSignal = Str::random(32);
        $record->cache_signal = $newSignal;
        $record->status = 0;
        $record->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Cache signal updated for "' . $record->domain_name . '". Client cache clears within ~30 minutes.',
            'signal'  => $newSignal,
            'item'    => $this->formatRow($record->fresh()),
        ]);
    }

    public function toggleBlock(Request $request, $id)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $record = StolenDomain::findOrFail($id);

        try {
            $record->is_blocked = !empty($record->is_blocked) ? 0 : 1;
            $record->cache_signal = Str::random(32);
            $record->status = 0;
            $record->save();
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'is_blocked') || str_contains($e->getMessage(), 'Unknown column')) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'is_blocked column missing. Run php artisan migrate.',
                ], 422);
            }
            throw $e;
        }

        $message = !empty($record->is_blocked)
            ? '"' . $record->domain_name . '" blocked successfully.'
            : '"' . $record->domain_name . '" unblocked successfully.';

        return response()->json([
            'ok'      => true,
            'blocked' => !empty($record->is_blocked),
            'message' => $message,
            'item'    => $this->formatRow($record->fresh()),
        ]);
    }

    public function toggleKillSwitch(Request $request, $id)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        if (!Schema::hasTable('unauthorized_logs') || !Schema::hasColumn('unauthorized_logs', 'kill_switch')) {
            return response()->json([
                'ok'      => false,
                'message' => 'kill_switch column missing. Run php artisan migrate.',
            ], 422);
        }

        $record = StolenDomain::findOrFail($id);
        $domain = $record->domain_name;
        $currentlyOn = UnauthorizedLog::killSwitchWantsWipeOut($domain);
        $newOn = !$currentlyOn;

        if ($newOn) {
            $updated = UnauthorizedLog::where('domain', $domain)->update(['kill_switch' => true]);
            if ($updated === 0) {
                UnauthorizedLog::create([
                    'domain'       => $domain,
                    'ip'           => null,
                    'reason'       => '[Admin App] Kill switch — remote wipe_out enabled',
                    'url'          => null,
                    'attempted_at' => now(),
                    'kill_switch'  => true,
                ]);
            }
        } else {
            UnauthorizedLog::where('domain', $domain)->update(['kill_switch' => false]);
        }

        $message = $newOn
            ? '"' . $domain . '" — Kill switch ON. Client may receive action=wipe_out.'
            : '"' . $domain . '" — Kill switch OFF.';

        return response()->json([
            'ok'          => true,
            'kill_switch' => $newOn,
            'message'     => $message,
            'item'        => $this->formatRow($record->fresh()),
        ]);
    }

    public function toggleStatus(Request $request, $id)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $record = StolenDomain::findOrFail($id);
        $record->status = $record->status == 1 ? 0 : 1;
        $record->save();

        return response()->json([
            'ok'      => true,
            'message' => 'Status updated for "' . $record->domain_name . '".',
            'item'    => $this->formatRow($record->fresh()),
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $record = StolenDomain::findOrFail($id);
        $domain = $record->domain_name;
        $record->delete();

        return response()->json([
            'ok'      => true,
            'message' => 'Report deleted for "' . $domain . '".',
        ]);
    }

    public function destroyAll(Request $request)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        StolenDomain::truncate();

        return response()->json([
            'ok'      => true,
            'message' => 'All stolen reports deleted.',
        ]);
    }

    public function destroySelected(Request $request)
    {
        if (!$this->auth($request)) {
            return $this->unauthorized();
        }

        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(array_map('intval', explode(',', $ids)));
        }
        if (empty($ids)) {
            return response()->json([
                'ok'      => false,
                'message' => 'No items selected.',
            ], 422);
        }

        StolenDomain::whereIn('id', $ids)->delete();

        return response()->json([
            'ok'      => true,
            'message' => count($ids) . ' report(s) deleted.',
        ]);
    }

    protected function formatRow(StolenDomain $row): array
    {
        $killColumnOk = Schema::hasTable('unauthorized_logs')
            && Schema::hasColumn('unauthorized_logs', 'kill_switch');
        $killActive = false;
        if ($killColumnOk) {
            try {
                $killActive = UnauthorizedLog::killSwitchWantsWipeOut($row->domain_name);
            } catch (\Throwable $e) {
                $killActive = false;
            }
        }

        $isBlocked = !empty($row->is_blocked);
        $statusLabel = $isBlocked
            ? 'Blocked'
            : ($row->status == 1 ? 'Resolved' : 'Pending');

        return [
            'id'              => $row->id,
            'domain_name'     => $row->domain_name,
            'ip_address'      => $row->ip_address,
            'full_url'        => $row->full_url,
            'status'          => (int) $row->status,
            'status_label'    => $statusLabel,
            'is_blocked'      => $isBlocked,
            'cache_signal'    => $row->cache_signal,
            'cache_cleared'   => !empty($row->cache_signal),
            'kill_switch'     => $killActive,
            'kill_switch_ok'  => $killColumnOk,
            'created_at'      => $row->created_at?->format('Y-m-d H:i:s'),
            'created_human'   => $row->created_at?->diffForHumans(),
        ];
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
