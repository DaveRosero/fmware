<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Create Reports</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Create Reports
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row d-flex align-items-center justify-content-center mb-2">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <form id="create-report-form" method="POST">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="moduleSelect" class="form-label">Select Module</label>
                                            <select id="moduleSelect" name="module" class="form-select" required>
                                                <option value="">Choose a module</option>
                                                <option value="pos">Sales</option>
                                                <option value="orders">Online Orders</option>
                                                <!-- Add more modules as needed -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="startDate" class="form-label">Start Date</label>
                                            <input type="date" id="startDate" name="start_date" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="mb-3">
                                            <label for="endDate" class="form-label">End Date</label>
                                            <input type="date" id="endDate" name="end_date" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" id="generate_report">Generate Report</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!--end::Row--> <!--begin::Row-->
            <div class="row d-flex align-items-center justify-content-center d-none" id="content">
                <div class="col-12 col-sm-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col text-end">
                                    <button class="btn btn-primary" id="print"><i class="bi bi-printer-fill"></i></button>
                                </div>
                            </div>
                            <div class="row" id="printContent">
                                <table class="table table-borderless">
                                    <thead id="content-thead">

                                    </thead>
                                    <tbody id="content-tbody">

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