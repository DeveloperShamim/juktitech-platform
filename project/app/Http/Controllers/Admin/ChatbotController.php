<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FacebookPage;
use App\Models\BotReply;
use App\Models\CommentAutomation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Display all connected Facebook pages across all users
     */
    public function connectedPages()
    {
        $pages = FacebookPage::with('user')
            ->where('is_connected', true)
            ->latest()
            ->paginate(20);
        
        return view('admin.chatbot.connected-pages', compact('pages'));
    }

    /**
     * Display chatbot analytics
     */
    public function analytics()
    {
        // Get statistics
        $stats = [
            'total_pages' => FacebookPage::where('is_connected', true)->count(),
            'total_users' => FacebookPage::where('is_connected', true)->distinct('user_id')->count('user_id'),
            'total_replies' => BotReply::count(),
            'total_automations' => CommentAutomation::count(),
            'active_bots' => FacebookPage::where('is_connected', true)->count(),
        ];

        // Get recent activities
        $recentPages = FacebookPage::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Get top users by pages connected
        $topUsers = FacebookPage::select('user_id', DB::raw('count(*) as pages_count'))
            ->where('is_connected', true)
            ->groupBy('user_id')
            ->orderByDesc('pages_count')
            ->limit(10)
            ->with('user')
            ->get();

        return view('admin.chatbot.analytics', compact('stats', 'recentPages', 'topUsers'));
    }

    /**
     * Disconnect a Facebook page (admin action)
     */
    public function disconnectPage($id)
    {
        $page = FacebookPage::findOrFail($id);
        $page->update([
            'is_connected' => false,
            'page_access_token' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Page disconnected successfully!');
    }
}
