<div class="container-fluid">
    <!--Unit Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Unit List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newUnit">
                            New Unit
                        </button>
                    </div>
                </div>
            </div>
            <table class="table table-borderless" id="unit-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Unit</th>
                        <th>Number of Products</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $unit->getUnits(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Category Table End-->
</div>

