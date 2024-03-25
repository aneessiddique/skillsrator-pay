@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.transaction.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.id') }}
                        </th>
                        <td>
                            {{ $transaction->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_amount') }}
                        </th>
                        <td>
                            {{ $transaction->txn_amount }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_id') }}
                        </th>
                        <td>
                            {{ $transaction->txn_customer_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_name') }}
                        </th>
                        <td>
                            {{ $transaction->txn_customer_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_email') }}
                        </th>
                        <td>
                            {{ $transaction->txn_customer_email }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_mobile') }}
                        </th>
                        <td>
                            {{ $transaction->txn_customer_mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_payment_type') }}
                        </th>
                        <td>
                            {{ $transaction->txn_payment_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_customer_bill_order_id') }}
                        </th>
                        <td>
                            {{ $transaction->txn_customer_bill_order_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_reference') }}
                        </th>
                        <td>
                            {{ $transaction->txn_reference }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_response_ref') }}
                        </th>
                        <td>
                            {{ $transaction->txn_response_ref }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_response') }}
                        </th>
                        <td>
                            {{ $transaction->txn_response }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_ec_platform_id') }}
                        </th>
                        <td>
                            {{ $transaction->txn_ec_platform_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_ec_gateway_id') }}
                        </th>
                        <td>
                            {{ $transaction->txn_ec_gateway_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_datetime') }}
                        </th>
                        <td>
                            {{ $transaction->txn_datetime }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_expiry_datetime') }}
                        </th>
                        <td>
                            {{ $transaction->txn_expiry_datetime }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_description') }}
                        </th>
                        <td>
                            {{ $transaction->txn_description }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.transaction.fields.txn_status') }}
                        </th>
                        <td>
                            {{ $transaction->txn_status }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Action
                        </th>
                        <td>
                            {{-- <form action="{{ route('account.transactions.txnReconcile') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="txn_id" value="{{ $transaction->id }}">
                                <input type="submit" class="btn btn-xs btn-warning" value="{{ trans('cruds.transaction.fields.txn_txnReconcile') }}">
                            </form>

                            <form action="{{ route('account.transactions.refund') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="txn_id" value="{{ $transaction->id }}">
                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.refund') }}">
                            </form> --}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@include('layouts.footer')