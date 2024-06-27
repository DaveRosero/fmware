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
                    <button class="btn btn-secondary print"><i class="fa-solid fa-print"></i></button>
                </div>
            </div>
            <table class="table table-borderless" id="staff-table">
                <thead>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile Number</th>
                    <th>Date Created</th>
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

