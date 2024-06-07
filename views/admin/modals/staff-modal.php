<!-- New Modal -->
<div class="modal fade" id="addStaff" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Register New Staff</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="add-staff">
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" name="fname" placeholder="" required>
                            <label for="" class="form-label">First Name<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="text" class="form-control" name="lname" placeholder="" required>
                            <label for="" class="form-label">Last Name<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="email" class="form-control" name="email" placeholder="" required>
                            <label for="" class="form-label">Email<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="password" class="form-control" name="password" id="password" placeholder="" required>
                            <label for="" class="form-label">Password<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="password" class="form-control" name="confirm" id="confirm" placeholder="" required>
                            <label for="" class="form-label">Confirm Password<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 form-check">
                  <input
                    type="checkbox"
                    class="form-check-input"
                    id="show_password"
                  />
                  <label class="form-check-label" for="showPasscheck"
                    >Show password</label
                  >
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <input type="number" class="form-control" name="phone" id="phone" placeholder="" required>
                            <label for="" class="form-label">Mobile Number<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col">
                        <div class="form-floating mb-2">
                            <select class="form-select" name="group" required>
                                <?php $staff->getPositions(); ?>
                            </select>
                            <label for="" class="form-label">Position<span class="text-danger">*</span></label>
                        </div>
                    </div>
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
