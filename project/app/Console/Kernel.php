<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CrudGenerator::class,
        Commands\CleanSmsHistory::class,
        Commands\ProcessPackageExpiry::class, // প্যাকেজ এক্সপাইরি (ডোমেন, হোস্টিং, চ্যাটবট)
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // প্রতিদিন রাত ১২টায় এসএমএস ক্লিনিং
        $schedule->command('sms:clean')->daily();

        // প্রতি ঘণ্টায় প্যাকেজ এক্সপাইরি (ডোমেন/হোস্টিং ডিঅ্যাকটিভ, চ্যাটবট)
        $schedule->command('packages:process-expiry')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}