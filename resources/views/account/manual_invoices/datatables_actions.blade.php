{{-- {!! Form::open(['route' => ['account.manual_invoices.destroy', $id], 'method' => 'delete']) !!} --}}
<div class='btn-group'>
    <a href="{{ route('account.manual_invoices.show', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-eye"></i>
    </a>
    @if($txn_status != 'completed')
    <a href="{{ route('account.manual_invoices.edit', $id) }}" class='btn btn-default btn-xs'>
        <i class="fa fa-edit"></i>
    </a>
    @endif
    <a href="{{ env('APP_URL') . '/invoice/payment/' . $txn_customer_bill_order_id }}" target="_blank" class='btn btn-default btn-xs'>
        Pay
    </a>
    {{-- {!! Form::button('<i class="fa fa-trash"></i>', [
        'type' => 'submit',
        'class' => 'btn btn-danger btn-xs',
        'onclick' => "return confirm('Are you sure?')"
    ]) !!} --}}
</div>
{{-- {!! Form::close() !!} --}}
