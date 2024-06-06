<div class="container-fluid">
    <div class="row">
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
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </thead>
                            <tbody>
                                <?php $manage->showExpenses(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-6">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col ">
                            <h5 class="card-title mb-9 fw-semibold">Daily Wages | <?php echo date('F j, Y'); ?></h5>
                        </div>
                        <div class="col text-end">
                            <button class="btn btn-primary" id="pay-employees">Pay Employees</button>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-borderless" id="wage-table">
                            <thead>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </thead>
                            <tbody>
                                <?php $manage->showEmployees(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>