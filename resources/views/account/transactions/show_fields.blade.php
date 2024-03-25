<!-- Txn Amount Field -->
<div class="col-sm-12">
    {!! Form::label('txn_amount', 'Txn Amount:') !!}
    <p>{{ $transactions->txn_amount }}</p>
</div>

<!-- Txn Gateway Fee Field -->
<div class="col-sm-12">
    {!! Form::label('txn_gateway_fee', 'Txn Gateway Fee:') !!}
    <p>{{ $transactions->txn_gateway_fee }}</p>
</div>

<!-- Txn Currency Field -->
<div class="col-sm-12">
    {!! Form::label('txn_currency', 'Txn Currency:') !!}
    <p>{{ $transactions->txn_currency }}</p>
</div>

<!-- Txn Customer Id Field -->
<div class="col-sm-12">
    {!! Form::label('txn_customer_id', 'Txn Customer Id:') !!}
    <p>{{ $transactions->txn_customer_id }}</p>
</div>

<!-- Txn Customer Name Field -->
<div class="col-sm-12">
    {!! Form::label('txn_customer_name', 'Txn Customer Name:') !!}
    <p>{{ $transactions->txn_customer_name }}</p>
</div>

<!-- Txn Customer Email Field -->
<div class="col-sm-12">
    {!! Form::label('txn_customer_email', 'Txn Customer Email:') !!}
    <p>{{ $transactions->txn_customer_email }}</p>
</div>

<!-- Txn Customer Mobile Field -->
<div class="col-sm-12">
    {!! Form::label('txn_customer_mobile', 'Txn Customer Mobile:') !!}
    <p>{{ $transactions->txn_customer_mobile }}</p>
</div>

<!-- Txn Payment Type Field -->
<div class="col-sm-12">
    {!! Form::label('txn_payment_type', 'Txn Payment Type:') !!}
    <p>{{ $transactions->txn_payment_type }}</p>
</div>

<!-- Txn Customer Bill Order Id Field -->
<div class="col-sm-12">
    {!! Form::label('txn_customer_bill_order_id', 'Txn Customer Bill Order Id:') !!}
    <p>{{ $transactions->txn_customer_bill_order_id }}</p>
</div>

<!-- Txn Reference Field -->
<div class="col-sm-12">
    {!! Form::label('txn_reference', 'Txn Reference:') !!}
    <p>{{ $transactions->txn_reference }}</p>
</div>

<!-- Txn Gateway Options Field -->
<div class="col-sm-12">
    {!! Form::label('txn_gateway_options', 'Txn Gateway Options:') !!}
    <p>{{ $transactions->txn_gateway_options }}</p>
</div>

<!-- Txn Ec Platform Id Field -->
<div class="col-sm-12">
    {!! Form::label('txn_ec_platform_id', 'Txn Ec Platform Id:') !!}
    <p>{{ $transactions->txn_ec_platform_id }}</p>
</div>

<!-- Txn Ec Gateway Id Field -->
<div class="col-sm-12">
    {!! Form::label('txn_ec_gateway_id', 'Txn Ec Gateway Id:') !!}
    <p>{{ $transactions->txn_ec_gateway_id }}</p>
</div>

<!-- Txn Datetime Field -->
<div class="col-sm-12">
    {!! Form::label('txn_datetime', 'Txn Datetime:') !!}
    <p>{{ $transactions->txn_datetime }}</p>
</div>

<!-- Txn Expiry Datetime Field -->
<div class="col-sm-12">
    {!! Form::label('txn_expiry_datetime', 'Txn Expiry Datetime:') !!}
    <p>{{ $transactions->txn_expiry_datetime }}</p>
</div>

<!-- Txn Description Field -->
<div class="col-sm-12">
    {!! Form::label('txn_description', 'Txn Description:') !!}
    <p>{{ $transactions->txn_description }}</p>
</div>

<!-- Txn Status Field -->
<div class="col-sm-12">
    {!! Form::label('txn_status', 'Txn Status:') !!}
    <p>{{ $transactions->txn_status }}</p>
</div>

<!-- Txn Request Field -->
<div class="col-sm-12">
    {!! Form::label('txn_request', 'Txn Request:') !!}
    <p>{{ $transactions->txn_request }}</p>
</div>

<!-- Txn Response Field -->
<div class="col-sm-12">
    {!! Form::label('txn_response', 'Txn Response:') !!}
    <p>{{ $transactions->txn_response }}</p>
</div>

<!-- Txn Response Code Field -->
<div class="col-sm-12">
    {!! Form::label('txn_response_code', 'Txn Response Code:') !!}
    <p>{{ $transactions->txn_response_code }}</p>
</div>

<!-- Txn Response Ref Field -->
<div class="col-sm-12">
    {!! Form::label('txn_response_ref', 'Txn Response Ref:') !!}
    <p>{{ $transactions->txn_response_ref }}</p>
</div>

<!-- Txn Platform Return Url Field -->
<div class="col-sm-12">
    {!! Form::label('txn_platform_return_url', 'Txn Platform Return Url:') !!}
    <p>{{ $transactions->txn_platform_return_url }}</p>
</div>

<!-- Customer Ip Field -->
<div class="col-sm-12">
    {!! Form::label('customer_ip', 'Customer Ip:') !!}
    <p>{{ $transactions->customer_ip }}</p>
</div>

<!-- txn_request Field -->
<div class="col-sm-12" style="display:none;overflow:scroll">
    {!! Form::label('txn_request', 'Transaction Request:') !!}
    <p>{{ $transactions->txn_request }}</p>
</div>

