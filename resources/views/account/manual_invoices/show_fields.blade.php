<!-- Id Field -->
<div class="col-sm-6">
    {!! Form::label('id', 'Id:') !!}
    <p>{{ $manualInvoices->id }}</p>
</div>

<!-- Amount Field -->
<div class="col-sm-6">
    {!! Form::label('txn_amount', 'Amount:') !!}
    <p>{{ $manualInvoices->txn_amount }}</p>
</div>

<!-- Currency Field -->
<div class="col-sm-6">
    {!! Form::label('txn_currency', 'Currency:') !!}
    <p>{{ $manualInvoices->txn_currency }}</p>
</div>

<!-- Customer Name Field -->
<div class="col-sm-6">
    {!! Form::label('txn_customer_name', 'Customer Name:') !!}
    <p>{{ $manualInvoices->txn_customer_name }}</p>
</div>

<!-- Customer Email Field -->
<div class="col-sm-6">
    {!! Form::label('txn_customer_email', 'Customer Email:') !!}
    <p>{{ $manualInvoices->txn_customer_email }}</p>
</div>

<!-- Customer Mobile Field -->
<div class="col-sm-6">
    {!! Form::label('txn_customer_mobile', 'Customer Mobile:') !!}
    <p>{{ $manualInvoices->txn_customer_mobile }}</p>
</div>

<!-- Payment Type Field -->
<div class="col-sm-6">
    {!! Form::label('txn_payment_type', 'Payment Title:') !!}
    <p>{{ $manualInvoices->txn_payment_type }}</p>
</div>

<!-- Customer Bill Order Id Field -->
<div class="col-sm-6">
    {!! Form::label('txn_customer_bill_order_id', 'Customer Bill Order Id:') !!}
    <p>{{ $manualInvoices->txn_customer_bill_order_id }}</p>
</div>

<!-- Gateway Options Field -->
<div class="col-sm-6">
    {!! Form::label('txn_gateway_options', 'Gateway Options:') !!}
    <p>{{ $manualInvoices->txn_gateway_options }}</p>
</div>

<!-- Description Field -->
<div class="col-sm-6">
    {!! Form::label('txn_description', 'Description:') !!}
    <p>{{ $manualInvoices->txn_description }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-6">
    {!! Form::label('txn_status', 'Status:') !!}
    <p>{{ $manualInvoices->txn_status }}</p>
</div>

<!-- Platform Return Url Field -->
<div class="col-sm-6">
    {!! Form::label('txn_platform_return_url', 'Platform Return Url:') !!}
    <p>{{ $manualInvoices->txn_platform_return_url }}</p>
</div>

<!-- Txn Expiry Datetime Field -->
<div class="col-sm-6">
    {!! Form::label('txn_expiry_datetime', 'Expiry Datetime:') !!}
    <p>{{ $manualInvoices->txn_expiry_datetime }}</p>
</div>
