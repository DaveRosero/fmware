<div class="modal fade" id="history-searchModal" aria-hidden="true"
    aria-labelledby="transactionhistorySearch-ModalLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h1 class="modal-title fs-5" id="transactionhistorySearch-ModalLabel"> History</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--Show Search Result & transaction Details-->
                <table class="table table-striped table-hover align-middle w-100" id="history-search">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Transaction #</th>
                            <th class="text-center">Transaction Date</th>
                            <th class="text-center">Total Price</th>
                            <th class="text-center">Transaction Type</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <!---fetching information from the database -->
                        <?php foreach ($historys as $history): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($history['pos_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($history['date'])); ?></td>
                                <td>₱<?php echo number_format($history['total'], 2); ?></td>
                                <td><?php echo isset($history['name']) ? htmlspecialchars($history['name']) : ''; ?>
                                </td>
                                <td>
                                <?php
                                    $badgeClass = $history['status'] == 'paid' ? 'text-bg-primary'
                                        : ($history['status'] == 'delivering' ? 'text-bg-primary'
                                            : ($history['status'] == 'void' ? 'text-bg-secondary'
                                                : ($history['status'] == 'fully refunded' ? 'bg-success text-white'
                                                    : ($history['status'] == 'fully replaced' ? 'bg-info text-white'
                                                        : ($history['status'] == 'partially refunded' ? 'bg-warning text-white'
                                                            : ($history['status'] == 'partially replaced' ? 'bg-warning text-white'
                                                                : ($history['status'] == 'pending' ? 'bg-warning text-white'
                                                                    : ($history['status'] == 'delivered' ? 'bg-success'
                                                                        : ''))))))));
                                    ?>
                                    <span
                                        class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($history['status']); ?></span>
                                    <!-- <span
                                        class="badge text-bg-primary"><?php echo htmlspecialchars($history['status']); ?></span> -->
                                </td>
                                <td>
                                    <button class="btn btn-primary view-history-btn"
                                        data-bs-posref="<?php echo htmlspecialchars($history['pos_ref']); ?>">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>