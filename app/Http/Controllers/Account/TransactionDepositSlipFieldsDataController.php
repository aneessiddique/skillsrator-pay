<?php

namespace App\Http\Controllers\Account;

use App\DataTables\Account\TransactionDepositSlipFieldsDataDataTable;
use App\Http\Requests\Account;
use App\Http\Requests\Account\CreateTransactionDepositSlipFieldsDataRequest;
use App\Http\Requests\Account\UpdateTransactionDepositSlipFieldsDataRequest;
use App\Models\Account\TransactionDepositSlipFieldsData;
use Flash;
use App\Http\Controllers\Controller;
use Response;

class TransactionDepositSlipFieldsDataController extends Controller
{
    /**
     * Display a listing of the TransactionDepositSlipFieldsData.
     *
     * @param TransactionDepositSlipFieldsDataDataTable $transactionDepositSlipFieldsDataDataTable
     * @return Response
     */
    public function index(TransactionDepositSlipFieldsDataDataTable $transactionDepositSlipFieldsDataDataTable)
    {
        return $transactionDepositSlipFieldsDataDataTable->render('account.transaction_deposit_slip_fields_datas.index');
    }

    /**
     * Show the form for creating a new TransactionDepositSlipFieldsData.
     *
     * @return Response
     */
    public function create()
    {
        return view('account.transaction_deposit_slip_fields_datas.create');
    }

    /**
     * Store a newly created TransactionDepositSlipFieldsData in storage.
     *
     * @param CreateTransactionDepositSlipFieldsDataRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionDepositSlipFieldsDataRequest $request)
    {
        $input = $request->all();

        /** @var TransactionDepositSlipFieldsData $transactionDepositSlipFieldsData */
        $transactionDepositSlipFieldsData = TransactionDepositSlipFieldsData::create($input);

        Flash::success('Transaction Deposit Slip Fields Data saved successfully.');

        return redirect(route('account.depositSlipFieldsData.index'));
    }

    /**
     * Display the specified TransactionDepositSlipFieldsData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TransactionDepositSlipFieldsData $transactionDepositSlipFieldsData */
        $transactionDepositSlipFieldsData = TransactionDepositSlipFieldsData::find($id);

        if (empty($transactionDepositSlipFieldsData)) {
            Flash::error('Transaction Deposit Slip Fields Data not found');

            return redirect(route('account.depositSlipFieldsData.index'));
        }

        return view('account.transaction_deposit_slip_fields_datas.show')->with('transactionDepositSlipFieldsData', $transactionDepositSlipFieldsData);
    }

    /**
     * Show the form for editing the specified TransactionDepositSlipFieldsData.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        /** @var TransactionDepositSlipFieldsData $transactionDepositSlipFieldsData */
        $transactionDepositSlipFieldsData = TransactionDepositSlipFieldsData::find($id);

        if (empty($transactionDepositSlipFieldsData)) {
            Flash::error('Transaction Deposit Slip Fields Data not found');

            return redirect(route('account.depositSlipFieldsData.index'));
        }

        return view('account.transaction_deposit_slip_fields_datas.edit')->with('transactionDepositSlipFieldsData', $transactionDepositSlipFieldsData);
    }

    /**
     * Update the specified TransactionDepositSlipFieldsData in storage.
     *
     * @param int $id
     * @param UpdateTransactionDepositSlipFieldsDataRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionDepositSlipFieldsDataRequest $request)
    {
        /** @var TransactionDepositSlipFieldsData $transactionDepositSlipFieldsData */
        $transactionDepositSlipFieldsData = TransactionDepositSlipFieldsData::find($id);

        if (empty($transactionDepositSlipFieldsData)) {
            Flash::error('Transaction Deposit Slip Fields Data not found');

            return redirect(route('account.depositSlipFieldsData.index'));
        }

        $transactionDepositSlipFieldsData->fill($request->all());
        $transactionDepositSlipFieldsData->save();

        Flash::success('Transaction Deposit Slip Fields Data updated successfully.');

        return redirect(route('account.depositSlipFieldsData.index'));
    }

    /**
     * Remove the specified TransactionDepositSlipFieldsData from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TransactionDepositSlipFieldsData $transactionDepositSlipFieldsData */
        $transactionDepositSlipFieldsData = TransactionDepositSlipFieldsData::find($id);

        if (empty($transactionDepositSlipFieldsData)) {
            Flash::error('Transaction Deposit Slip Fields Data not found');

            return redirect(route('account.depositSlipFieldsData.index'));
        }

        $transactionDepositSlipFieldsData->delete();

        Flash::success('Transaction Deposit Slip Fields Data deleted successfully.');

        return redirect(route('account.depositSlipFieldsData.index'));
    }
}
