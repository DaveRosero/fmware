<div class="modal fade" id="pickupView" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h1 class="modal-title fs-5" id="pickupViewLabel"></h1>
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
                                        <th scope="text-center">Item Name</th>
                                        <th scope="text-center">Unit</th>
                                        <th scope="text-center">Variant</th>
                                        <th scope="text-center">Price</th>
                                        <th scope="text-center">Quantity</th>
                                        <th scope="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="productDetails">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--Show the details  and total of the items to be  returned or refunded-->
                    <div class="col-5">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="ptransaction-total"></h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <div class="col border-bottom mb-2" id="payment-section">
                            <form id="discount-form" class="mb-2">
                                <label for="cashRec-input" class="form-label">Cash Received</label>
                                <input type="number" class="form-control" name="cash" id="pickupcashRec-input" />
                            </form>
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" name="changes" id="pickup-change">₱0.00</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-2">
                            <form action="" class="transactionType-form">
                                <div class="d-flex justify-content-end gap-2">
                                    <select class="form-select   w-50" aria-label="Default select example"
                                        id="pickupStatus" disabled>
                                    </select>
                                    <select class="form-select   w-50" aria-label="Default select example" 
                                    id="ptransactionStatus" disabled>
                                    </select>
                                </div>
                                <!--Show the details  of customer this can be blank-->
                                <h3>Customer Details</h3>
                                <form>
                                    <label for="fName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" placeholder="Jhon" id="pickupfName-input"
                                        disabled />
                                    <label for="lName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" placeholder="Doe" id="pickuplName-input"
                                        disabled />
                                    <label for="contact-input" class="form-label">Contact:</label>
                                    <input type="text" class="form-control" placeholder="1231-1231-1231"
                                        id="pickupcontact-input" disabled />
                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <?php echo htmlspecialchars($user_name); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--choose to confirm or you can cancel it by pressing the x on the top of the view modal -->
            <div class="modal-footer">
                <button class="btn btn-primary prepared" data-bs-toggle="modal"
                    data-bs-target="#claimConfirmationModal">Prepared</button>
                <button class="btn btn-primary claim" data-bs-toggle="modal"
                    data-bs-target="#claimConfirmationModal" disabled>Claim</button>
            </div>
        </div>
    </div>
</div>