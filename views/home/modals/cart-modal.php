<!-- Reset Cart Modal -->
<div class="modal fade" id="checkout-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fw-bold" id="exampleModalLabel">Checkout</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" id="confirm-reset" data-user-id="<?php echo $user_info['id']; ?>">Confirm</button>
        </div>
    </div>
  </div>
</div>

<form id="newAddress">
  <!-- Address Modal -->
  <div class="modal fade" id="address-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
          <h5 class="modal-title fw-bold text-success" id="exampleModalLabel">Register an Address</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row mt-2">
              <p class="text-danger fs-6" id="address-label"></p>
              <p class="fs-6 text-muted"><em>*We only accept orders within Bulacan</em></p>
              <div class="col">
                <label class="form-label" for="">House No. / Blk. & Lot / Apartment No. / Building <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="house_no" id="nouse_no" required>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col">
                <label class="form-label" for="">Street <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="street" id="street" required>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col">
                <label class="form-label" for="">Barangay <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="brgy" id="brgy" required>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col">
                <label class="form-label" for="">Municipality <span class="text-danger">*</span></label>
                <input class="form-control" type="text" name="municipality" id="municipality" required>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col">
                <label class="form-label" for="">Notes / Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
              <input type="hidden" name="user_id" value="<?php echo $user_info['id']; ?>">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Confirm Address</button>
          </div>
      </div>
    </div>
  </div>
</form>

