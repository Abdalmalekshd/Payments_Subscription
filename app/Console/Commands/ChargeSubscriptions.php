<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Subscription;
use App\Jobs\ChargeUserSubscription;
use Illuminate\Support\Facades\Log;
class ChargeSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:charge';

    protected $description = 'Charge all active subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('Charging subscriptions...');

        // Retrieve all active subscriptions that are due for renewal
        $subscriptions = Subscription::where('status', 'active')
            ->where('current_period_end', '<=', now())
            ->get();

        if ($subscriptions->isEmpty()) {
            Log::info('No subscriptions to charge.');
        } else {
            Log::info('Found ' . $subscriptions->count() . ' subscriptions to charge.');
        }

        foreach ($subscriptions as $subscription) {
            Log::info('Charging subscription ID: ' . $subscription->id);
            ChargeUserSubscription::dispatch($subscription);
        }

        $this->info('All active subscriptions have been charged.');
    }
}