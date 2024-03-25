<?php

namespace App\Console;

use App\Mail\KuickpayReportMail;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
                $this->sendMail();
        })->daily();
        // })->everyFiveMinutes();
    }

    public function sendMail()
    {
        Log::info("kuickpay2 report email " . Date('Y-m-d H:i:s'));
        // Mail::to('shoaib.iqbal@ec.com.pk')
        Mail::to('ecin.finance@ec.com.pk')
        // ->bcc(['bilal@ec.com.pk'])
        ->send(new KuickpayReportMail());
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
