<?php

namespace App\Console\Commands;

use App\Api\V1\Helpers\StripeHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SyncStripeInvoicesHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stripe_invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is a cron job that sync the stripe data hourly to stripe_payments_log table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $stripeHelper = new StripeHelper();
        $stripeHelper->refreshStripeLogTable();
        Log::info('Crone run and Data synced with stripe invoices');
        $this->info('Crone run and Data synced with stripe invoices');
    }
}
