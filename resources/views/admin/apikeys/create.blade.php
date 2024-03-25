@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.apikey.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.apikeys.store") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group {{ $errors->has('ec_pay_app_name') ? 'has-error' : '' }}">
                <label for="ec_pay_app_name">{{ trans('cruds.apikey.fields.ec_pay_app_name') }}*</label>
                <input type="text" id="ec_pay_app_name" name="ec_pay_app_name" class="form-control" value="{{ old('ec_pay_app_name', isset($apikey) ? $apikey->ec_pay_app_name : '') }}" required>
                @if($errors->has('ec_pay_app_name'))
                    <em class="invalid-feedback">
                        {{ $errors->first('ec_pay_app_name') }}
                    </em>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.apikey.fields.ec_pay_app_name_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form>


    </div>
</div>
@include('layouts.footer')