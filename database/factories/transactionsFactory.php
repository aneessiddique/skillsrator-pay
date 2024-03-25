<?php

namespace Database\Factories\Account;

use App\Models\Account\transactions;
use Illuminate\Database\Eloquent\Factories\Factory;

class transactionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = transactions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'txn_amount' => $this->faker->randomDigitNotNull,
        'txn_gateway_fee' => $this->faker->randomDigitNotNull,
        'txn_currency' => $this->faker->word,
        'txn_customer_id' => $this->faker->text,
        'txn_customer_name' => $this->faker->text,
        'txn_customer_email' => $this->faker->word,
        'txn_customer_mobile' => $this->faker->word,
        'txn_payment_type' => $this->faker->word,
        'txn_customer_bill_order_id' => $this->faker->word,
        'txn_reference' => $this->faker->word,
        'txn_gateway_options' => $this->faker->word,
        'txn_ec_platform_id' => $this->faker->randomDigitNotNull,
        'txn_ec_gateway_id' => $this->faker->word,
        'txn_datetime' => $this->faker->date('Y-m-d H:i:s'),
        'txn_expiry_datetime' => $this->faker->date('Y-m-d H:i:s'),
        'txn_description' => $this->faker->text,
        'txn_status' => $this->faker->word,
        'txn_request' => $this->faker->text,
        'txn_response' => $this->faker->text,
        'txn_response_code' => $this->faker->randomDigitNotNull,
        'txn_response_ref' => $this->faker->word,
        'txn_platform_return_url' => $this->faker->text,
        'customer_ip' => $this->faker->word,
        'created_at' => $this->faker->date('Y-m-d H:i:s'),
        'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
