<div class="modal fade" id="transaction-viewModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionViewLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-8">
                        <div class="col" style="height: calc(95vh - 100px);overflow-y: auto;overflow-x: hidden;">
                            <table class="table align-middle">
                                <thead class="table table-secondary">
                                    <tr>
                                        <th scope="col">Selected</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Subtotal</th>
                                        <th scope="col">Selected QTY</th>
                                        <th scope="col">Item Condition</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionItems">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Show the details  and total of the items to be  returned or refunded-->
                    <div class="col-4">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="rtransaction-total"></h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <div class="col border-bottom mb-2">
                            <form id="discount-form" class="mb-2">
                                <label for="discountRec-input" class="form-label">Discount</label>
                                <input type="number" class="form-control" id="rviewdiscountRec-input" disabled />
                                <label for="cashRec-input" class="form-label">Cash Received</label>
                                <input type="number" class="form-control" id="rviewcashRec-input" disabled />
                            </form>
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" id="rtransaction-change"></h1>
                                </div>
                            </div>
                            <input type="hidden" name="delivery-fee-value" id="delivery-fee-value-hidden" value="">
                        </div>
                        <div class="col mb-2">
                            <form action="" class="transactionType-form" id="transactionType-form">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select   w-50" aria-label="Default select example" disabled>
                                        <option id="rtransaction-type" selected></option>
                                    </select>
                                    <select class="form-select   w-50" aria-label="Default select example" id="rpaymentMethod" disabled>
                                    </select>
                                </div>
                                <h3>Refund Details</h3>
                                <div class="d-flex justify-content-between text-end">
                                    <div>
                                        <h5 class="text-secondary my-auto">Returned/Refunded Reason:</h5>
                                        <select class="form-select select-reason" aria-label="Default select example" id="replace-refund-reason" disabled>
                                            <option value="">Select Reason</option>
                                            <option value="wrong items">Wrong Items</option>
                                            <option value="duplicated items">Duplicated Items</option>
                                            <option value="expired items">Expired items</option>
                                        </select>
                                    </div>
                                    <div>
                                        <h5 class="text-secondary my-auto">Value Returned/Refunded:</h5>
                                        <h1 class="text-success" id="refund-TotalValue"></h1>
                                    </div>
                                </div>
                                <h3>Customer Details</h3>
                                <form>
                                    <label for="viewfName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="rfName-input" disabled />
                                    <label for="viewlName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="rlName-input" disabled />
                                    <label for="viewstreet-input" class="form-label" id="street-label">Street:</label>
                                    <input type="text" class="form-control" id="viewstreet-input" disabled />
                                    <label for="viewbrgy-input" class="form-label" id="brgy-label">Baranggay:</label>
                                    <input type="text" class="form-control" id="viewbrgy-input" disabled />
                                    <label for="viewmunicipality-input" class="form-label" id="municipality-label">Municipality:</label>
                                    <input type="text" class="form-control" id="viewmunicipality-input" disabled />
                                    <label for="contact-input" class="form-label" id="contact-label">Contact:</label>
                                    <input type="text" class="form-control" id="viewcontact-input" disabled />
                                    <label for="viewdeliverer-input" class="form-label" id="deliverer-label">Deliverer:</label>
                                    <input type="text" class="form-control" id="viewdeliverer-input" disabled />
                                    <input type="hidden" id="deliverer-name-hidden" name="deliverer_name" value="Deliverer Name">
                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <p class="ms-2" id="rtrans-username"></p>
                            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_info['id']); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!--choose to confirm or you can cancel it by pressing the x on the top of the view modal -->
            <div class="modal-footer">
                <button id="refund-button" class="btn btn-primary" disabled>Process Refund</button>
                <button id="replace-button" class="btn btn-primary" disabled>Process Replacement</button>
            </div>
        </div>
    </div>
</div>