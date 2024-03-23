<x-app-layout>
    <input type="hidden" name="auth-request" id="auth-request" value="{{ Auth::user()->id }}">

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Balance
        </h2>
        @if(Auth::user()->account_type == 'C')
        <div align="right">
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-primary text-white" data-bs-toggle="modal" data-bs-target="#deposit-check"><i class="fa fa-money-bill fa-lg"></i> Check Deposit</button>
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-primary text-white" data-bs-toggle="modal" data-bs-target="#purchase-item"><i class="fa fa-shirt fa-lg"></i> Purchase Item</button>
        </div>
        @endif
    </x-slot>

    @if(Auth::user()->account_type == 'C')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <i id="hide-amount" class="fa fa-eye fa-lg" onclick="hideShowAmount('H')"></i>
                    Current Balance: 
                    <strong id="my-wallet-value" class="text-success"></strong>
                    <strong id="my-wallet-value-hidden" class="text-success" style="display:none;">-------</strong> 
                    <i id="see-more" class="fa fa-info-circle fa-lg text-success" onclick="getMyTransactions()"></i>
                </div>
                <div class="p-6" id="transactions-list"></div>
            </div>
        </div>
    </div>
    @else
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Pendind Check Deposit
                </div>
                <hr/>
                <div class="navbar p-2">
                    <table class="table table-hover table-responsive" id="table-list">
                        <thead class="alert-primary">
                            <tr>
                                <tr id="table-trs"></tr>
                            </tr>
                        </thead>
                        <tbody id="table-results">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MODALS -->
    <div class="modal fade" id="deposit-check" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header alert-info">
            <h5 class="modal-title"><strong>Check Deposit</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <fieldset>
                  <div class="alert alert-danger" id="error-check-deposit" style="display: none;">
                  </div>
                  <div class="alert alert-success" id="success-check-deposit" style="display: none;">
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><i class="fa fa-money-bill"></i> Amount USD</label>
                    <input type="text" id="amount-deposit" name="amount-deposit" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><i class="fa fa-star"></i> Description</label>
                    <input type="text" id="description-deposit" name="description-deposit" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                  </div>
                  <div class="mb-3">
                    <br/>
                    <div align="center" onclick="callFileUploadBtn()">
                        <button><i class="fa fa-upload fa-2x"></i></button>
                        <br/>
                        Upload Check Picture
                    </div>
                    <input type="file" id="picture-deposit" name="picture-deposit" class="form-control" style="display:none;" onchange="readUpload();" accept="image/*">
                    <div id="upload-name"></div>
                  </div>
                </fieldset>
            </div>
            <div class="modal-footer">
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-secondary text-white" onclick="closeTransactionModal()" data-bs-dismiss="modal">Close</button>
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-primary text-white" onclick="depositCheck()">Deposit</button>
            </div>
        </div>
        </div>
    </div>


    <div class="modal fade" id="purchase-item" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header alert-info">
            <h5 class="modal-title"><strong>Purchase</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <fieldset>
                  <div class="alert alert-danger" id="error-purchase" style="display: none;">
                  </div>
                  <div class="alert alert-success" id="success-purchase" style="display: none;">
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><i class="fa fa-money-bill"></i> Amount USD</label>
                    <input type="text" id="amount-purchase" name="amount-purchase" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><i class="fa fa-calendar"></i> Date</label>
                    <input type="date" id="date-purchase" name="date-purchase" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                  </div>
                  <div class="mb-3">
                    <label class="form-label"><i class="fa fa-star"></i> Description</label>
                    <input type="text" id="description-purchase" name="description-purchase" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                  </div>
                </fieldset>
            </div>
            <div class="modal-footer">
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-secondary text-white" onclick="closeTransactionModal()" data-bs-dismiss="modal">Close</button>
            <button type="button" class="items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md bg-primary text-white" onclick="purchaseItem()">Purchase</button>
            </div>
        </div>
        </div>
    </div>


</x-app-layout>
