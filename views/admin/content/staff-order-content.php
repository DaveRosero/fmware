<form id="confirm-order-form" enctype="multipart/form-data">
    <div class="container py-5">
        <div class="row">
            <p class="fw-bold text-center">Confirm Order for <?php echo $order_ref; ?></p>
        </div>
        <div class="row">
            <div class="col">
                <label for="imageInput" class="form-label">Upload Proof of Delivery <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="imageInput" name="image" accept="image/*" required>
                <input type="hidden" name="order_ref" value="<?php echo $order_ref; ?>">
                <button type="submit" class="btn btn-primary mt-2">Confirm Order</button>
            </div>
        </div>
    </div>
</form>