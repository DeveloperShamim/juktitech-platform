<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanSmsHistory extends Command
{
    protected $signature = 'sms:clean';
    protected $description = 'Delete SMS logs older than 30 days';

    public function handle()
    {
        $date = Carbon::now()->subDays(30);
        DB::table('sms_histories')->where('created_at', '<', $date)->delete();
        $this->info("Old SMS logs cleaned.");
    }
}