<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManualInvoicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manual_invoices', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('txn_amount', false);
            $table->string('txn_currency');
            $table->text('txn_customer_id');
            $table->text('txn_customer_name');
            $table->string('txn_customer_email');
            $table->string('txn_customer_mobile');
            $table->string('txn_payment_type');
            $table->string('txn_customer_bill_order_id');
            $table->string('txn_gateway_options');
            $table->text('txn_description');
            $table->text('txn_platform_return_url');
            $table->enum('txn_status', ['draft', 'pending', 'completed', 'rejected', 'refunded', 'cancelled'])->default('draft');
            $table->dateTime('txn_expiry_datetime',0)->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('manual_invoices');
    }
}
