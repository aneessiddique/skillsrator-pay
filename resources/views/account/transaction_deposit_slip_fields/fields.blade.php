<!-- Gateway Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('gateway_id', 'Gateway Id:') !!}
    {!! Form::number('gateway_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Field Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_name', 'Field Name:') !!}
    {!! Form::text('field_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Field Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('field_type', 'Field Type:') !!}
    {!! Form::text('field_type', null, ['class' => 'form-control']) !!}
</div>