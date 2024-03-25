<?php

namespace App\DataTables\Account;

use App\Models\Account\ManualInvoices;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class ManualInvoicesDataTable extends DataTable
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

        return $dataTable->addColumn('action', 'account.manual_invoices.datatables_actions');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ManualInvoices $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ManualInvoices $model)
    {
        return $model->newQuery();
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
            'action',
            ['name' => 'txn_status', 'title' => 'Status', 'data' => 'txn_status'],
            ['name' => 'id', 'title' => 'ID', 'data' => 'id'],
            ['name' => 'txn_amount', 'title' => 'Amount', 'data' => 'txn_amount'],
            ['name' => 'txn_currency', 'title' => 'Currency', 'data' => 'txn_currency'],
            // ['name' => 'txn_customer_id', 'title' => 'Customer Id', 'data' => 'txn_customer_id'],
            ['name' => 'txn_customer_name', 'title' => 'Customer Name', 'data' => 'txn_customer_name'],
            ['name' => 'txn_customer_email', 'title' => 'Customer Email', 'data' => 'txn_customer_email'],
            ['name' => 'txn_customer_mobile', 'title' => 'Customer Mobile', 'data' => 'txn_customer_mobile'],
            ['name' => 'txn_payment_type', 'title' => 'Payment Type', 'data' => 'txn_payment_type'],
            ['name' => 'txn_customer_bill_order_id', 'title' => 'Customer Bill Order Id', 'data' => 'txn_customer_bill_order_id'],
            ['name' => 'txn_gateway_options', 'title' => 'Gateway Options', 'data' => 'txn_gateway_options'],
            ['name' => 'txn_description', 'title' => 'Description', 'data' => 'txn_description'],
            ['name' => 'txn_expiry_datetime', 'title'=>'Expiry', 'data' => "txn_expiry_datetime"],
            ['name' => 'txn_platform_return_url', 'title' => 'Platform Return URL', 'data' => "txn_platform_return_url"],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'manual_invoices_datatable_' . time();
    }
}
