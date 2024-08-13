<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $data=[
          [  'plan_name' =>'Basic',
            'plan_price' =>30,
            'plan_features' =>json_encode(['3 Invoices per day','1 Sending per day','0 Upload invoices']),
        ],
        [  'plan_name' =>'Pro',
        'plan_price' =>10,
        'plan_features' =>json_encode(['Unlimited Invoices','Unlimited Sending','Upload invoices']),

    ],
        ];

        Plan::insert($data);

    }
}
