<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Business</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Manage Business
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col col-6 mb-2">
                    <button class="btn btn-success" id="change_pin">Change PIN</button>
                </div>
            </div>
            <div class="row">
                <div class="col col-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <h5 class="card-title mb-9 fw-semibold">Delivery Fee</h5>
                            </div>
                            <div class="row">
                                <table class="table table-borderless w-100" id="municipal-table">
                                    <thead>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Municipal</th>
                                        <th class="text-center">Delivery Fee</th>
                                        <th class="text-center">Action</th>
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
                                    <button class="btn btn-primary" id="add-expenses"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <table class="table table-borderless w-100" id="expenses-table">
                                    <thead>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Action</th>
                                    </thead>
                                    <tbody>
                                        <?php $manage->showExpenses(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main-->