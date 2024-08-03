<div class="modal fade" id="transaction-searchModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h1 class="modal-title fs-5" id="transaction-searchModalLabel">TRANSACTIONS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">


                <!--Show Search Result & transaction Details-->
                <table class="w-100" id="transaction-table">
                    <thead>
                        <tr>
                            <th scope="col">Transaction #</th>
                            <th scope="col">Transaction Date</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Transaction Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-table-body">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>