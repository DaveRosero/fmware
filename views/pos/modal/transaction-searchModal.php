<div class="modal fade" id="transaction-searchModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transaction-searchModalLabel">TRANSACTIONS</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">


                <!--Show Search Result & transaction Details-->
                <table class="w-100" id="transaction-table">
                    <thead>
                        <tr>
                            <th scope="col">Transaction #</th>
                            <th scope="col">Transaction Date</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Transaction Type</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!---fetching information from the database -->
                        <?php foreach ($transactions as $transaction) : ?>
                            <?php if ($transaction['status'] !== 'void') : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['pos_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($transaction['date'])); ?></td>
                                <td>₱<?php echo number_format($transaction['total'], 2); ?></td>
                                <td><?php echo isset($transaction['name']) ? htmlspecialchars($transaction['name']) : ''; ?>
                                </td>
                                <td>
                                    <?php
                                    $status = htmlspecialchars($transaction['status']);
                                    $badgeClass = 'badge text-bg-primary';

                                    switch ($status) {
                                        case 'paid':
                                            $badgeClass = 'badge bg-primary text-white';
                                            break;
                                        case 'void':
                                        case 'refunded':
                                        case 'replaced':
                                            $badgeClass = 'badge bg-secondary text-white';
                                            break;
                                            // Add more cases as needed for other status types
                                    }
                                    ?>

                                    <span class="<?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-primary view-transaction-btn" data-bs-posref="<?php echo htmlspecialchars($transaction['pos_ref']); ?>">View</button>
                                </td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>