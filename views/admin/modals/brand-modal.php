<!-- New Modal -->
<div class="modal fade" id="newBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Brand</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="new-brand" method="POST">
            <div class="modal-body">
                <p class="text-danger" id="brand_feedback"></p>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" name="brand_name" placeholder="" required>
                    <label for="" class="form-label">Brand Name<span class="text-danger">*</span></label>
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


<!-- Edit Modal -->
<div class="modal fade" id="editBrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edit-label"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-brand" method="POST">
                <div class="modal-body">
                    <p class="text-danger" id="edit_feedback"></p>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="" required>
                        <label for="" class="form-label">New Brand Name<span class="text-danger">*</span></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="brand_id" id="brand_id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal" id="loadingModal" tabindex="-1" aria-labelledby="loadingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading...</p>
            </div>
        </div>
    </div>
</div>
