<!-- Ipn Gateway Field -->
<div class="col-sm-12">
    {!! Form::label('id', 'ID:') !!}
    <p>{{ $iPNLogs->id }}</p>
</div>

<!-- Ipn Gateway Field -->
<div class="col-sm-12">
    {!! Form::label('ipn_gateway', 'Ipn Gateway:') !!}
    <p>{{ $iPNLogs->ipn_gateway }}</p>
</div>

<!-- Ipn Response Field -->
<div class="col-sm-12">
    {!! Form::label('ipn_response', 'Ipn Response:') !!}
    <p>{{ $iPNLogs->ipn_response }}</p>
</div>

<!-- Ipn Created Field -->
<div class="col-sm-12">
    {!! Form::label('created_at', 'Created:') !!}
    <p>{{ $iPNLogs->created_at }}</p>
</div>

