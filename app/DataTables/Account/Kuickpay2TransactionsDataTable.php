<?php

namespace App\DataTables\Account;

use App\Transaction;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class Kuickpay2TransactionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable->addColumn('action', 'account.transactions.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\transactions $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Transaction $model)
    {
        return $model->newQuery()->with(['platform', 'gateway'])
            ->where('txn_response_ref', 'like', '%06880%');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters([
                'dom'       => 'Bfrtip',
                'stateSave' => false,
                'order'     => [[2, 'desc']],
                'buttons'   => [
                    // ['extend' => 'create', 'className' => 'btn btn-default btn-sm no-corner',],
                    // ['extend' => 'export', 'className' => 'btn btn-default btn-sm no-corner',],
                    // ['extend' => 'print', 'className' => 'btn btn-default btn-sm no-corner',],
                    // ['extend' => 'reset', 'className' => 'btn btn-default btn-sm no-corner',],
                    // ['extend' => 'reload', 'className' => 'btn btn-default btn-sm no-corner',],
                ],
                'initComplete' => "function () {
                    this.api().columns().every(function () {
                        var column = this;
                        if(column.header().title != 'Action'){
                        var input = document.createElement(\"input\");
                        $(input).appendTo($(column.header()))
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                        }
                    });
                }",
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            // 'action' => new \Yajra\DataTables\Html\Column([
            //     'title' => '',
            //     'data' => 'action',
            //     'name' => 'action'
            // ]),
            'action',
            ['name'=>'txn_status','title'=>'Status','data'=>"txn_status"], //'txn_status',
            ['name'=>'id','title'=>'ID','data'=>"id"], //'txn_amount',
            ['name'=>'txn_amount','title'=>'Amount','data'=>"txn_amount"], //'txn_amount',
            ['name'=>'txn_gateway_fee','title'=>'Gateway Fee','data'=>"txn_gateway_fee"], //'txn_gateway_fee',
            ['name'=>'txn_currency','title'=>'Currency','data'=>"txn_currency"], //'txn_currency',
            ['name'=>'txn_customer_id','title'=>'Customer ID','data'=>"txn_customer_id"], //'txn_customer_id',
            ['name'=>'txn_customer_name','title'=>'Customer Name','data'=>"txn_customer_name"], //'txn_customer_name',
            ['name'=>'txn_customer_email','title'=>'Customer Email','data'=>"txn_customer_email"], //'txn_customer_email',
            ['name'=>'txn_customer_mobile','title'=>'Customer Mobile','data'=>"txn_customer_mobile"], //'txn_customer_mobile',
            ['name'=>'txn_payment_type','title'=>'Type','data'=>"txn_payment_type"], //'txn_payment_type',
            ['name'=>'txn_customer_bill_order_id','title'=>'Order ID','data'=>"txn_customer_bill_order_id"], //'txn_customer_bill_order_id',
            ['name'=>'txn_reference','title'=>'Transaction Reference','data'=>"txn_reference"], //'txn_reference',
            ['name'=>'txn_response_ref','title'=>'Gateway Reference','data'=>"txn_response_ref"], //'txn_response_ref',
            ['name'=>'txn_gateway_options','title'=>'Gateway Options','data'=>"txn_gateway_options"], //'txn_gateway_options',
            // ['name'=>'txn_ec_platform_id','title'=>'Platform','data'=>"txn_ec_platform_id"],
            ['name'=>'txn_ec_platform_id','title'=>'Platform','data'=>"platform.ec_pay_app_name"],
            // ['name'=>'txn_ec_gateway_id','title'=>'Gateway ID','data'=>"txn_ec_gateway_id"],
            ['name'=>'txn_ec_gateway_id','title'=>'Gateway ID','data'=>"gateway.ec_pay_gateway_name"],
            ['name'=>'txn_datetime','title'=>'Date','data'=>"txn_datetime"], //'txn_datetime',
            ['name'=>'txn_expiry_datetime','title'=>'Expiry','data'=>"txn_expiry_datetime"], //'txn_expiry_datetime',
            ['name'=>'txn_description','title'=>'Description','data'=>"txn_description"], //'txn_description',
            // ['name'=>'txn_request','title'=>'txn_request','data'=>"txn_request"], //'txn_request',
            // ['name'=>'txn_response','title'=>'txn_response','data'=>"txn_response"], //'txn_response',
            // ['name'=>'txn_response_code','title'=>'txn_response_code','data'=>"txn_response_code"], //'txn_response_code',
            // ['name'=>'txn_platform_return_url','title'=>'txn_platform_return_url','data'=>"txn_platform_return_url"], //'txn_platform_return_url',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'transactions_datatable_' . time();
    }
}
