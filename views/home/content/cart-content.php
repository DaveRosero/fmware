<div class="container">
    <div class="row py-5 text-center">
        <h1 class="fw-semibold">
            <?php
                echo $user_info['fname']." ".substr($user_info['lname'], 0, 1).".'s Cart";
            ?>
        </h1>
    </div>
    <div class="row py-5 text-center">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $cart->getCart($user_info['id']); ?>
            </tbody>
            <tfoot>
                <th class="text-end" colspan="4">Grand Total :</th>
                <th id="cart-total" data-user-id="<?php echo $user_info['id']; ?>"></th>
                <tr>
                    <th class="text-end" colspan="5">
                        <button class="btn btn-danger" id="cart-reset">Cart Reset</button>
                        <button class="btn btn-primary" id="cart-checkout">Checkout</button>
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>