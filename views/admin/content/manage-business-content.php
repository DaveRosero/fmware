<div class="container-fluid">
    <div class="row">
        <div class="col col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <h5 class="card-title mb-9 fw-semibold">Delivery Fee</h5>
                    </div>
                    <div class="row">
                        <table class="table table-borderless" id="municipal-table">
                            <thead>
                                <th class="text-center">Status</th>
                                <th class="text-center">Municipal</th>
                                <th class="text-center">Delivery Fee</th>
                                <th class="text-center"><i class="fas fa-cog"></i></th>
                            </thead>
                            <tbody>
                                <?php $manage->showDeliveryFee(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col">
                            <h5 class="card-title mb-9 fw-semibold">Expenses</h5>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-primary" id="add-expenses">Add Expense</button>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-borderless" id="expenses-table">
                            <thead>
                                <th class="text-center">Description</th>
                                <th class="text-center">Amount</th>
                                <th class="text-center">Date</th>
                                <th class="text-center"></th>
                            </thead>
                            <tbody>
                                <?php $manage->showExpenses(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>