<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Brands</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Maintenance</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Brands
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
                                <div class="col text-end">
                                    <div class="d-inline-block me-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newBrand">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-borderless w-100" id="brand-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Brand</th>
                                        <th class="text-center">Number of Products</th>
                                        <th class="text-center">Date Added</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $brand->getBrands(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main-->