<div class="modal fade" id="pickup-searchModal" aria-hidden="true" aria-labelledby="transactionSearch-ModalLabel"
    tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header  bg-dark text-white">
                <h1 class="modal-title fs-5" id="pickupSearch-ModalLabel">Pick-Up Transaction</h1>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--Show Search Result & transaction Details-->
                <table class="table table-striped table-hover align-middle w-100" id="pickup-search">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Transaction #</th>
                            <th class="text-center">Order Date</th>
                            <th class="text-center">Total Price</th>
                            <th class="text-center">Claimed Date</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    <?php foreach ($pickups as $pickup): 
                            if (in_array($pickup['status'], ['pending', 'prepared', 'claimed'])):
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pickup['order_ref']); ?></td>
                                <td><?php echo date('F j, Y h:i A', strtotime($pickup['date'])); ?></td>
                                <td>₱<?php echo number_format($pickup['gross'], 2); ?></td>
                                <td><?php echo isset($pickup['name']) ? htmlspecialchars($pickup['name']) : ''; ?>
                                </td>
                                <td>
                                    <?php
                                    $badgeClass = $pickup['status'] == 'pending' ? 'text-bg-warning text-white'
                                        : ($pickup['status'] == 'prepared' ? 'text-bg-light'
                                            : ($pickup['status'] == 'claimed' ? 'bg-primary text-white'
                                                    : ''));
                                    ?>
                                    <span
                                        class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($pickup['status']); ?></span>
                                </td>
                                <td>
                                    <button class="btn btn-primary view-pickup-btn"
                                        data-bs-orderref="<?php echo htmlspecialchars($pickup['order_ref']); ?>">View</button>
                                </td>
                            </tr>
                        <?php 
                         endif;
                        endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>