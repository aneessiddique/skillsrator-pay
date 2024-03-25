@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.transaction.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("account.transactions.update", [$transaction->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('txn_expiry_datetime') ? 'has-error' : '' }}">
                <label for="txn_expiry_datetime">{{ trans('cruds.transaction.fields.txn_expiry_datetime') }}*</label>
                <input type="text" id="txn_expiry_datetime" name="txn_expiry_datetime" class="form-control" value="{{ old('txn_expiry_datetime', isset($transaction) ? $transaction->txn_expiry_datetime : '') }}" required>
                @if($errors->has('txn_expiry_datetime'))
                    <em class="invalid-feedback">
                        {{ $errors->first('txn_expiry_datetime') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.transaction.fields.txn_expiry_datetime_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('txn_response_ref') ? 'has-error' : '' }}">
                <label for="txn_response_ref">{{ trans('cruds.transaction.fields.txn_response_ref') }}*</label>
                <input type="text" id="txn_response_ref" name="txn_response_ref" class="form-control" value="{{ old('txn_response_ref', isset($transaction) ? $transaction->txn_response_ref : '') }}" required>
                @if($errors->has('txn_response_ref'))
                    <em class="invalid-feedback">
                        {{ $errors->first('txn_response_ref') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.transaction.fields.txn_response_ref_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('txn_ec_gateway_id') ? 'has-error' : '' }}">
                <label for="txn_ec_gateway_id">{{ trans('cruds.transaction.fields.txn_ec_gateway_id') }}*</label>
                <input type="text" id="txn_ec_gateway_id" name="txn_ec_gateway_id" class="form-control" value="{{ old('txn_ec_gateway_id', isset($transaction) ? $transaction->txn_ec_gateway_id : '') }}" required>
                @if($errors->has('txn_ec_gateway_id'))
                    <em class="invalid-feedback">
                        {{ $errors->first('txn_ec_gateway_id') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.transaction.fields.txn_ec_gateway_id_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>

@include('layouts.footer')