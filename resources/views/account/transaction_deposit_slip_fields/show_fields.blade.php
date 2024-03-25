<!-- Id Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $transactionDepositSlipFields->id }}</p>
</div>

<!-- Gateway Id Field -->
<div class="col-sm-12">
    {!! Form::label('gateway_id', 'Gateway Id:') !!}
    <p>{{ $transactionDepositSlipFields->gateway_id }}</p>
</div>

<!-- Field Name Field -->
<div class="col-sm-12">
    {!! Form::label('field_name', 'Field Name:') !!}
    <p>{{ $transactionDepositSlipFields->field_name }}</p>
</div>

<!-- Field Type Field -->
<div class="col-sm-12">
    {!! Form::label('field_type', 'Field Type:') !!}
    <p>{{ $transactionDepositSlipFields->field_type }}</p>
</div>

