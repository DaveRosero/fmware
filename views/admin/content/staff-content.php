<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Manage Staff</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">People</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Staff
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
                                    <h5 class="card-title fw-semibold mb-4">Staff List</h5>
                                </div>
                                <div class="col text-end">
                                    <div class="d-inline-block me-2">
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaff">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-borderless" id="staff-table">
                                <thead>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Mobile Number</th>
                                    <th class="text-center">Date Created</th>
                                    <th class="text-center">Position</th>
                                </thead>
                                <tbody>
                                    <?php $staff->getStaffList(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main--> 