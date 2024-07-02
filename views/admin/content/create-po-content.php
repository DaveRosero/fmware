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
                        <h6 class="mb-0"><?php echo date('F j, Y'); ?></h6>
                        <p class="fst-italic">P.O. # {auto generated}</p>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-2">
                <hr>
            </div>

            <div class="row">
                <table class="table table-bordered">
                    <thead class="table-secondary">
                        <th></th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">BALL VALVE 1/2</td>
                            <td class="fw-semibold fs-3">2</td>
                            <td class="fw-semibold fs-3">pack</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">UNION PATENTE 1/2</td>
                            <td class="fw-semibold fs-3">2</td>
                            <td class="fw-semibold fs-3">pack</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">TEE 1/2</td>
                            <td class="fw-semibold fs-3">3</td>
                            <td class="fw-semibold fs-3">pack</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">PVC PIPE #2 (ORANGE)</td>
                            <td class="fw-semibold fs-3">50</td>
                            <td class="fw-semibold fs-3">piece</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">PVC PIPE #3 (ORANGE)</td>
                            <td class="fw-semibold fs-3">40</td>
                            <td class="fw-semibold fs-3">piece</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="fw-semibold fs-3">PPR PIPE 1/2</td>
                            <td class="fw-semibold fs-3">2</td>
                            <td class="fw-semibold fs-3">roll</td>
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

