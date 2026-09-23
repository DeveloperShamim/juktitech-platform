<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookAppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class FacebookAppController extends Controller
{
    /**
     * টেবিলে redirect_url ইত্যাদি কলাম না থাকলে একবার যোগ করে নেয়
     */
    private function ensureFacebookAppSettingsColumns(): void
    {
        $table = 'facebook_app_settings';
        if (!Schema::hasTable($table)) {
            return;
        }
        $add = [];
        if (!Schema::hasColumn($table, 'redirect_url')) {
            $add[] = 'redirect_url';
        }
        if (!Schema::hasColumn($table, 'privacy_policy_url')) {
            $add[] = 'privacy_policy_url';
        }
        if (!Schema::hasColumn($table, 'terms_of_service_url')) {
            $add[] = 'terms_of_service_url';
        }
        if (!Schema::hasColumn($table, 'data_deletion_url')) {
            $add[] = 'data_deletion_url';
        }
        if (empty($add)) {
            return;
        }
        foreach ($add as $col) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE {$table} ADD COLUMN {$col} TEXT NULL");
        }
    }

    /**
     * Show Facebook App Settings
     * 
     * Facebook App configuration page
     */
    public function index()
    {
        $this->ensureFacebookAppSettingsColumns();
        $setting = FacebookAppSetting::getSetting();
        
        // Auto-generate verify token if not exists
        if (empty($setting->verify_token)) {
            $setting->update(['verify_token' => Str::random(32)]);
            $setting->refresh(); // Reload to get updated token
        }
        
        // Auto-generate webhook URL
        $webhookUrl = route('chatbot.webhook.handle');
        
        return view('admin.chatbot.app-settings', compact('setting', 'webhookUrl'));
    }

    /**
     * Update Facebook App Settings
     * 
     * Save Facebook App credentials
     */
    public function update(Request $request)
    {
        $this->ensureFacebookAppSettingsColumns();
        $validated = $request->validate([
            'app_id' => 'required|string|max:255',
            'app_secret' => 'required|string|max:500',
            'verify_token' => 'nullable|string|max:255',
            'graph_version' => 'nullable|string|max:20',
            'redirect_url' => 'nullable|url|max:500',
            'privacy_policy_url' => 'nullable|url|max:500',
            'terms_of_service_url' => 'nullable|url|max:500',
            'data_deletion_url' => 'nullable|url|max:500',
        ]);

        // Get or create settings
        $setting = FacebookAppSetting::getSetting();

        // Generate verify token if not provided
        if (empty($validated['verify_token'])) {
            $validated['verify_token'] = Str::random(32);
        }

        // Set default graph version
        if (empty($validated['graph_version'])) {
            $validated['graph_version'] = 'v18.0';
        }

        // Generate webhook URL
        $validated['webhook_url'] = route('chatbot.webhook.handle');

        // Update settings
        $setting->update($validated);

        // Update .env file (optional, for backup)
        $this->updateEnvFile([
            'FACEBOOK_APP_ID' => $validated['app_id'],
            'FACEBOOK_APP_SECRET' => $validated['app_secret'],
            'FACEBOOK_VERIFY_TOKEN' => $validated['verify_token'],
            'FACEBOOK_GRAPH_VERSION' => $validated['graph_version'],
        ]);

        Toastr::success('Facebook App settings updated successfully!', 'Success');
        return redirect()->route('admin.chatbot.app-settings.index');
    }

    /**
     * Test Facebook App Connection
     * 
     * Verify if App ID and Secret are valid
     */
    public function testConnection(Request $request)
    {
        $setting = FacebookAppSetting::getSetting();

        if (!$setting->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Please configure App ID and App Secret first.',
            ], 400);
        }

        try {
            // Test API call to verify credentials
            $response = Http::get("https://graph.facebook.com/{$setting->graph_version}/oauth/access_token", [
                'client_id' => $setting->app_id,
                'client_secret' => $setting->app_secret,
                'grant_type' => 'client_credentials',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['access_token'])) {
                    // Update connection status
                    $setting->update([
                        'is_connected' => true,
                        'connected_at' => now(),
                        'error_log' => null,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Facebook App connected successfully! ✅',
                    ]);
                }
            }

            // Connection failed
            $errorMessage = $response->json('error.message') ?? 'Invalid credentials';
            
            $setting->update([
                'is_connected' => false,
                'error_log' => $errorMessage,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Connection failed: ' . $errorMessage,
            ], 400);

        } catch (\Exception $e) {
            Log::error('Facebook App connection test failed', [
                'error' => $e->getMessage(),
            ]);

            $setting->update([
                'is_connected' => false,
                'error_log' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate new verify token
     */
    public function generateToken(Request $request)
    {
        try {
            $newToken = Str::random(32);
            
            $setting = FacebookAppSetting::getSetting();
            $setting->update(['verify_token' => $newToken]);

            // Update .env (optional, may fail in some environments)
            try {
                $this->updateEnvFile([
                    'FACEBOOK_VERIFY_TOKEN' => $newToken,
                ]);
            } catch (\Exception $envError) {
                Log::warning('Failed to update .env file', [
                    'error' => $envError->getMessage(),
                ]);
            }

            Log::info('Verify token generated successfully', [
                'token_length' => strlen($newToken),
            ]);

            return response()->json([
                'success' => true,
                'token' => $newToken,
                'message' => 'New verify token generated successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Token generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate token: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Disconnect Facebook App
     */
    public function disconnect(Request $request)
    {
        $setting = FacebookAppSetting::getSetting();
        
        $setting->update([
            'is_connected' => false,
            'connected_at' => null,
        ]);

        Toastr::info('Facebook App disconnected.', 'Info');
        return redirect()->route('admin.chatbot.app-settings.index');
    }

    /**
     * Update .env file with new values
     * 
     * @param array $data
     */
    private function updateEnvFile(array $data)
    {
        $envFile = base_path('.env');

        if (!file_exists($envFile)) {
            return;
        }

        $envContent = file_get_contents($envFile);

        foreach ($data as $key => $value) {
            $value = str_replace('"', '\"', $value); // Escape quotes
            
            // Check if key exists
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                // Update existing
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $envContent
                );
            } else {
                // Add new
                $envContent .= "\n{$key}=\"{$value}\"";
            }
        }

        file_put_contents($envFile, $envContent);
    }

    /**
     * Show webhook setup guide
     */
    public function webhookGuide()
    {
        $setting = FacebookAppSetting::getSetting();
        $webhookUrl = route('chatbot.webhook.handle');
        
        return view('admin.chatbot.webhook-guide', compact('setting', 'webhookUrl'));
    }
}
