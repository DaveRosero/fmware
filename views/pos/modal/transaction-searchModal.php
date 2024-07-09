<div class="modal fade" id="transaction-searchModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transaction-searchModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
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
                              <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($transaction['pos_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($transaction['date'])); ?></td>
                                <td>₱<?php echo number_format($transaction['total'], 2); ?></td>
                                <td><?php echo isset($transaction['name']) ? htmlspecialchars($transaction['name']) : ''; ?>
                                </td>
                                <td>
                                    <span
                                        class="badge text-bg-primary"><?php echo htmlspecialchars($transaction['status']); ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-primary view-transaction-btn"
                                        data-bs-posref="<?php echo htmlspecialchars($transaction['pos_ref']); ?>">View</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>