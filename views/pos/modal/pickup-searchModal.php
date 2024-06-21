<div class="modal fade" id="pickup-searchModal" aria-hidden="true" aria-labelledby="transactionSearch-ModalLabel" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="etransactionSearch-ModalLabel">Transaction Search</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!--Search using Transaction/Invoice # -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="button-addon2">
                    <button class="btn btn-outline-success" type="button" id="button-addon2">Search</button>
                </div>
                <!--Show Search Result & transaction Details-->
                <table class="table align-middle">
                    <thead class="table-secondary">
                        <tr>
                            <th scope="col">Transaction #</th>
                            <th scope="col">Order Date</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Status</th>
                            <th scope="col">Claimed Date</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1234567890</th>
                            <td>09/11/2001</td>
                            <td>$100.00</td>
                            <td><span class="badge text-bg-secondary">Claimed</span></td>
                            <td>09/12/2002</td>
                            <!--transaction status-->
<!-- 
                            <span class="badge text-bg-primary">Claimable</span>
                            <span class="badge text-bg-secondary">Claimed</span>
                            <span class="badge text-bg-danger">Unclaimed</span> -->

                            <!--Toggle Transaction View Modal-->
                            <!--Toggle Transaction View Modal-->
                            <td>
                                <button class="btn btn-primary" data-bs-target="#pickupView" data-bs-toggle="modal">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>