<?php

namespace App\Console;

use App\Console\Commands\ConnectedAdvisor;
use App\Console\Commands\DiscoverCallLog;
use App\Console\Commands\DiscoverCallLogs;
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
//        Commands\DeleteExpiredActivations::class,
//        Commands\InvoicePaymentAtMidNight::class,
//        Commands\CreateInvoice::class,
//        Commands\TestCronEveryMinute::class,
//        Commands\AskForQuestionaire::class,
//        Commands\selectTherapist::class,
//        Commands\AppointmentReminder5Minute::class,
//        Commands\AppointmentReminder10Minutes::class,
//        Commands\AppointmentReminder24hour::class,
//        Commands\SendNotificationIfTherapistMatchFound::class,
//        Commands\ChangePakagePlanRequest::class,
//        Commands\retrieveSubscriptions::class,
        Commands\SyncStripeInvoicesHourly::class,
        ConnectedAdvisor::class,
        DiscoverCallLog::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

//        $schedule->command('activations:clean')->daily();
//        $schedule->command('invoices:create')->dailyAt('23:00');
//        $schedule->command('invoice:payment')->dailyAt('23:55');
//        $schedule->command('select:questionaire')->dailyAt('9:00');
//        $schedule->command('appointment:reminder24hour')->everyFifteenMinutes();
//        $schedule->command('appointment:reminder10minutes')->everyTenMinutes();
//        $schedule->command('appointment:reminder5minute')->everyFiveMinutes();
//        $schedule->command('check:match')->cron('0 */4 * * *');
//
//        $schedule->command('change:plan')->hourly(); // Payment for change plan
        $schedule->command('sync:stripe_invoices')->hourly(); //hourly Invoices from the stripe every hour.
        $schedule->command('sync:connected_advisor')->hourly(); //check flag client and send email and notification.
        $schedule->command('sync:discoveryCallLog')->hourly(); // Discovery call logs for client&advisor and send email and notification.

        // If therapist match send the notification // will work on it later.
//        $schedule->command('test:job')->everyMinute()->withoutOverlapping();

//        $schedule->command('retrieve:subscriptions')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */

    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
