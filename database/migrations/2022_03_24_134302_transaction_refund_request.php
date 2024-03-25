<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransactionRefundRequest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_refund_requests', function (Blueprint $table){
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->increments('id');
            $table->integer('txn_id');
            $table->integer('gateway_id');
            $table->integer('approved')->default(0);
            $table->integer('approved_by')->default(0);
            $table->text('approved_reason')->nullable();
            $table->integer('rejected')->default(0);
            $table->integer('rejected_by')->default(0);
            $table->text('reject_reason')->nullable();
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
        Schema::dropIfExists('transaction_refund_requests');
    }
}
