<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CreativePayAppQrService;
use Illuminate\Http\Request;

class CreativePayAppQrController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $admin = auth('admin')->user();
        if ((int) $admin->role_id !== 1) {
            abort(403, 'Only super admin can generate app QR login.');
        }

        return view('admin.payments.app_qr');
    }

    public function generate(Request $request, CreativePayAppQrService $qr)
    {
        $admin = auth('admin')->user();
        if ((int) $admin->role_id !== 1) {
            return response()->json(['ok' => false, 'message' => 'Only super admin allowed.'], 403);
        }

        $data = $qr->generate($admin);

        return response()->json([
            'ok'     => true,
            'message'=> 'QR code generated. Scan within 3 minutes.',
            'qr'     => $data,
        ]);
    }
}
