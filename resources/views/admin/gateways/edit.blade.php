@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.gateway.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.gateways.update", [$gateway->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('ec_pay_gateway_sort') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_sort">Sort*</label>
                <input type="text" id="ec_pay_gateway_sort" name="ec_pay_gateway_sort" class="form-control" value="{{ old('ec_pay_gateway_sort', isset($gateway) ? $gateway->ec_pay_gateway_sort : '') }}" required>
                @if($errors->has('ec_pay_gateway_sort'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_sort') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_sort_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_name') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_name">{{ trans('cruds.gateway.fields.ec_pay_gateway_name') }}*</label>
                <input type="text" id="ec_pay_gateway_name" name="ec_pay_gateway_name" class="form-control" value="{{ old('ec_pay_gateway_name', isset($gateway) ? $gateway->ec_pay_gateway_name : '') }}" required>
                @if($errors->has('ec_pay_gateway_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_url') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_url">{{ trans('cruds.gateway.fields.ec_pay_gateway_url') }}*</label>
                <input type="text" id="ec_pay_gateway_url" name="ec_pay_gateway_url" class="form-control" value="{{ old('ec_pay_gateway_url', isset($gateway) ? $gateway->ec_pay_gateway_url : '') }}" required>
                @if($errors->has('ec_pay_gateway_url'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_url') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_url_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_currency') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_currency">{{ trans('cruds.gateway.fields.ec_pay_gateway_currency') }}*</label>
                <input type="text" id="ec_pay_gateway_currency" name="ec_pay_gateway_currency" class="form-control" value="{{ old('ec_pay_gateway_currency', isset($gateway) ? $gateway->ec_pay_gateway_currency : '') }}" required>
                @if($errors->has('ec_pay_gateway_currency'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_currency') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_currency_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_fee_percent') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_fee_percent">Gateway Fee Percentage</label>
                <input type="text" id="ec_pay_gateway_fee_percent" name="ec_pay_gateway_fee_percent" class="form-control" value="{{ old('ec_pay_gateway_fee_percent', isset($gateway) ? $gateway->ec_pay_gateway_fee_percent : '') }}" required>
                @if($errors->has('ec_pay_gateway_fee_percent'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_fee_percent') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_fee_percent_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_image') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_image">{{ trans('cruds.gateway.fields.ec_pay_gateway_image') }}*</label>
                <input type="text" id="ec_pay_gateway_image" name="ec_pay_gateway_image" class="form-control" value="{{ old('ec_pay_gateway_image', isset($gateway) ? $gateway->ec_pay_gateway_image : '') }}" required>
                @if($errors->has('ec_pay_gateway_image'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_image') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_image_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_enabled') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_enabled">{{ trans('cruds.gateway.fields.ec_pay_gateway_enabled') }}*</label>
                <input type="text" id="ec_pay_gateway_enabled" name="ec_pay_gateway_enabled" class="form-control" value="{{ old('ec_pay_gateway_enabled', isset($gateway) ? $gateway->ec_pay_gateway_enabled : '') }}" required>
                @if($errors->has('ec_pay_gateway_enabled'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_enabled') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_enabled_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('ec_pay_gateway_description') ? 'has-error' : '' }}">
                <label for="ec_pay_gateway_description">{{ trans('cruds.gateway.fields.ec_pay_gateway_description') }}*</label>
                <textarea id="ec_pay_gateway_description" name="ec_pay_gateway_description" class="form-control" required>{{ 
                    old('ec_pay_gateway_description', isset($gateway) ? $gateway->ec_pay_gateway_description : '') 
                }}</textarea>
                @if($errors->has('ec_pay_gateway_description'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_gateway_description') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.gateway.fields.ec_pay_gateway_enabled_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@include('layouts.footer')