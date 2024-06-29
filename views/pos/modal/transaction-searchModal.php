<div class="modal fade" id="transactionSearch-Modal" aria-hidden="true" aria-labelledby="transactionSearch-ModalLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionSearch-ModalLabel">Transaction Search</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search using Transaction/Invoice # -->
                <!-- <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-success" type="button" id="button-addon2">Search</button>
                </div> -->
                <!-- Show Search Result & transaction Details -->
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
                            document.addEventListener('DOMContentLoaded', function() {
                                const viewButtons = document.querySelectorAll('.view-transaction-btn');

                                viewButtons.forEach(button => {
                                    button.addEventListener('click', function() {
                                        const posRef = button.getAttribute('data-bs-posref');
                                        fetch(`get_transaction.php?pos_ref=${posRef}`)
                                            .then(response => response.text())
                                            .then(data => {
                                                // Inject modal content into the existing transaction-viewModal
                                                document.querySelector('#transaction-view-modal-content').innerHTML = data;
                                                // Update modal title with transaction number
                                                const transactionNumber = document.querySelector('#transactionViewLabel');
                                                transactionNumber.textContent = `Transaction #${posRef}`;
                                                // Show the modal
                                                const transactionViewModal = new bootstrap.Modal(document.getElementById('transactionView'));
                                                transactionViewModal.show();
                                            })
                                            .catch(error => {
                                                console.error('Error fetching transaction details:', error);
                                                alert('Error fetching transaction details. Please try again.');
                                            });
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