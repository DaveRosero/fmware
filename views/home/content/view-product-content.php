<!-- Open Content -->
<section class="bg-light">
    <div class="container pb-5">
        <div class="row">
            <div class="col-lg-5 mt-5">
                <div class="card mb-3">
                    <img class="card-img img-fluid" src="/fmware/asset/images/products/<?php echo $product_info['image']; ?>" alt="Product Image" id="product-detail">
                </div>
            </div>
            <!-- col end -->
            <div class="col-lg-7 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h2"><?php echo $product_info['name']; ?></h1>
                        <p class="h3 py-2"><?php echo $product->getPrice($product_info['name']); ?></p>
                        <div class="row mb-2">
                            <h6>Brand: <span class="text-muted"><?php $product->getBrand($product_info['brand_id']); ?></span></h6>
                            <h6>Category: <span class="text-muted"><?php $product->getCategory($product_info['category_id']); ?></span></h6>
                        </div>

                        <h6>Description:</h6>
                        <p><?php echo isset($product_info['description']) && empty($product_info['description']) ? 'N/A' : $product_info['description']; ?></p>

                        <div class="row pb-3">
                            <div class="col d-grid">
                                <button type="submit" class="btn btn-success btn-lg" name="submit" value="buy">Buy</button>
                            </div>
                            <div class="col d-grid">
                                <button type="submit" class="btn btn-success btn-lg add-to-cart-btn" name="submit" value="addtocard">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Close Content -->