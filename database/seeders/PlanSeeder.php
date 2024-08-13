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

        Plan::insert($data);

    }
}
