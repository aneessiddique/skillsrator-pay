<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApiKeyRequest;
use App\Http\Requests\StoreApiKeyRequest;
use App\Http\Requests\UpdateApiKeyRequest;
use App\ApiKey;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeysController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('ApiKey_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $apikeys = ApiKey::all();

        return view('admin.apikeys.index', compact('apikeys'));
    }

    public function create()
    {
        // abort_if(Gate::denies('ApiKey_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.apikeys.create');
    }

    public function store(StoreApiKeyRequest $request)
    {
        $data = $request->all();
        $data['ec_pay_api_key'] = bin2hex(openssl_random_pseudo_bytes(32));
        
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $data['ec_pay_api_iv'] = bin2hex(openssl_random_pseudo_bytes($ivlen));
        $data['ec_pay_api_token'] = bin2hex(openssl_random_pseudo_bytes(32));
        
        ApiKey::create($data);

        return redirect()->route('admin.apikeys.index');
    }

    public function edit(ApiKey $apikey)
    {
        // abort_if(Gate::denies('ApiKey_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.apikeys.edit', compact('apikey'));
    }

    public function update(UpdateApiKeyRequest $request, ApiKey $apikey)
    {
        $apikey->update($request->all());

        return redirect()->route('admin.apikeys.index');
    }

    public function show(ApiKey $apikey)
    {
        // abort_if(Gate::denies('ApiKey_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.apikeys.show', compact('apikey'));
    }

    public function destroy(ApiKey $apikey)
    {
        // abort_if(Gate::denies('ApiKey_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $apikey->delete();

        return back();
    }

    public function massDestroy(MassDestroyApiKeyRequest $request)
    {
        ApiKey::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
