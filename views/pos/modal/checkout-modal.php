<!-- Modals -->
<div class="modal fade" id="checkoutModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Checkout</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-7">
                        <div class="modalBody-header">
                            <h3 class="modalHeader-title">Items</h3>
                        </div>
                        <div class="col modalItem-list">
                            <table class="table align-middle">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                        <th>Variant</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="cart-body-modal">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="row text-center border-bottom mb-2">
                            <h1 class="text-danger" id="cart-total-modal">₱0.00</h1>
                            <p class="text-secondary">Total Price</p>
                        </div>
                        <!-- Discount and Total Section in Modal Body -->
                        <div class="col border-bottom mb-2">
                            <form id="discount-form" class="mb-2">
                                <label for="discount-input" class="form-label">Discount</label>
                                <input type="number" class="form-control apply-discount" id="discount-input" />
                                <label for="cashRec-input" class="form-label">Cash Received</label>
                                <input type="number" class="form-control" id="cashRec-input" />
                            </form>
                            <div class="mb-2">
                                <div class="d-flex justify-content-end">
                                    <h5 class="text-secondary my-auto">Change:</h5>
                                    <h1 class="text-success" id="change-display">₱0.00</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <form action="" class="transaction-form">
                                <div class="d-flex justify-content-end">
                                    <select class="form-select w-50" aria-label="Default select example">
                                        <option value="1">walk-in</option>
                                        <option value="2">deliver</option>
                                    </select>
                                </div>
                                <h3>Customer Details</h3>
                                <div>
                                    <label for="fName-input" class="form-label">First Name:</label>
                                    <input type="text" class="form-control" id="fName-input" />
                                    <label for="lName-input" class="form-label">Last Name:</label>
                                    <input type="text" class="form-control" id="lName-input" />
                                    <label for="address-input" class="form-label">Address:</label>
                                    <input type="text" class="form-control" id="address-input" />
                                    <label for="contact-input" class="form-label">Contact:</label>
                                    <input type="text" class="form-control" id="contact-input" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                </button>
                <button class="btn btn-success print">Checkout</button>
            </div>
        </div>
    </div>
</div>