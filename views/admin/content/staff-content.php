<div class="container-fluid">
    <!--Restock Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Staff List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaff">
                            Register Staff
                        </button>
                    </div>
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table id="staff-table">
                <thead>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Position</th>
                </thead>
                <tbody>
                    <?php $staff->getStaffList(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Restock Table End-->
</div>

