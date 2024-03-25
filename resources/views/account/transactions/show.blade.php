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
                            Transaction Gateway Fee
                        </th>
                        <td>
                            {{ $transaction->txn_gateway_fee }}
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
                            {{ $transaction->platform->ec_pay_app_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Transaction Gateway
                        </th>
                        <td>
                            {{ ($transaction->gateway ? $transaction->gateway->ec_pay_gateway_name : '') }}
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
                    <tr style="display:none">
                        <th>
                            Transaction Request
                        </th>
                        <td style="overflow:scroll">
                            {{ $transaction->txn_request }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Action
                        </th>
                        <td>
                            <form action="{{ route('account.transactions.txnReconcile') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="txn_id" value="{{ $transaction->id }}">
                                <input type="submit" class="btn btn-xs btn-warning" value="{{ trans('cruds.transaction.fields.txn_txnReconcile') }}">
                            </form>

                            @if(isset($refund_request_exists))
                                <div style="display:none;">{{$refund_request_exists}}</div>
                                <div class="badge badge-danger">Refund Requested.</div>
                            @elseif($transaction->txn_status == 'completed')
                                <form action="{{ route('account.transactions.refund') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="txn_id" value="{{ $transaction->id }}">
                                    <input type="hidden" name="gateway_id" value="{{ $transaction->txn_ec_gateway_id }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.refund') }}">
                                </form>
                                {{-- @if(in_array('Admin', Auth::user()->roles->pluck('title')->toArray())) --}}
                                <form action="{{ route('account.transactions.triggerManualIPN') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="txn_id" value="{{ $transaction->id }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="Trigger Return URL">
                                </form>
                                {{-- @endif --}}
                            @endif
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

@if(isset($deposit_slips) && count($deposit_slips) > 0)
<div class="card" style="margin-bottom:70px;">
    <div class="card-header">
        Deposit Slips
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Txn ID</th>
                    <th>Gateway</th>
                    <th>Slip</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Date</th>
                </tr>
                    @foreach ($deposit_slips as $deposit_slip)   
                    <tr>
                        <td>{{$deposit_slip->id}}</td>
                        <td>{{$deposit_slip->txn_id}}</td>
                        <td>{{$deposit_slip->gateway->ec_pay_gateway_name}}</td>
                        <td>
                            @if(isset($deposit_slip->slip_url))
                            @foreach ($deposit_slip->slip_url as $slip_url)
                                <a class="btn btn-xs btn-warning" href="{{$slip_url['filePath']}}" target="_blank">
                                    View Slip
                                </a>
                            @endforeach
                            @endif
                        </td>
                        <td>
                            @if($deposit_slip->approved)
                            Approved By: {{$deposit_slip->approvedby->name}}
                            @elseif($deposit_slip->rejected)
                            Rejected By: {{$deposit_slip->rejectedby->name}}
                            @endif
                        </td>
                        <td>{{$deposit_slip->reject_reason}}</td>
                        <td>{{$deposit_slip->updated_at}}</td>
                    </tr>
                    @endforeach
                </body>
            </table>
        </div>
    </div>
</div>
@endif

@if(isset($refund_requests) && count($refund_requests) > 0)
<div class="card" style="margin-bottom:70px;">
    <div class="card-header">
        Refund Requests
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped table-hover">
                <tbody>
                <tr>
                    <th>ID</th>
                    <th>Txn ID</th>
                    <th>Gateway</th>
                    <th>Status</th>
                    <th>Reason</th>
                    <th>Date</th>
                </tr>
                    @foreach ($refund_requests as $refund_request)   
                    <tr>
                        <td>{{$refund_request->id}}</td>
                        <td>{{$refund_request->txn_id}}</td>
                        <td>{{$refund_request->gateway->ec_pay_gateway_name}}</td>
                        <td>
                            @if($refund_request->approved)
                            Approved by: {{$refund_request->approvedby->name}}
                            @elseif($refund_request->rejected)
                            Rejected By: {{$refund_request->rejectedby->name}}
                            @endif
                        </td>
                        <td>
                            @if($refund_request->approved)
                            {{$refund_request->approved_reason}}
                            @elseif($refund_request->rejected)
                            {{$refund_request->reject_reason}}
                            @endif
                        </td>
                        <td>{{$refund_request->updated_at}}</td>
                    </tr>
                    @endforeach
                </body>
            </table>
        </div>
    </div>
</div>
@endif
@include('layouts.footer')