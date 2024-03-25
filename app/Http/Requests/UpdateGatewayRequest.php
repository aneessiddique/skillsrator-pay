<?php

namespace App\Http\Requests;

use App\Gateway;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateGatewayRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('Gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ec_pay_gateway_name' => [
                'required',
            ],
            'ec_pay_gateway_url' => [
                'required',
            ],
        ];
    }
}
