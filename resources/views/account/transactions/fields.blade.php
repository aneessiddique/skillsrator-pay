<!-- Txn Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_amount', 'Txn Amount:') !!}
    {!! Form::number('txn_amount', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Gateway Fee Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_gateway_fee', 'Txn Gateway Fee:') !!}
    {!! Form::number('txn_gateway_fee', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_currency', 'Txn Currency:') !!}
    {!! Form::text('txn_currency', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Txn Customer Id Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_customer_id', 'Txn Customer Id:') !!}
    {!! Form::textarea('txn_customer_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Customer Name Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_customer_name', 'Txn Customer Name:') !!}
    {!! Form::textarea('txn_customer_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Customer Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_email', 'Txn Customer Email:') !!}
    {!! Form::text('txn_customer_email', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Txn Customer Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_mobile', 'Txn Customer Mobile:') !!}
    {!! Form::text('txn_customer_mobile', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Txn Payment Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_payment_type', 'Txn Payment Type:') !!}
    {!! Form::text('txn_payment_type', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Txn Customer Bill Order Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_bill_order_id', 'Txn Customer Bill Order Id:') !!}
    {!! Form::text('txn_customer_bill_order_id', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Txn Reference Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_reference', 'Txn Reference:') !!}
    {!! Form::text('txn_reference', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Txn Gateway Options Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_gateway_options', 'Txn Gateway Options:') !!}
    {!! Form::text('txn_gateway_options', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Txn Ec Platform Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_ec_platform_id', 'Txn Ec Platform Id:') !!}
    {!! Form::number('txn_ec_platform_id', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Ec Gateway Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_ec_gateway_id', 'Txn Ec Gateway Id:') !!}
    {!! Form::text('txn_ec_gateway_id', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Txn Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_datetime', 'Txn Datetime:') !!}
    {!! Form::text('txn_datetime', null, ['class' => 'form-control','id'=>'txn_datetime']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#txn_datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Txn Expiry Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_expiry_datetime', 'Txn Expiry Datetime:') !!}
    {!! Form::text('txn_expiry_datetime', null, ['class' => 'form-control','id'=>'txn_expiry_datetime']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#txn_expiry_datetime').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            useCurrent: true,
            sideBySide: true
        })
    </script>
@endpush

<!-- Txn Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_description', 'Txn Description:') !!}
    {!! Form::textarea('txn_description', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_status', 'Txn Status:') !!}
    {!! Form::text('txn_status', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Request Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_request', 'Txn Request:') !!}
    {!! Form::textarea('txn_request', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Response Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_response', 'Txn Response:') !!}
    {!! Form::textarea('txn_response', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Response Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_response_code', 'Txn Response Code:') !!}
    {!! Form::number('txn_response_code', null, ['class' => 'form-control']) !!}
</div>

<!-- Txn Response Ref Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_response_ref', 'Txn Response Ref:') !!}
    {!! Form::text('txn_response_ref', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Txn Platform Return Url Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_platform_return_url', 'Txn Platform Return Url:') !!}
    {!! Form::textarea('txn_platform_return_url', null, ['class' => 'form-control']) !!}
</div>

<!-- Customer Ip Field -->
<div class="form-group col-sm-6">
    {!! Form::label('customer_ip', 'Customer Ip:') !!}
    {!! Form::text('customer_ip', null, ['class' => 'form-control','maxlength' => 45,'maxlength' => 45]) !!}
</div>