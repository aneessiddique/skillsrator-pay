<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDepositSlipFieldsDatasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_deposit_slip_fields_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('deposit_slip_id');
            $table->integer('field_id');
            $table->text('field_value');
            $table->integer('txn_id');
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
        Schema::drop('transaction_deposit_slip_fields_data');
    }
}
