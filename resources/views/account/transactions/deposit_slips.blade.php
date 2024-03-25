@include('layouts.head')
@include('partials.header')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <!-- <a class="btn btn-success" href="{{ route("account.transactions.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.transaction.title_singular') }}
            </a> -->
        </div>
    </div>

<div class="card">
    <div class="card-header">
        Transactions with Deposit Slip List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-transaction">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_amount') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_email') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_mobile') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_payment_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_bill_order_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_reference') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_datetime') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_expiry_datetime') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_description') }}
                        </th>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_status') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($deposit_slips as $key => $deposit_slip)
                        <tr data-entry-id="{{ $deposit_slip->id }}">
                            {{-- {{dd($deposit_slip)}} --}}
                            <td>
                                <p>
                                    <a class="btn btn-xs btn-primary" href="{{ route('account.transactions.deposit_slip_transaction_show', $deposit_slip->id) }}" target="_blank">
                                        View
                                    </a>
                                </p>
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->id ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_amount ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_customer_name ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_customer_email ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_customer_mobile ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_payment_type ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_customer_bill_order_id ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_reference ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_datetime ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_expiry_datetime ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_description ?? '' }}
                            </td>
                            <td>
                                {{ $deposit_slip->transaction->txn_status ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
</main>

<script>

    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)


  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-transaction:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@include('layouts.footer')
