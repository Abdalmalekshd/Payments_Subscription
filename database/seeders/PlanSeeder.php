<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;
use Stripe\StripeClient;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        {
            \Stripe\Stripe::setApiKey(config('services.stripe.sk'));

            $stripe = new StripeClient(config('services.stripe.sk'));

            $data=[
                [
                  'name'                  => 'Basic'
                  , 'monthly_price_id'    =>"price_1PlJPDGWolDHikV5DxiGvQUQ"
                  , 'yearly_price_id'     =>"price_1PlJPDGWolDHikV581nAapiJ"
                  , 'monthly_price'       =>10
                  , 'yearly_price'        =>120
                  , 'features'            =>json_encode(["3 Invoices per day","1 Sending per day","0 Upload invoices"]),
              ],
              [
                  'name'                  =>'Pro',
                  'monthly_price_id'      =>"price_1PlJPDGWolDHikV5DxiGvQUQ",
                  'yearly_price_id'       =>"price_1PlJPDGWolDHikV581nAapiJ",
                  'monthly_price'         =>30,
                  'yearly_price'          =>360,
                  'features'              =>json_encode(['Unlimited Invoices','Unlimited Sending','Upload invoices']),
      
          ],
              ];

            foreach ($data as $planData) {
                // Create or update monthly price
                $monthlyPrice = $stripe->prices->retrieve($planData['monthly_price_id']);
                if (!$monthlyPrice) {
                    $monthlyPrice = $stripe->prices->create([
                        'unit_amount' => $planData['monthly_price'] * 100,
                        'currency' => 'usd',
                        'recurring' => ['interval' => 'month'],
                        'product_data' => [
                            'name' => $planData['name'],
                            'metadata' => [
                                'subscription_type' => 'monthly',
                                'features' => $planData['features'],
                            ],
                        ],
                    ]);
                    $planData['monthly_price_id'] = $monthlyPrice->id;
                } else {
                    $stripe->prices->update($planData['monthly_price_id'], [
                        'metadata' => [
                            'subscription_type' => 'monthly',
                            'features' => $planData['features'],
                        ],
                    ]);
                }

                // Create or update yearly price
                $yearlyPrice = $stripe->prices->retrieve($planData['yearly_price_id']);
                if (!$yearlyPrice) {
                    $yearlyPrice = $stripe->prices->create([
                        'unit_amount' => $planData['yearly_price'] * 100,
                        'currency' => 'usd',
                        'recurring' => ['interval' => 'year'],
                        'product_data' => [
                            'name' => $planData['name'],
                            'metadata' => [
                                'subscription_type' => 'yearly',
                                'features' => $planData['features'],
                            ],
                        ],
                    ]);
                    $planData['yearly_price_id'] = $yearlyPrice->id;
                } else {
                    $stripe->prices->update($planData['yearly_price_id'], [
                        'metadata' => [
                            'subscription_type' => 'yearly',
                            'features' => $planData['features'],
                        ],
                    ]);
                }

                Plan::updateOrCreate(
                    ['name' => $planData['name']],
                    $planData
                );
            }
        }
    }
}