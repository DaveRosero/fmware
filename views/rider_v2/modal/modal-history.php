<!-- Order Items Modal -->
<div class="modal fade" id="historyOrder-items-modal" tabindex="-1" aria-labelledby="acceptedOrder-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyOrder-items-modal-label"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex">
        <div class="table-responsive flex-fill">
          <table id="historyOrder-items-table" class="table text-center align-middle table-borderless w-100">
            <thead>
              <tr>
                <th>Product</th>
                <th>Variant</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
              </tr>
            </thead>
            <tbody>
              <!-- Data will be populated by DataTable -->
            </tbody>
          </table>
        </div>
        <div class="ms-3 flex-shrink-1">
          <h3>Order Info</h3>
          <p><strong>Date:</strong> <span id="historyOrder-date"></span></p>
          <p><strong>Total Price:</strong> <span id="historyOrder-gross"></span></p>
          <p><strong>Delivery Fee:</strong> <span id="historyOrder-delivery-fee"></span></p>
          <p><strong>Customer Name:</strong> <span id="historyOrder-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="historyOrder-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="historyOrder-address"></span></p>
          <p><strong>Description:</strong> <span id="historyOrder-address-desc"></span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
