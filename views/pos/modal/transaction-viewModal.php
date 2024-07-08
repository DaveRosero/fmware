<div class="modal fade" id="transactionView" aria-hidden="true" aria-labelledby="transactionViewLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionViewLabel"></h1>
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
                                <tbody id="transaction-itemtable">

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
                                        <option id="transaction-transaction-type" selected></option>
                                    </select>
                                </div>
                                <div>
                                    <label for="other=reasons" class="form-label">If other specify:</label>
                                    <input type="text" class="form-control" id="other=reasons" />
                                </div>
                                <h3>Customer Details</h3>
                                <form>
                                    <div class="d-flex gap-2">
                                        <div class="col d-flex gap-2">
                                            <label for="transaction-fname" class="form-label">First Name:</label>
                                            <p id="transaction-fname"></p>
                                        </div>
                                        <div class="col d-flex gap-2">
                                            <label for="transaction-lname" class="form-label">Last Name:</label>
                                            <p id="transaction-lname"></p>
                                        </div>
                                    </div>
                                    <label for="address-input" class="form-label">Address:</label>
                                    <input type="text" class="form-control" placeholder="#123 Somewhere Street, Nowhere City" id="address-input" disabled />
                                    <div class="col d-flex gap-2"> <label for="contact-input" class="form-label">Contact:</label>
                                        <p id="transaction-contact"></p>
                                    </div>

                                </form>
                            </form>
                        </div>
                        <div class="col d-flex justify-content-end">
                            <p class="text-secondary">Request fulfilled by:</p>
                            <p class="ms-2" id="transaction-user-name"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#replaceConfirmationModal">Replace</button>
                <button class="btn btn-primary refund-items-btn" data-bs-posref="<?php echo htmlspecialchars($transaction['pos_ref']); ?>">
                    Refund
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.refund-items-btn').click(function() {
            const posRef = $(this).data('bs-posref');
            $.ajax({
                url: '/pos-process_refund',
                method: 'GET',
                data: {
                    pos_ref: posRef
                },
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#transactionViewLabel').text('Transaction #' + data.pos_ref);
                    $('#transaction-fname').text(data.firstname);
                    $('#transaction-lname').text(data.lastname);
                    $('#transaction-date').text(data.date);
                    $('#transaction-subtotal').text(data.subtotal);
                    $('#transaction-total').text(data.total);
                    $('#transaction-discount').text(data.discount);
                    $('#transaction-cash').text(data.cash);
                    $('#transaction-changes').text(data.changes);
                    $('#transaction-delivery-fee').text(data.delivery_fee);
                    $('#transaction-deliverer').text(data.deliverer_name);
                    $('#transaction-contact').text(data.contact_no);
                    $('#transaction-transaction-type').text(data.transaction_type);
                    $('#transaction-payment-type').text(data.payment_type);
                    $('#transaction-user-name').text(data.username);
                    $('#transaction-status').text(data.status);
                    $('#transactionView').modal('hide');
                    $('#refundConfirmationModal').modal('show');

                    // $.ajax({
                    //     url: '/pos-getTransactionTable',
                    //     method: 'GET',
                    //     data: {
                    //         pos_ref: posRef
                    //     },
                    //     dataType: 'html',
                    //     success: function(data) {

                    //         $('#transaction-itemtable').html(data)
                    //     },
                    // })
                },
            });
        });
    });
</script>