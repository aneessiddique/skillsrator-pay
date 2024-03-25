@include('layouts.head')
@include('partials.header')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.gateway.title') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.id') }}
                        </th>
                        <td>
                            {{ $gateway->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Sort
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_sort }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_name') }}
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_url') }}
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_url }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_currency') }}
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_currency }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Gateway Fee Percentage
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_fee_percent }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_image') }}
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_image }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.gateway.fields.ec_pay_gateway_enabled') }}
                        </th>
                        <td>
                            {{ $gateway->ec_pay_gateway_enabled }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            Description
                        </th>
                        <td>
                            <pre>{{ $gateway->ec_pay_gateway_description }}</pre>
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