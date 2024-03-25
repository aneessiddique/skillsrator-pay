<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('txn_amount');
            $table->text('txn_customer_id');
            $table->text('txn_customer_name');
            $table->string('txn_customer_email');
            $table->string('txn_customer_mobile');
            $table->string('txn_payment_type', 100);
            $table->string('txn_customer_bill_order_id', 100);
            $table->string('txn_reference', 100);
            $table->integer('txn_ec_platform_id');
            $table->dateTime('txn_datetime',0)->useCurrent();
            $table->dateTime('txn_expiry_datetime',0)->useCurrent();
            $table->text('txn_description');
            $table->enum('txn_status', ['draft', 'pending', 'completed', 'rejected', 'refunded', 'cancelled'])->default('draft');
            $table->longText('txn_request')->nullable();
            $table->longText('txn_response')->nullable();
            $table->integer('txn_response_code')->nullable();
            $table->string('txn_response_ref', 25)->nullable();
            $table->text('txn_platform_return_url');
            $table->ipAddress('customer_ip');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
