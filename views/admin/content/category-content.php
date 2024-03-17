<div class="container-fluid">
    <!--Create Category Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">New Category</h5>
            <form action="/fmware/category" method="POST">
                <div class="row">
                    <div class="col">
                        <label for="group_name" class="form-label">Name  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="category_name" aria-describedby="group_name" required>
                    </div>
                </div>
                <input type="hidden" name="action" value="create_category">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Category End-->


    <!--Category Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Category List</h5>
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
                        <?php $category->getCategory(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Category Table End-->
</div>

