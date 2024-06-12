<form id="add-expenses-form" method="POST">
    <div class="modal fade" id="expenses-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Expenses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col form-floating">
                            <input type="text" class="form-control" name="description" placeholder="" required>
                            <label for="" class="mx-2">Description<span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col form-floating">
                            <input type="number" class="form-control" name="amount" placeholder="" min="1" step="any" required>
                            <label for="" class="mx-2">Amount<span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<form id="edit-df-form" method="POST">
    <div class="modal fade" id="df-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Delivery Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col form-floating">
                            <input type="text" class="form-control" name="municipal" id="municipal" placeholder="" readonly>
                            <label for="" class="mx-2">Municipality</label>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col form-floating">
                            <input type="number" class="form-control" name="df" id="df" placeholder="" min="1" step="any" required>
                            <label for="" class="mx-2">Delivery Fee</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>