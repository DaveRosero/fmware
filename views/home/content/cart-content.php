<section class="h-100 gradient-custom">
  <div class="container py-5">
    <div class="row d-flex justify-content-center my-4">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Cart - <?php echo $cart->cartCount($user_info['id']); ?> items</h5>
          </div>
          <div class="card-body">
            <?php $cart->getCart($user_info['id']); ?>
          </div>
        </div>
        <div class="card mb-4">
          <div class="card-body">
            <p><strong>Receive by</strong></p>
            <p class="mb-0">#</p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header py-3">
            <h5 class="mb-0">Summary</h5>
          </div>
          <div class="card-body">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center px-0 mb-3">
                Products
                <span id="product-total"></span>
              </li>
              <!-- <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                <div>Coupon Code</div>
                <div class="text-end fs-6">
                  <input class="form-control text-center" type="text" name="" id="" value="">
                  <button class="btn btn-sm text-primary">Apply Coupon</button>
                </div>
              </li> DISCOUNT/PROMO CODES/COUPON-->
            </ul>
            <button type="button" class="btn btn-primary btn-lg btn-block" id="checkout" data-user-id="<?php echo $user_info['id']; ?>">
              Go to checkout
            </button>
          </div>
        </div>
        <div class="card mb-4 mb-lg-0">
          <div class="card-body">
            <p><strong>We accept</strong></p>
            <img class="me-2" width="100px"
            src="/asset/images/payments/cod.png"
            alt="COD" />
            <img class="me-2" width="100px"
              src="/asset/images/payments/gcash.png"
              alt="GCash" />
          </div>
        </div>
      </div>
    </div>
  </div>
</section>