<!--begin::App Main-->
<main class="app-main"> <!--begin::App Content Header-->
    <div class="app-content-header"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Dashboard</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Dashboard
                        </li>
                    </ol>
                </div>
            </div> <!--end::Row-->
        </div> <!--end::Container-->
    </div> <!--end::App Content Header--> <!--begin::App Content-->
    <div class="app-content"> <!--begin::Container-->
        <div class="container-fluid"> <!--begin::Row-->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box"> <span class="info-box-icon text-bg-primary shadow-sm"> <i class="bi bi-bag-fill"></i> </span>
                        <div class="info-box-content"> <span class="info-box-text">Online Orders</span> 
                        <span class="info-box-number">₱1,000,000.00</span> </div> <!-- /.info-box-content -->
                    </div> <!-- /.info-box -->
                </div> <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box"> <span class="info-box-icon text-bg-success shadow-sm"> <i class="bi bi-cart-fill"></i> </span>
                        <div class="info-box-content"> <span class="info-box-text">Sales</span> 
                        <span class="info-box-number">₱1,000,000.00</span> </div> <!-- /.info-box-content -->
                    </div> <!-- /.info-box -->
                </div> <!-- /.col --> <!-- fix for small devices only --> <!-- <div class="clearfix hidden-md-up"></div> -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box"> <span class="info-box-icon text-bg-danger shadow-sm"> <i class="bi bi-wallet-fill"></i> </span>
                        <div class="info-box-content"> <span class="info-box-text">Expenses</span> 
                        <span class="info-box-number">₱1,000,000.00</span> </div> <!-- /.info-box-content -->
                    </div> <!-- /.info-box -->
                </div> <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box"> <span class="info-box-icon text-bg-info shadow-sm"> <i class="bi bi-tag-fill"></i> </span>
                        <div class="info-box-content"> <span class="info-box-text">Discounts</span> 
                        <span class="info-box-number">₱1,000,000.00</span> </div> <!-- /.info-box-content -->
                    </div> <!-- /.info-box -->
                </div> <!-- /.col -->
            </div> <!--end::Row--> <!--begin::Row-->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div id="sales-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div id="profits-chart"></div>
                        </div>
                    </div>
                </div>
            </div> <!--end::Row-->
        </div>
    </div> <!--end::App Content-->
</main> <!--end::App Main--> 