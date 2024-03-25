<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\DataTables\Admin\IPNLogsDataTable;
use App\Http\Requests;
use App\IPNLogs;
use Flash;
use Illuminate\Http\Request;
use Response;

class IPNLogsController extends Controller
{
    /**
     * Display a listing of the ipnlogs.
     *
     * @param IPNLogsDataTable $iPNLogsDataTable
     * @return Response
     */
    public function index(IPNLogsDataTable $iPNLogsDataTable)
    {
        return $iPNLogsDataTable->render('admin.ipn_logs.index');
    }

    /**
     * Show the form for creating a new ipnlogs.
     *
     * @return Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created IPNLogs in storage.
     *
     * @param CreateIPNLogsRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified ipnlogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $iPNLogs = IPNLogs::find($id);

        if (empty($iPNLogs)) {
            Flash::error('IPN Logs not found');

            return redirect(route('ipnlogs.index'));
        }

        return view('admin.ipn_logs.show')->with('iPNLogs', $iPNLogs);
    }

    /**
     * Show the form for editing the specified ipnlogs.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified IPNLogs in storage.
     *
     * @param int $id
     * @param UpdateIPNLogsRequest $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        
    }

    /**
     * Remove the specified IPNLogs from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        
    }
}
