<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * অ্যাডমিন প্যানেলে ক্রন জব / শিডিউলড কমান্ড দেখানো ও ম্যানুয়ালি রান করার অপশন।
 */
class CronJobController extends Controller
{
    /**
     * অ্যাডমিনের জন্য অনুমোদিত কমান্ড (শুধু এগুলো রান করা যাবে)
     */
    protected static $allowedCommands = [
        'sms:clean' => [
            'name'    => 'SMS হিস্ট্রি ক্লিন',
            'schedule'=> 'প্রতিদিন রাত ১২টা (০০:০০)',
            'desc'    => 'পুরনো এসএমএস হিস্ট্রি ক্লিয়ার করে।',
        ],
        'packages:process-expiry' => [
            'name'    => 'প্যাকেজ এক্সপাইরি প্রসেস',
            'schedule'=> 'প্রতিদিন ভোর ১টা (০১:০০)',
            'desc'    => 'ডোমেন, হোস্টিং (সাস্পেন্ড/এক্সপায়ার), ফ্রড ও চ্যাটবট প্যাকেজ এক্সপাইরি ডেট অনুযায়ী প্রসেস করে।',
        ],
    ];

    public function index()
    {
        $commands = [];
        foreach (self::$allowedCommands as $signature => $info) {
            $commands[] = [
                'signature' => $signature,
                'name'      => $info['name'],
                'schedule'  => $info['schedule'],
                'desc'      => $info['desc'],
            ];
        }

        return view('admin.cron.index', compact('commands'));
    }

    /**
     * কমান্ড এখনই রান করুন (শুধু অ্যালাউড লিস্ট থেকে)
     */
    public function run(Request $request)
    {
        $signature = $request->input('command');
        if (!isset(self::$allowedCommands[$signature])) {
            return back()->with('error', 'অনুমোদিত কমান্ড নয়।');
        }

        try {
            Artisan::call($signature);
            $output = Artisan::output();
            return back()->with('success', 'কমান্ড সফলভাবে রান হয়েছে।')->with('output', trim($output));
        } catch (\Throwable $e) {
            return back()->with('error', 'কমান্ড রান করতে সমস্যা: ' . $e->getMessage());
        }
    }
}
