<!-- Order Items Modal -->
<div class="modal fade" id="order-items-modal" tabindex="-1" aria-labelledby="order-items-modal-label" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="order-items-modal-label"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex">
        <div class="table-responsive flex-fill">
          <table id="order-items-table" class="table text-center align-middle table-borderless w-100">
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
          <p><strong>Date:</strong> <span id="order-date"></span></p>
          <p><strong>Total Gross:</strong> <span id="order-gross"></span></p>
          <p><strong>Delivery Fee:</strong> <span id="order-delivery-fee"></span></p>
          <p><strong>Customer Name:</strong> <span id="order-user-name"></span></p>
          <p><strong>Phone#:</strong> <span id="order-user-phone"></span></p>
          <p><strong>Address:</strong> <span id="order-address"></span></p>
          <p><strong>Description:</strong> <span id="order-address-desc"></span></p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="accept-order-btn">Accept Order</button>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#qr-scanner-modal" id="webcamButton">Scan QR Code</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>