<!-- Ipn Gateway Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ipn_gateway', 'Ipn Gateway:') !!}
    {!! Form::text('ipn_gateway', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Ipn Response Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('ipn_response', 'Ipn Response:') !!}
    {!! Form::textarea('ipn_response', null, ['class' => 'form-control']) !!}
</div>