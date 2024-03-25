<!-- Inv Amount Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_amount', 'Amount:') !!}
    {!! Form::number('txn_amount', null, ['class' => 'form-control', 'step' => '0.01']) !!}
</div>

<!-- Inv Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_currency', 'Currency:') !!}
    {{-- {!! Form::text('txn_currency', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!} --}}
    {!! Form::select('txn_currency', ['USD' => 'USD', 'PKR' => 'PKR', 'GBP' => 'GBP', 'AUD' => 'AUD', 'CAD' => 'CAD'], null, ['class' => 'form-control']) !!}

</div>

<!-- Inv Currency Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_currency_rate', 'Currency Rate:') !!}
    {!! Form::select('txn_currency_rate', $currency_rates, null, ['class' => 'form-control', 'required']) !!}

</div>

<!-- Inv Customer Id Field -->
{{-- <div class="form-group col-sm-6"> --}}
    {{-- {!! Form::label('txn_customer_id', 'Customer Id:') !!} --}}
    {!! Form::hidden('txn_customer_id', 0, ['class' => 'form-control']) !!}
{{-- </div> --}}

<!-- Inv Customer Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_name', 'Customer Name:') !!}
    {!! Form::text('txn_customer_name', null, ['class' => 'form-control']) !!}
</div>

<!-- Inv Customer Email Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_email', 'Customer Email:') !!}
    {!! Form::text('txn_customer_email', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Inv Customer Mobile Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_customer_mobile', 'Customer Mobile:') !!}
    {!! Form::text('txn_customer_mobile', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!}
</div>

<!-- Inv Payment Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_payment_type', 'Payment Title:') !!}
    {!! Form::text('txn_payment_type', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div>

<!-- Inv Customer Bill Order Id Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('txn_customer_bill_order_id', 'Customer Bill Order Id:') !!}
    {!! Form::text('txn_customer_bill_order_id', null, ['class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
</div> --}}
{{-- {{dd(json_decode($manualInvoices->txn_gateway_options))}} --}}
<!-- Inv Gateway Options Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_gateway_options', 'Gateway Options:') !!}
    {{-- {!! Form::text('txn_gateway_options', null, ['class' => 'form-control','maxlength' => 255,'maxlength' => 255]) !!} --}}
    {!! Form::select('txn_gateway_options[]', $gateways, isset($manualInvoices) ? json_decode($manualInvoices->txn_gateway_options) : [], ['multiple' => 'multiple', 'class' => 'form-control']) !!}
</div>

<!-- Inv Platform Return Url Field -->
{{-- <div class="form-group col-sm-6">
    {!! Form::label('txn_platform_return_url', 'Platform Return Url:') !!}
    {!! Form::text('txn_platform_return_url', null, ['class' => 'form-control']) !!}
</div> --}}

<!-- Expiry Datetime Field -->
<div class="form-group col-sm-6">
    {!! Form::label('txn_expiry_datetime', 'Expiry Datetime:') !!}
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

<!-- Inv Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('txn_description', 'Description:') !!}
    {!! Form::textarea('txn_description', null, ['class' => 'form-control']) !!}
</div>
