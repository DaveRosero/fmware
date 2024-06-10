<div class="container-fluid">
    <!--Category Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Category List</h5>
                </div>
                <div class="col text-end">
                    <div class="d-inline-block me-2">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newCategory">
                            New Category
                        </button>
                    </div>
                    <div id="printButtonContainer" class="d-inline-block">
                        <!-- DataTables print button will be placed here -->
                    </div>
                </div>
            </div>
            <table class="table table-borderless" id="category-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Date Added</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $category->getCategory(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <!--Category Table End-->
</div>

