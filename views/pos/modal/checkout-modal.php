<!-- Modals -->
<div class="modal fade" id="checkoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Checkout</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                    id="close-button"></button>
            </div>
            <div class="modal-body" style="max-height: 75vh; overflow-y:auto;">
                <div class="row">
                    <div class="col-lg-7">
                        <div class="modalBody-header">
                            <h3 class="modalHeader-title">Items</h3>
                        </div>
                        <div class="col modalItem-list">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center">Item Name</th>
                                        <th class="text-center">Unit</th>
                                        <th class="text-center">Variant</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body-modal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row text-center">
                            <h1 class="text-danger" name="origtotal" id="cart-subtotal-modal">₱0.00</h1>
                            <h5 class="text-secondary my-auto">Total Price</h5>
                        </div>
                        <!---Discount and change input --->
                        <div class="col border-bottom mb-2">
                            <form id="discount-form" class="mb-2">
                                <label for="discount-input" class="form-label">Discount</label>
                                <input type="number" name="discount" class="form-control" id="discount-input" />
                            </form>
                            <div class="d-flex justify-content-end">
                                <h5 class="text-secondary my-auto" id="delivery-fee">Delivery Fee: </h5>
                                <h1 class="text-danger" name="delivery-fee-value" id="delivery-fee-value">₱0.00</h1>
                                <input type="hidden" name="delivery-fee-value" id="delivery-fee-value-hidden" value="">
                            </div>
                            <div class="d-flex justify-content-end">
                                <h5 class="text-secondary my-auto">Grand Total:</h5>
                                <h1 class="text-danger" name="total" id="cart-total-modal">₱0.00</h1>
                                <input type="hidden" name="subtotal" id="cart-total-modal" value="subtotal">
                            </div>
                            <div class="mb-2">
                                <form id="discount-form" class="mb-2">
                                    <label for="cashRec-input" class="form-label">Cash Received</label>
                                    <input type="number" class="form-control" name="cash" id="cashRec-input" />
                                </form>
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" name="changes" id="change-display">₱0.00</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col" id="address-form">
                            <form action="" class="transaction-form" id="transaction-form">
                                <div class="d-flex justify-content-evenly gap-2">
                                    <select class="form-select w-50" name="transaction_type"
                                        aria-label="Default select example">
                                        <option value="0" name="pos">Walk-in</option>
                                        <option value="1" name="walk-in">Delivery</option>
                                    </select>
                                    <select class="form-select w-50" name="payment_type" id="payment_type"
                                        aria-label="Default select example">
                                        <option value="3" name="cash">Cash</option>
                                        <option value="2" name="gcash">Gcash</option>
                                    </select>
                                </div>
                                <h3>Customer Details</h3>
                                <div>
                                    <div class="mb-3">
                                        <label for="fName-input" class="form-label">First Name:</label>
                                        <input type="text" class="form-control" name="fname" id="fName-input"
                                            required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="lName-input" class="form-label">Last Name:</label>
                                        <input type="text" class="form-control" name="lname" id="lName-input"
                                            required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="address-input" class="form-label">Street:</label>
                                        <input type="text" class="form-control" name="address" id="street-input"
                                            required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="address-input" class="form-label">Baranggay:</label>
                                        <select class="form-select" type="text" name="address" id="brgy" required>
                                            <option value="0">Select Your Baranggay</option>
                                            <?php $posaddress->getBrgys(); ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address-input" class="form-label">Municipality:</label>
                                        <input type="text" class="form-control" name="address" id="municipality"
                                            readonly />
                                    </div>
                                    <div class="mb-3">
                                        <label for="contact-input" class="form-label">Contact:</label>
                                        <input type="text" class="form-control" name="contact" id="contact-input"
                                            maxlength="11" pattern="\d{11}" required />
                                    </div>
                                </div>
                               
                                <div class="col d-flex justify-content-end">
                                    <p class="text-secondary">Request fulfilled by:</p>
                                    <?php echo htmlspecialchars($user_name); ?>
                                    <input type="hidden" name="user_id"
                                        value="<?php echo htmlspecialchars($user_info['id']); ?>">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="modal-checkout">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="footer-close-button">
                    Close
                </button>
                <button class="btn btn-success print" disabled>Checkout</button>
            </div>
        </div>
    </div>
</div>