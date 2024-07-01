<!-- <div class="modal fade" id="transactionView" aria-hidden="true" aria-labelledby="transactionViewLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionViewLabel">Transaction #1234567890</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow:hidden;">
                <div class="row">
                    <div class="col-7">
                        <div class="col" style="height: calc(75vh - 100px);overflow-y: auto;overflow-x: hidden;">
                            <table class="table align-middle">
                                <thead class="table table-secondary">
                                    <tr>
                                        <th scope="col">Selected</th>
                                        <th scope="col">Item Name</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Variant</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Quantity Selected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td scope="row">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                            </div>
                                        </td>
                                        <td>Boysen Paint (W)</td>
                                        <td>1 Liter</td>
                                        <td>White</td>
                                        <td>$99.00</td>
                                        <td>3</td>
                                        <td>
                                            <div class="input-group">
                                                <button class="btn btn-sm btn-outline-secondary " type="button">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                                <input type="number" class="form-control text-center " placeholder="0" style="max-width: 50px;">
                                                <button class="btn btn-sm btn-outline-secondary " type="button">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="transaction-total">$100.00</h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <div class="col border-bottom mb-2">
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Return/Refund Total:</h5>
                                    <h1 class="text-success" id="return-refundTotal">₱0.00</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <form action="" class="transactionType-form">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select w-50" aria-label="Default select example">
                                        <option value="1">--select reason--</option>
                                        <option value="1">broken items</option>
                                        <option value="2">wrong items</option>
                                        <option value="2">item change</option>
                                        <option value="2">others</option>
                                    </select>
                                    <select class="form-select w-50" aria-label="Default select example" disabled>
                                        <option value="1">walk-in</option>
                                        <option value="2">deliver</option>
                                        <option value="2">online order</option>
                                    </select>
                                </div>
                                <div> 
                                    <label for="other=reasons" class="form-label">If other specify:</label>
                                    <input type="text" class="form-control"  id="other=reasons"  />
                                </div>
                                <h3>Customer Details</h3>
                                <form>
                                    <label for="fName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" placeholder="Jhon" id="fName-input" disabled />
                                    <label for="lName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" placeholder="Doe" id="lName-input" disabled />
                                    <label for="address-input" class="form-label">Address:</label>
                                    <input type="text" class="form-control" placeholder="#123 Somewhere Street, Nowhere City" id="address-input" disabled />
                                    <label for="contact-input" class="form-label">Contact:</label>
                                    <input type="text" class="form-control" placeholder="1231-1231-1231" id="contact-input" disabled />
                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <p class="ms-2">Username</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replaceConfirmationModal">Replace</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#refundConfirmationModal">Refund</button>
            </div>
        </div>
    </div>
</div> -->


<div class="modal fade" id="transactionView" aria-hidden="true" aria-labelledby="transactionViewLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionViewLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display transaction details dynamically -->
                <div id="transaction-view-modal-content">
                    <p id="transaction-fname"></p>
                    <p id="transaction-lname"></p>
                    <p id="transaction-date"></p>
                    <p id="transaction-subtotal"></p>
                    <p id="transaction-total"></p>
                    <p id="transaction-discount"></p>
                    <p id="transaction-cash"></p>
                    <p id="transaction-changes"></p>
                    <p id="transaction-delivery-fee"></p>
                    <p id="transaction-deliverer"></p>
                    <p id="transaction-contact"></p>
                    <p id="transaction-transaction-type"></p>
                    <p id="transaction-payment-type"></p>
                    <p id="transaction-status"></p>
                </div>
            </div>
        </div>
    </div>
</div>