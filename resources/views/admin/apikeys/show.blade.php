@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.apikey.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.apikey.fields.id') }}
                        </th>
                        <td>
                            {{ $apikey->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_app_name') }}
                        </th>
                        <td>
                            {{ $apikey->ec_pay_app_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_key') }}
                        </th>
                        <td>
                            {{ $apikey->ec_pay_api_key }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_iv') }}
                        </th>
                        <td>
                            {{ $apikey->ec_pay_api_iv }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.apikey.fields.ec_pay_api_token') }}
                        </th>
                        <td>
                            {{ $apikey->ec_pay_api_token }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@include('layouts.footer')