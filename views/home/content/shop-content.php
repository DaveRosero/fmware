<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <h1 class="h2 pb-4">Products</h1>
            <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="/shop/all">
                <div class="d-flex align-items-center">
                    <i class="fas fa-asterisk"></i>
                    <span class="px-2">All</span>
                </div>
            </a>
            <ul class="list-unstyled templatemo-accordion">
                <li>
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-tags"></i>
                            <span class="px-2">Brands</span>
                        </div>
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul class="collapse show list-unstyled pl-3">
                        <?php $shop->showBrands(); ?>
                    </ul>
                </li>
            </ul>
            <ul class="list-unstyled templatemo-accordion">
                <li>
                    <a class="collapsed d-flex justify-content-between h3 text-decoration-none" href="#">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-list"></i>
                            <span class="px-2">Categories</span>
                        </div>
                        <i class="fa fa-fw fa-chevron-circle-down mt-1"></i>
                    </a>
                    <ul class="collapse show list-unstyled pl-3">
                        <?php $shop->showCategories(); ?>
                    </ul>
                </li>
            </ul>
        </div>

        <div class="col-lg-9">
            <div class="row">
                <form id="search-form">
                    <div class="col-md-6 pb-4">
                        <div class="d-flex">
                            <div class="input-group">
                                <input type="hidden" name="filter" value="<?php echo $filter; ?>">
                                <input class="form-control" type="text" name="search" id="search" placeholder="Search products...">
                                <button type="submit" class="btn btn-primary" style="width: 80px;"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="row" id="products">
                <?php $shop->filterProducts($filter, $brands, $categories); ?>
            </div>
        </div>
    </div>
</div>