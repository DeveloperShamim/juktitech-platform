<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GeneralSettings;
use App\Services\CreativePayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreativePayAppAuthController extends Controller
{
    public function __construct(private CreativePayService $pay) {}

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok'      => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        if (!Auth::guard('admin')->attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            return response()->json([
                'ok'      => false,
                'message' => 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।',
            ], 401);
        }

        /** @var Admin $admin */
        $admin = Auth::guard('admin')->user();
        Auth::guard('admin')->logout();

        if ((int) $admin->verify !== 1) {
            return response()->json([
                'ok'      => false,
                'message' => 'Your email is not verified.',
            ], 403);
        }

        if ((int) $admin->role_id !== 1) {
            return response()->json([
                'ok'      => false,
                'message' => 'Only super admin can login to this app.',
            ], 403);
        }

        $apiKey = $this->pay->getApiKey();
        if (empty($apiKey)) {
            return response()->json([
                'ok'      => false,
                'message' => 'App API key is not configured. Set it in Admin → Payment settings.',
            ], 503);
        }

        $token = Str::random(64);
        $admin->update(['token' => $token]);

        return response()->json([
            'ok'          => true,
            'message'       => 'Login successful',
            'api_key'       => $apiKey,
            'admin_token'   => $token,
            'admin'         => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    public function loginWithQr(Request $request, \App\Services\CreativePayAppQrService $qr)
    {
        $request->validate([
            'qr_token' => 'required|string|min:16|max:128',
        ]);

        $admin = $qr->consume($request->qr_token);
        if (!$admin) {
            return response()->json([
                'ok'      => false,
                'message' => 'QR code invalid or expired. Generate a new QR from admin panel.',
            ], 401);
        }

        $apiKey = $this->pay->getApiKey();
        if (empty($apiKey)) {
            return response()->json([
                'ok'      => false,
                'message' => 'App API key is not configured. Set it in Admin → Payment settings.',
            ], 503);
        }

        $token = \Illuminate\Support\Str::random(64);
        $admin->update(['token' => $token]);

        return response()->json([
            'ok'          => true,
            'message'     => 'QR login successful',
            'api_key'     => $apiKey,
            'admin_token' => $token,
            'admin'       => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $admin = $this->pay->resolveAdminFromAppRequest($request);
        if ($admin) {
            $admin->update(['token' => null]);
        }

        return response()->json([
            'ok'      => true,
            'message' => 'Logged out',
        ]);
    }

    public function me(Request $request)
    {
        if (!$this->pay->validateAppRequest($request)) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized'], 401);
        }

        $admin = $this->pay->resolveAdminFromAppRequest($request);
        if (!$admin) {
            return response()->json(['ok' => false, 'message' => 'Invalid admin session'], 401);
        }

        return response()->json([
            'ok'    => true,
            'admin' => [
                'id'    => $admin->id,
                'name'  => $admin->name,
                'email' => $admin->email,
            ],
        ]);
    }

    public function webBridge(Request $request)
    {
        $token = (string) $request->query('token', '');
        $to = trim((string) $request->query('to', 'admin/dashboard'), '/');

        if ($token === '') {
            abort(403, 'Missing token');
        }

        $admin = Admin::where('token', $token)->whereNotNull('token')->first();
        if (!$admin || (int) $admin->role_id !== 1) {
            abort(403, 'Invalid or expired app session');
        }

        Auth::guard('admin')->login($admin);

        if (!str_starts_with($to, 'admin/')) {
            $to = 'admin/' . ltrim($to, '/');
        }

        return redirect('/' . $to);
    }
}
