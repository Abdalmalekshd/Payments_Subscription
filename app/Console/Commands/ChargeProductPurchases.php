<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserProduct;
use App\Jobs\ProcessProductPurchase;
use App\Models\User_Product;
use Illuminate\Support\Facades\Log;
class ChargeProductPurchases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:charge';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge all due product purchases';

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
     * @return int
     */
    public function handle()
    {
        Log::info('Charging product purchases...');

        // Retrieve all pending product purchases
        $userProducts = User_Product::where('subscription_end_date', '<=', now())->where('status', 'subscribe')->get();

        if ($userProducts->isEmpty()) {
            Log::info('No product purchases to charge.');
        } else {
            Log::info('Found ' . $userProducts->count() . ' product purchases to charge.');
        }

        foreach ($userProducts as $userProduct) {
            Log::info('Charging product purchase ID: ' . $userProduct->id);
            ProcessProductPurchase::dispatch($userProduct);
        }

        $this->info('All pending product purchases have been charged.');
    }
}