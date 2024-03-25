<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTxnResponseRefInTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('txn_ec_gateway_id')->after('txn_ec_platform_id');
            $table->string('txn_currency')->after('txn_amount');
            $table->string('txn_response_ref', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('txn_ec_gateway_id');
            $table->dropColumn('txn_currency');
        });
    }
}
