<div class="container py-5">
    <div class="row text-center pt-3">
        <div class="col-lg-6 m-auto">
            <h1 class="h1">Top Selling Products</h1>
            <p class="text-info"><a class="text-decoration-none" href="/shop/all">Click here to view more</a></p>
        </div>
    </div>
    <div class="row mt-4">
        <?php $product->getProducts(); ?>
    </div>
</div>