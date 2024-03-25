<!-- Deposit Slip Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deposit_slip_id', 'Deposit Slip Id:') !!}
    {!! Form::number('deposit_slip_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Field Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_id', 'Field Id:') !!}
    {!! Form::number('field_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Field Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_value', 'Field Value:') !!}
    {!! Form::text('field_value', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_id', 'Txn Id:') !!}
    {!! Form::number('txn_id', null, ['class' => 'form-control']) !!}
</div>