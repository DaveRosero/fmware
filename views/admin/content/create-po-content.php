<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="text-center">
                    <h5 class="mb-0 fw-semibold">FM Odulio's Enterprise & Gen. Merchandise</h5>
                    <p class="mb-0">Mc Arthur Hi-way, Poblacion II, Marilao, Bulacan</p>
                    <p class="mb-0">fmoduliogenmdse@yahoo.com</p>
                    <p class="mb-0">0922-803-3898</p>
                    <h5 class="mt-4">Purchase Order</h5>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h6 class="mb-0"><?php echo $supplier_info['name']; ?></h6>
                    <p class="mb-0"><?php echo $supplier_info['address']; ?></p>
                    <p class="mb-0"><?php echo $supplier_info['email']; ?></p>
                    <p class="mb-0"><?php echo $supplier_info['phone']; ?></p>
                </div>
                <div class="col-md-6">
                    <div class="text-end">
                        <h6 class="mb-0"><?php echo $po_info['date']; ?></h6>
                        <p class="fst-italic"><?php echo $po_info['po_ref']; ?></p>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <hr>
            </div>

            <div class="text-end mb-2">
                <button class="btn btn-success">+ Add Product</button>
            </div>

            <div class="row">
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Unit</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td>BALL VALVE 1/2</td>
                            <td class="w-25"><input class="form-control" type="number"></td>
                            <td class="w-25"><input class="form-control" type="number"></td>
                            <td>pack</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="">REMARKS:</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

