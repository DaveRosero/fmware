<div class="container-fluid">
    <!--Create Unit Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">New Unit</h5>
            <form action="/fmware/unit" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="group_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="unit_name" aria-describedby="group_name" required>
                    </div>
                </div>
                <input type="hidden" name="action" value="create_unit">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Unit End-->


    <!--Unit Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Brand List</h5>
                </div>
                <div class="col text-end">
                    <a href="#"><i class="fa-solid fa-print fs-5"></i></a>
                </div>
            </div>
            
            <form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $unit->getUnits(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Unit Table End-->
</div>

