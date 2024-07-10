<div class="modal fade" id="history-searchModal" aria-hidden="true"
    aria-labelledby="transactionhistorySearch-ModalLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionhistorySearch-ModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--Search using Transaction/Invoice # -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Recipient's username"
                        aria-describedby="button-addon2">
                    <button class="btn btn-outline-success" type="button" id="button-addon2">Search</button>
                </div>
                <!--Show Search Result & transaction Details-->
                <table class="table align-middle">
                    <thead class="table-secondary">
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
                        <?php foreach ($historys as $history): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($history['pos_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($history['date'])); ?></td>
                                <td>₱<?php echo number_format($history['total'], 2); ?></td>
                                <td><?php echo isset($history['name']) ? htmlspecialchars($history['name']) : ''; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = $history['status'] == 'paid' ? 'text-bg-primary' : ($history['status'] == 'void' ? 'text-bg-secondary' : '');
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