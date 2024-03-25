<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\TransactionDepositSlipFieldsDataTable;
use App\Http\Requests\Account;
use App\Http\Requests\Account\CreateTransactionDepositSlipFieldsRequest;
use App\Http\Requests\Account\UpdateTransactionDepositSlipFieldsRequest;
use App\Models\Account\TransactionDepositSlipFields;
use Flash;
use App\Http\Controllers\Controller;
use Response;

class TransactionDepositSlipFieldsController extends Controller
{
    /**
     * Display a listing of the TransactionDepositSlipFields.
     *
     * @param TransactionDepositSlipFieldsDataTable $transactionDepositSlipFieldsDataTable
     * @return Response
     */
    public function index(TransactionDepositSlipFieldsDataTable $transactionDepositSlipFieldsDataTable)
    {
        return $transactionDepositSlipFieldsDataTable->render('account.transaction_deposit_slip_fields.index');
    }

    /**
     * Show the form for creating a new TransactionDepositSlipFields.
     *
     * @return Response
     */
    public function create()
    {
        return view('account.transaction_deposit_slip_fields.create');
    }

    /**
     * Store a newly created TransactionDepositSlipFields in storage.
     *
     * @param CreateTransactionDepositSlipFieldsRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionDepositSlipFieldsRequest $request)
    {
        $input = $request->all();

        /** @var TransactionDepositSlipFields $transactionDepositSlipFields */
        $transactionDepositSlipFields = TransactionDepositSlipFields::create($input);

        Flash::success('Transaction Deposit Slip Fields saved successfully.');

        return redirect(route('account.depositSlipFields.index'));
    }

    /**
     * Display the specified TransactionDepositSlipFields.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TransactionDepositSlipFields $transactionDepositSlipFields */
        $transactionDepositSlipFields = TransactionDepositSlipFields::find($id);

        if (empty($transactionDepositSlipFields)) {
            Flash::error('Transaction Deposit Slip Fields not found');

            return redirect(route('account.depositSlipFields.index'));
        }

        return view('account.transaction_deposit_slip_fields.show')->with('transactionDepositSlipFields', $transactionDepositSlipFields);
    }

    /**
     * Show the form for editing the specified TransactionDepositSlipFields.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var TransactionDepositSlipFields $transactionDepositSlipFields */
        $transactionDepositSlipFields = TransactionDepositSlipFields::find($id);

        if (empty($transactionDepositSlipFields)) {
            Flash::error('Transaction Deposit Slip Fields not found');

            return redirect(route('account.depositSlipFields.index'));
        }

        return view('account.transaction_deposit_slip_fields.edit')->with('transactionDepositSlipFields', $transactionDepositSlipFields);
    }

    /**
     * Update the specified TransactionDepositSlipFields in storage.
     *
     * @param int $id
     * @param UpdateTransactionDepositSlipFieldsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionDepositSlipFieldsRequest $request)
    {
        /** @var TransactionDepositSlipFields $transactionDepositSlipFields */
        $transactionDepositSlipFields = TransactionDepositSlipFields::find($id);

        if (empty($transactionDepositSlipFields)) {
            Flash::error('Transaction Deposit Slip Fields not found');

            return redirect(route('account.depositSlipFields.index'));
        }

        $transactionDepositSlipFields->fill($request->all());
        $transactionDepositSlipFields->save();

        Flash::success('Transaction Deposit Slip Fields updated successfully.');

        return redirect(route('account.depositSlipFields.index'));
    }

    /**
     * Remove the specified TransactionDepositSlipFields from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TransactionDepositSlipFields $transactionDepositSlipFields */
        $transactionDepositSlipFields = TransactionDepositSlipFields::find($id);

        if (empty($transactionDepositSlipFields)) {
            Flash::error('Transaction Deposit Slip Fields not found');

            return redirect(route('account.depositSlipFields.index'));
        }

        $transactionDepositSlipFields->delete();

        Flash::success('Transaction Deposit Slip Fields deleted successfully.');

        return redirect(route('account.depositSlipFields.index'));
    }
}
