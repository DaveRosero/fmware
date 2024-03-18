<div class="container-fluid">
    <!--Category Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Category List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2"> <!-- Add margin-right to create spacing -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategory">
                            New Category
                        </button>
                    </div>
                    <div class="d-inline-block">
                        <a href="#"><i class="fa-solid fa-print fs-5"></i></a>
                    </div>
                </div>
            </div>
            
            <form>
                <table id="category-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Date Added</th>
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

