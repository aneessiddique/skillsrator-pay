<?php

namespace Database\Seeders;

use App\Gateway;
use Illuminate\Database\Seeder;

class GatewaysTableSeeder extends Seeder
{
    public function run()
    {
        $gateways = [
            [
                'id'                     => 1,
                'ec_pay_gateway_name'    => 'Jazz Cash',
                'ec_pay_gateway_url'     => 'jazzcash',
                'ec_pay_gateway_image'   => 'assets/img/payment-option-1.png', 
                'ec_pay_gateway_enabled' => true,
            ],
        ];

        Gateway::insert($gateways);
    }
}
