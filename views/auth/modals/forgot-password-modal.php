<div class="modal fade" id="forgot-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="forgot-form">
            <div class="modal-body">
                <div class="text-danger" id="forgot-feedback"></div>
                <div class="text-success" id="forgot-success"></div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" name="email" placeholder="" required>
                    <label for="" class="form-label">Email<span class="text-danger">*</span></label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>