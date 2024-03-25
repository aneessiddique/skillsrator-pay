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
                            ID
                        </th>
                        <td>
                            {{ $deposit_slip->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Txn ID
                        </th>
                        <td>
                            {{ $deposit_slip->txn_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Gateway
                        </th>
                        <td>
                            {{ $deposit_slip->gateway->ec_pay_gateway_name }}
                        </td>
                    </tr>
                    @foreach ($deposit_slip->deposit_slip_fields_data as $data)
                        <tr>
                            <th>
                                {{ $data->deposit_slip_fields->field_name }}
                            </th>
                            <td>
                                {{ $data->field_value }}
                            </td>
                    </tr>
                    @endforeach
                       <tr>
                        <td colspan="2">
                            @if(isset($deposit_slip->slip_url))
                            @foreach ($deposit_slip->slip_url as $slip_url)
                                <a class="btn btn-xs btn-warning" href="{{$slip_url['filePath']}}" target="_blank">
                                    View Slip
                                </a>
                            @endforeach
                            @endif
                            
                            <a class="btn btn-xs btn-success" href="{{ route('account.transactions.deposit_slip_transaction_approve', $deposit_slip->id) }}">
                                Approve
                            </a>
                            {{-- <a class="btn btn-xs btn-primary" href="{{ route('account.transactions.deposit_slip_transaction_reject', $deposit_slip->id) }}">
                                Reject
                            </a> --}}
                            <!-- Button trigger modal -->
                            <a class="rejectModal btn btn-xs btn-danger" data-slipid="{{ $deposit_slip->id }}" data-toggle="modal" data-target="#rejectModal" href="#">
                                Reject
                            </a>
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

  
<!-- Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="rejectModalLabel">Reject Deposit Slip</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span> 
          </button>
        </div>
        <form action="{{ route('account.transactions.deposit_slip_transaction_reject') }}" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" id="deposit_slip_id" name="deposit_slip_id" value="">
                    <textarea class="form-control" id="reject_reason" name="reject_reason" style="width:100%;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>
<!-- Modal -->

<script>    
    $(document).on("click", ".rejectModal", function () {
        var slipid = $(this).data('slipid');
        $(".modal-body #deposit_slip_id").val( slipid );
    });
</script>

@include('layouts.footer')