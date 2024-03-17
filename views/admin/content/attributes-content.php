<div class="container-fluid">
    <!--Create Attribute Start-->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">New Attribute</h5>
                    <form action="/fmware/attributes" method="POST">
                        <div class="row">
                            <div class="col">
                                <label for="group_name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="group_name" name="attr_name" aria-describedby="group_name" required>
                            </div>
                        </div>
                        <input type="hidden" name="action" value="new_attr">    
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold mb-4">Add Variant</h5>
                    <form action="/fmware/attributes" method="POST">
                        <div class="row">
                            <div class="col">
                                <label for="group_name" class="form-label">Attribute</label>
                                <select class="form-select" id="category" name="attr_id">
                                    <?php $attr->getAttributes(); ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="group_name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="group_name" name="attr_value" aria-describedby="group_name">
                            </div>
                        </div>
                        <input type="hidden" name="action" value="new_variant">    
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Create Attribute End-->


    <!--Product Table Start-->
    <div class="card">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col">
                    <h5 class="card-title fw-semibold mb-4">Attributes List</h5>
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
                            <th>Variant</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $attr->getVariant(); ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
    <!--Product Table End-->
</div>

