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
            <div class="row mt-2">
                <hr>
            </div>
        </div>
    </div>
</div>

