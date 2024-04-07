<?php
    
?>
<div class="container-fluid py-5">
    <div class="row mb-4">
        <div class="col col-md6">
            <div class="card">
                <div class="card-body">
                    <p>One</p>
                </div>
            </div>
        </div>
        <div class="col col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold text-center mb-4">My Purchase History</h4>
                    
                    <div class="row mb-4 justify-content-center align-items-center">
                        <?php $transaction->displayButtons($status, $user_info['id']); ?>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col d-flex justify-content-center">
                            <table id="transaction-table" class="table">
                                <thead>
                                    <tr>
                                        <th>Order Ref</th>
                                        <th>Total Amount</th>
                                        <th>Order Date</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $transaction->displayHistory($status, $user_info['id']); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
