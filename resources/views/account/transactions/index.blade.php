@extends('layouts.app')

@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">Transactions</li>
    </ol>
    <div class="container-fluid">
        <div class="animated fadeIn">
             {{-- @include('flash::message') --}}
             <div class="row">
                 <div class="col-lg-12">
                     <div class="card">
                         
                         <div class="card-body p-0" style="overflow: auto;">
                             @include('account.transactions.table')
                              <div class="pull-right mr-3">
                                     
                              </div>
                         </div>
                     </div>
                  </div>
             </div>
         </div>
    </div>
@endsection

