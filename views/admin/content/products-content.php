<div class="container-fluid">
    <!--Create Product Start-->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold">New Product</h5>
            <form action="/fmware/products" method="POST" enctype="multipart/form-data">
                <div class="row mt-2">
                    <div class="col">
                        <label for="imageInput" class="form-label">Choose an image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="imageInput" name="image" accept="image/*">
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="group_name" name="name" aria-describedby="group_name">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label for="group_name" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="category">
                            <option value="" select disabled>Select Category</option>
                            <option value="0">N/A</option>
                            <?php $products->getCategory(); ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="group_name" class="form-label">Brand <span class="text-danger">*</span></label>
                        <select class="form-select" id="brand" name="brand">
                            <option value="" select disabled>Select Category</option>
                            <option value="0">N/A</option>
                            <?php $products->getBrands(); ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="group_name" class="form-label">Unit <span class="text-danger">*</span></label>
                        <select class="form-select" id="unit" name="unit">
                            <option value="" select disabled>Select Category</option>
                            <option value="0">N/A</option>
                            <?php $products->getUnits(); ?>
                        </select>
                    </div>

                    <div class="col">
                        <label for="group_name" class="form-label">Variant <span class="text-danger">*</span></label>
                        <select class="form-select" id="category" name="attribute">
                            <option value="" select disabled>Select Variant</option>
                            <option value="0">N/A</option>
                            <?php $products->getAttributes(); ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label for="group_name" class="form-label">Description</label>
                        <input type="text" class="form-control" id="group_name" name="description" aria-describedby="group_name">
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col">
                        <label for="group_name" class="form-label">Stock Keeping Unit</label>
                        <input type="text" class="form-control" id="group_name" name="sku" aria-describedby="group_name">
                    </div>
                    <div class="col">
                        <label for="group_name" class="form-label">UPC Barcode</label>
                        <input type="text" class="form-control" id="group_name" name="upc" aria-describedby="group_name">
                    </div>
                </div>
                <input type="hidden" name="action" value="new_product">    
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
    </div>
    <!--Create Product End-->


    <!--Product Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Product List</h5>
                </div>
                <div class="col text-end">
                    <a href="#"><i class="fa-solid fa-print fs-5"></i></a>
                </div>
            </div>
            
            <form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU / UPC</th>
                            <th>Brand</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                      <?php $products->getProducts();?>  
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Product Table End-->
</div>

