<div class="modal fade" id="transactionSearch-Modal" aria-hidden="true" aria-labelledby="transactionSearch-ModalLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionSearch-ModalLabel">Transaction Search</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table align-middle" id="transearch" style="width: 100%;">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Transaction #</th>
                            <th scope="col">Transaction Date</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Transaction Type</th>
                            <th scope="col">Validity</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['pos_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($transaction['date'])); ?></td>
                                <td>₱<?php echo number_format($transaction['total'], 2); ?></td>
                                <td><?php echo isset($transaction['transaction_type']) ? htmlspecialchars($transaction['transaction_type']) : ''; ?></td>
                                <td>
                                    <?php if ($transaction['status'] === 'Valid') : ?>
                                        <span class="badge text-bg-primary"><?php echo htmlspecialchars($transaction['status']); ?></span>
                                    <?php else : ?>
                                        <span class="badge text-bg-danger"><?php echo htmlspecialchars($transaction['status']); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-primary view-transaction-btn" data-bs-posref="<?php echo htmlspecialchars($transaction['pos_ref']); ?>">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Script to handle View button click -->
                        <script>
                            $(document).ready(function() {
                                $('.view-transaction-btn').click(function() {
                                    const posRef = $(this).data('bs-posref');

                                    $.ajax({
                                        url: '/pos-getTransaction',
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
                                            $('#transactionView').modal('show');
                                        },

                                    });
                                });
                            });
                        </script>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>