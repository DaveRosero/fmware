<div class="modal fade" id="pickupView" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="pickupViewLabel">Transaction #1234567890</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow:hidden;">
                <div class="row">
                    <div class="col-7">
                        <div class="col" style="height: calc(75vh - 100px);overflow-y: auto;overflow-x: hidden;">
                            <table class="table align-middle">
                                <thead class="table table-secondary">
                                    <tr>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Boysen Paint (W)</td>
                                        <td>1 Liter</td>
                                        <td>White</td>
                                        <td>$99.00</td>
                                        <td>3</td>
                                        <td>$297.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Show the details  and total of the items to be  returned or refunded-->
                    <div class="col-5">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="transaction-total">$297.00</h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <div class="col border-bottom mb-2">
                            <select class="form-select   w-50" aria-label="Default select example" id="paymentMethod"
                                disabled>
                                <option value="1">G-Cash</option>
                            </select>
                            <form id="discount-form" class="mb-2">
                                <label for="cashRec-input" class="form-label">Cash Received</label>
                                <input type="number" class="form-control" id="cashRec-input" />
                            </form>
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" id="pickup-change">₱0.00</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <form action="" class="transactionType-form">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select   w-50" aria-label="Default select example"
                                        id="pickupStatus" disabled>
                                        <option value="1">Claimable</option>
                                        <option value="2">Claimed</option>
                                        <option value="3">Unclaimed</option>
                                    </select>
                                    <select class="form-select   w-50" aria-label="Default select example"
                                        id="transactionStatus" disabled>
                                        <option value="1">Un-Paid</option>
                                        <option value="2">Paid Online</option>
                                        <option value="3">Paid Cash</option>
                                    </select>
                                </div>
                                <!--Show the details  of customer this can be blank-->
                                <h3>Customer Details</h3>
                                <form>
                                    <label for="fName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" placeholder="Jhon" id="fName-input"
                                        disabled />
                                    <label for="lName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" placeholder="Doe" id="lName-input"
                                        disabled />
                                    <label for="contact-input" class="form-label">Contact:</label>
                                    <input type="text" class="form-control" placeholder="1231-1231-1231"
                                        id="contact-input" disabled />
                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <p class="ms-2" name="user_id" id="pickup-username"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!--choose to confirm or you can cancel it by pressing the x on the top of the view modal -->
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#claimConfirmationModal">Claim</button>
            </div>
        </div>
    </div>
</div>