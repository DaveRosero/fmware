<div class="modal fade" id="historyView" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
    tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h1 class="modal-title fs-5" id="historyViewLabel"></h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y:auto;">
                <div class="row">
                    <div class="col-7">
                        <div class="col" style="height: calc(75vh - 100px);overflow-y: auto;overflow-x: hidden;">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table table-dark">
                                    <tr>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Variant</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="productInfo">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Show the details  and total of the items to be  returned or refunded-->
                    <div class="col-5">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="htransaction-total"></h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <div class="col border-bottom mb-2">
                            <form id="discount-form" class="mb-2">
                                <label for="discountRec-input" class="form-label">Discount</label>
                                <input type="number" class="form-control" id="viewdiscountRec-input" disabled />
                                <label for="cashRec-input" class="form-label">Cash Received</label>
                                <input type="number" class="form-control" id="viewcashRec-input" disabled />
                            </form>
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" id="history-change">₱0.00</h1>
                                </div>
                            </div>
                            <input type="hidden" name="delivery-fee-value" id="delivery-fee-value-hidden" value="">
                        </div>
                        <div class="col mb-2">
                            <form action="" class="transactionType-form" id="transactionType-form">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select   w-50" aria-label="Default select example" disabled>
                                        <option id="history-transaction-type" selected></option>
                                    </select>
                                    <select class="form-select   w-50" aria-label="Default select example"
                                        id="paymentMethod" disabled>
                                    </select>
                                </div>
                                <!--Show the details  of customer this can be blank-->
                                <h3>Customer Details</h3>
                                <form>
                                    <label for="viewfName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="viewfName-input" disabled />
                                    <label for="viewlName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="viewlName-input" disabled />
                                    <label for="viewstreet-input" class="form-label" id="street-label">Street:</label>
                                    <input type="text" class="form-control" id="viewstreet-input" disabled />
                                    <label for="viewbrgy-input" class="form-label" id="brgy-label">Baranggay:</label>
                                    <input type="text" class="form-control" id="viewbrgy-input" disabled />
                                    <label for="viewmunicipality-input" class="form-label"
                                        id="municipality-label">Municipality:</label>
                                    <input type="text" class="form-control" id="viewmunicipality-input" disabled />
                                    <label for="contact-input" class="form-label" id="contact-label">Contact:</label>
                                    <input type="text" class="form-control" id="viewcontact-input" disabled />
                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <p class="ms-2" name="user_id" id="history-username"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!--choose to confirm or you can cancel it by pressing the x on the top of the view modal -->
            <div class="modal-footer">
                <button class="btn btn-primary void" data-bs-toggle="modal"
                    data-bs-target="#history-confirmVoidModal">Void</button>
            </div>
        </div>
    </div>
</div>