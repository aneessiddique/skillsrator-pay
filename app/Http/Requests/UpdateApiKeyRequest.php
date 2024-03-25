<?php

namespace App\Http\Requests;

use App\ApiKey;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateApiKeyRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('ApiKey_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ec_pay_app_name' => [
                'required',
            ],
        ];
    }
}
