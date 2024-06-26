<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCloumnTxnCurrencyRateInManualInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manual_invoices', function (Blueprint $table) {
            $table->string('txn_currency_rate')->nullable()->after('txn_currency');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manual_invoices', function (Blueprint $table) {
            $table->dropColumn('txn_currency_rate');
        });
    }
}
