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
                        <tr>
                            <th scope="row">POS_D4F3E0EE7820263C09E1</th>
                            <td>July 26, 2024 09:43 AM</td>
                            <td>₱750.00</td>
                            <td><span class="badge text-bg-secondary">Claimed</span></td>
                            <td>July 27, 2024 10:45 AM</td>
                            <td>
                                <button class="btn btn-primary" data-bs-target="#pickupView"
                                    data-bs-toggle="modal">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>