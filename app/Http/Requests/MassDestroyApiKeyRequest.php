<?php

namespace App\Http\Requests;

use App\ApiKey;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyApiKeyRequest extends FormRequest
{
    public function authorize()
    {
        // abort_if(Gate::denies('ApiKey_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:ApiKeys,id',
        ];
    }
}
