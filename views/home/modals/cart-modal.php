<form id="place-order">
  <!-- Checkout Modal -->
  <div class="modal fade" id="checkout-form" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fw-bold text-success" id="exampleModalLabel">Checkout</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p class="fw-bold">BILLING INFO</p>
          <div class="row mb-2">
            <div class="col">
              <label for="">First Name</label>
              <input class="form-control" type="text" name="fname" id="fname" value="<?php echo $user_info['fname']; ?>">
            </div>
            <div class="col">
              <label for="">Last Name</label>
              <input class="form-control" type="text" name="lname" id="lname" value="<?php echo $user_info['lname']; ?>">
            </div>
          </div>
          <label for="">Phone Number</label>
          <input class="form-control mb-2" type="text" name="phone" id="" value="<?php echo $user_info['phone']; ?>">
          <label for="">Address</label>
          <select class="form-select" name="address" id="address" required>
            <option value="" selected disabled>Select an Address</option>
            <?php echo $address->displayAddress($user_info['id']); ?>
          </select>
          <input type="hidden" name="address_id" id="address_id">
          <div class="d-flex justify-content-end">
            <button type="button" class="btn text-primary" id="checkout-address">Add new address</button>
          </div>
          <hr>
          <?php if ($buynow == 1): ?>
            <!-- Single item -->
            <div class="row d-flex justify-content-center align-items-center">
              <div class="col-lg-2 col-md-3 mb-4 mb-lg-0">
                <!-- Image -->
                <div class="bg-image hover-overlay hover-zoom ripple rounded" data-mdb-ripple-color="light">
                  <img src="/asset/images/products/<?= $product['image']; ?>" class="w-100" alt="product" />
                  <a href="#!">
                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.2)"></div>
                  </a>
                </div>
                <!-- Image -->
              </div>

              <div class="col-lg-4 col-md-5 mb-4 mb-lg-0">
                <!-- Data -->
                <p><strong><?= $product['name'] . ' (' . $product['variant'] . ') ' . $product['unit_value'] . ' ' . strtoupper($product['unit']) ?></strong></p>
                <!-- Data -->
              </div>

              <!-- Quantity -->
              <div class="d-flex mb-4 align-items-center" style="max-width: 300px">
                <button type="button" class="btn btn-primary px-3 me-2 subQty"
                  data-product-id="<?= $product['id'] ?>"
                  data-cart-id="<?= $product['cart_id'] ?>">
                  <i class="fas fa-minus"></i>
                </button>

                <!-- Input Field Container -->
                <div class="flex-grow-1">
                  <input min="0" name="quantity" value="<?= $product['qty'] ?>" type="number"
                    class="form-control text-center qty-input w-100"
                    data-current-stock="<?= $product['stock'] ?>"
                    data-product-id="<?= $product['id'] ?>"
                    data-cart-id="<?= $product['cart_id'] ?>" />
                </div>

                <button type="button" class="btn btn-primary px-3 ms-2 addQty"
                  data-current-stock="<?= $product['stock'] ?>"
                  data-product-id="<?= $product['id'] ?>"
                  data-cart-id="<?= $product['cart_id'] ?>">
                  <i class="fas fa-plus"></i>
                </button>
              </div>
              <!-- Quantity -->

            </div>
            <!-- Single item -->
            <hr>
          <?php endif; ?>
          <div class="row mb-2">
            <label for="">Payment Method</label>
            <div class="col-md-4">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                  <input type="radio" name="payment_type" id="cod" value="1" required>
                  <img class="me-2" width="100px" src="/asset/images/payments/cod.png" alt="COD" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                  <input type="radio" name="payment_type" id="gcash" value="2" required>
                  <img class="me-2" width="100px" src="/asset/images/payments/gcash.png" alt="GCash" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                  <input type="radio" name="payment_type" id="pickup" value="4" required>
                  <img class="me-2 ms-2" width="75px" src="/asset/images/payments/pickup.jpg" alt="Pickup" />
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row mb-2">
            <div class="col">
              <div class="card">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <p>Delivery Fee</p>
                    </div>
                    <div class="col text-end">
                      <p id="delivery-fee">-</p>
                      <input type="hidden" name="delivery-fee-value" id="delivery-fee-value">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <p>Total Amount</p>
                    </div>
                    <div class="col text-end">
                      <p id="checkout-total"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="user_id" value="<?php echo $user_info['id']; ?>">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="place-order" data-user-id="<?php echo $user_info['id']; ?>">Place Order</button>
        </div>
      </div>
    </div>
  </div>
</form>

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
              <select class="form-select" type="text" name="brgy" id="brgy" required>
                <option></option>
                <?php $address->getBrgys(); ?>
              </select>
            </div>
          </div>
          <div class="row mt-2">
            <div class="col">
              <label class="form-label" for="">Municipality <span class="text-danger">*</span></label>
              <input class="form-control" type="text" name="municipality" id="municipality" readonly>
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