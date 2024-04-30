<?php
    include_once 'session.php';
    require_once 'model/home/shopClass.php';

    $shop = new Shop();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $filter = $_POST['filter'];
        $search = $_POST['search'];

        $brands = $shop->getBrandArray();
        $categories = $shop->getCategoryArray();

        if ($filter === 'all') {
            $html = $shop->searchProduct($search);
        }

        if (in_array($filter, $brands)) {
            $id = $shop->getBrandId($filter);
            $html = $shop->searchProductbyBrand($search, $id);
        }

        if (in_array($filter, $categories)) {
            $id = $shop->getCategoryId($filter);
            $html = $shop->searchProductbyCategory($search, $id);
        }

        if ($html === '') {
            echo '<h3>No Product Found.</h3>';
        } else {
            echo $html;
        }
    } else {
        header('Location: /404');
        exit();
    }
?>