<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Purchase Orders</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Inventory</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Purchase Orders
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col">
                                    <div class="text-end">
                                        <button class="btn btn-primary" id="create_po" data-bs-toggle="modal" data-bs-target="#createPO">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless w-100" id="purchase-order-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">P.O. #</th>
                                            <th class="text-center">Supplier</th>
                                            <th class="text-center">Date Created</th>
                                            <th class="text-center">Date Received</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center"><i class="bi bi-gear-fill"></i></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $po->showPO(); ?>
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