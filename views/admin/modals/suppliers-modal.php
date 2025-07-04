<form id="add-supplier-form">
    <div class="modal fade" id="addSupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="supplier" placeholder="" required>
                                <label class="form-label">Supplier<span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="email" placeholder="">
                                <label class="form-label">Email address</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="contact" placeholder="">
                                <label class="form-label">Contact Person</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="phone" placeholder="">
                                <label class="form-label">Phone</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="address" placeholder="">
                                <label class="form-label">Address</label>
                            </div>
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

<form id="edit-supplier-form">
    <div class="modal fade" id="editSupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="edit-supplier" id="edit-supplier" placeholder="" required>
                                <label class="form-label">Supplier<span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="email" class="form-control" name="edit-email" id="edit-email" placeholder="">
                                <label class="form-label">Email address</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="edit-contact" id="edit-contact" placeholder="">
                                <label class="form-label">Contact Person</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="edit-phone" id="edit-phone" placeholder="">
                                <label class="form-label">Phone</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="edit-address" id="edit-address" placeholder="">
                                <label class="form-label">Address</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="edit-id" id="edit-id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>