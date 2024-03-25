@include('layouts.head')
@include('partials.header')
@if(isset($error))
<em class="invalid-feedback">
    {{ $error }}
</em>
@endif
<div class="card">
    <div class="card-header">
        Transactions Refund Request List
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
                    @foreach($refund_requests as $key => $refund_request)
                        <tr data-entry-id="{{ $refund_request->id }}">
                            {{-- {{dd($refund_request)}} --}}
                            <td>
                                <p>
                                    <a class="btn btn-xs btn-primary" href="{{ route('account.transactions.show', $refund_request->txn_id) }}">
                                        View Transaction
                                    </a>
                                </p>
                                <p>
                                    <a class="approveModal btn btn-xs btn-primary" data-refundtxnid="{{ $refund_request->id }}" data-toggle="modal" data-target="#approveModal" href="#">
                                        Approve Refund
                                    </a>
                                </p>
                                <p>
                                    {{-- <a class="btn btn-xs btn-primary" href="{{ route('account.transactions.refund_request_transaction_reject', $refund_request->id) }}">
                                        Reject
                                    </a> --}}
                                    <!-- Button trigger modal -->
                                    <a class="rejectModal btn btn-xs btn-danger" data-refundtxnid="{{ $refund_request->id }}" data-toggle="modal" data-target="#rejectModal" href="#">
                                        Reject Refund
                                    </a>
                                </p>
                            </td>
                            <td>
                                {{ $refund_request->transaction->id ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_amount ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_customer_name ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_customer_email ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_customer_mobile ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_payment_type ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_customer_bill_order_id ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_reference ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_datetime ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_expiry_datetime ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_description ?? '' }}
                            </td>
                            <td>
                                {{ $refund_request->transaction->txn_status ?? '' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
</main>

  
  <!-- Modal -->
  <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="approveModalLabel">Approve Refund</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <form action="{{ route('account.transactions.refund_request_transaction_approve') }}" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="approve_request_id" name="refund_request_id" value="">
                    <textarea class="form-control" id="approved_reason" name="approved_reason" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Approve</button>
                </div>
            </div>
        </form>
    </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Reject Refund</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <form action="{{ route('account.transactions.refund_request_transaction_reject') }}" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="reject_request_id" name="refund_request_id" value="">
                    <textarea class="form-control" id="reject_reason" name="reject_reason" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Reject</button>
                </div>
            </div>
        </form>
    </div>
    </div>
  </div>
<script>

$(document).on("click", ".approveModal", function () {
     var refundtxnid = $(this).data('refundtxnid');
     $(".modal-body #approve_request_id").val( refundtxnid );
});

$(document).on("click", ".rejectModal", function () {
     var refundtxnid = $(this).data('refundtxnid');
     $(".modal-body #reject_request_id").val( refundtxnid );
});

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
