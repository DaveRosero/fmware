<!-- New Modal -->
<div class="modal fade" id="reset-warning" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title fw-bold text-danger" id="exampleModalLabel">Do you want to delete all items in your cart?</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="text-muted fs-6">Note: This action is irreversible. Please proceed with caution.</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" id="confirm-reset" data-user-id="<?php echo $user_info['id']; ?>">Confirm</button>
        </div>
    </div>
  </div>
</div>