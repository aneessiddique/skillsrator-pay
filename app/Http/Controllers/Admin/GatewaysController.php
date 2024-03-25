<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyGatewayRequest;
use App\Http\Requests\StoreGatewayRequest;
use App\Http\Requests\UpdateGatewayRequest;
use App\Gateway;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GatewaysController extends Controller
{
    public function index()
    {
        // abort_if(Gate::denies('Gateway_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateways = Gateway::all();

        return view('admin.gateways.index', compact('gateways'));
    }

    public function create()
    {
        // abort_if(Gate::denies('Gateway_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.gateways.create');
    }

    public function store(StoreGatewayRequest $request)
    {
        $data = $request->all();
        $data['ec_pay_api_key'] = bin2hex(openssl_random_pseudo_bytes(32));

        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $data['ec_pay_api_iv'] = bin2hex(openssl_random_pseudo_bytes($ivlen));
        $data['ec_pay_gateway_image'] = 'assets/img/' . preg_replace('/\s+/', '_', strtolower($request->ec_pay_gateway_name)) . '.png';
        
        Gateway::create($data);

        return redirect()->route('admin.gateways.index');
    }

    public function edit(Gateway $gateway)
    {
        // abort_if(Gate::denies('Gateway_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.gateways.edit', compact('gateway'));
    }

    public function update(UpdateGatewayRequest $request, Gateway $gateway)
    {
        $gateway->update($request->all());

        return redirect()->route('admin.gateways.index');
    }

    public function show(Gateway $gateway)
    {
        // abort_if(Gate::denies('Gateway_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.gateways.show', compact('gateway'));
    }

    public function destroy(Gateway $gateway)
    {
        // abort_if(Gate::denies('Gateway_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $gateway->delete();

        return back();
    }

    public function massDestroy(MassDestroyGatewayRequest $request)
    {
        Gateway::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
