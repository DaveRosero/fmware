<div class="container-fluid">
    <!--Create Brand Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">New Brand</h5>
            <form action="/fmware/brands" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="group_name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="brand_name" aria-describedby="group_name" required>
                    </div>
                </div>
                <input type="hidden" name="action" value="create_brand">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Brand End-->


    <!--Brand Table Start-->
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
                        <?php $brand->getBrands(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Brand Table End-->
</div>

