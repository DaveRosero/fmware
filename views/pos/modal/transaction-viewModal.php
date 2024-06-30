<div class="modal fade" id="transactionView" aria-hidden="true" aria-labelledby="transactionViewLabel" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="transactionViewLabel">Transaction #<?php echo ($transaction['pos_ref']); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Display transaction details dynamically -->
                <div id="transaction-view-modal-content">
                    <!-- Transaction details will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>
