<?php
// pos-live-search-products.php

// Include your database connection
require_once 'model/database/database.php';

// Function to fetch product search results
function searchProducts($searchTerm)
{
    $mysqli = database();

    // Prepare your SQL query
    $query = 'SELECT price_list.unit_price,
                     product.image,
                     product.name,
                     product.unit_value,
                     stock.qty,
                     product.id,
                     unit.name AS unit_name,
                     variant.name AS variant_name
              FROM price_list
              INNER JOIN stock ON price_list.product_id = stock.product_id
              INNER JOIN product ON price_list.product_id = product.id
              INNER JOIN variant ON product.variant_id = variant.id
              INNER JOIN unit ON product.unit_id = unit.id
              WHERE product.name LIKE CONCAT("%", ?, "%") OR product.barcode = ?
              ORDER BY product.name ASC';

    // Prepare statement
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        return false;
    }

    // Bind parameters
    $search = "%{$searchTerm}%";
    $stmt->bind_param('ss', $search, $search);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($unit_price, $image, $name, $unit_value, $qty, $id, $unit_name, $variant_name);

    // Fetch results into HTML format
    $output = '';
    while ($stmt->fetch()) {
        $disabled = ($qty == 0) ? 'disabled' : '';
        $output .= '
            <div class="col item-card">
                <div class="card">
                    <img src="asset/images/products/' . $image . '" class="card-img-top" alt="' . $name . '">    
                    <h5 class="card-title p-2 w-50 bg-success text-white"> ₱ ' . number_format($unit_price) . ' </h5>
                    <div class="card-body">
                        <h5 class="card-title">' . $name . ' (' . $variant_name . ')</h5>
                        <div class="item-info">
                            <div class="d-flex justify-content-between">
                                <p> ' . $unit_value . ' ' . strtoupper($unit_name) . '</p>
                                <div class="d-flex">
                                    <p>Stock:</p>
                                    <p> ' . $qty . ' </p>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary cart-button" 
                                data-product-id="' . $id . '"
                                data-product-price="' . $unit_price . '"
                                ' . $disabled . '
                            >
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>';
    }

    // Close statement and database connection
    $stmt->close();
    $mysqli->close();

    return $output;
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure search term is set and not empty
    if (isset($_POST['search']) && !empty($_POST['search'])) {
        $searchTerm = $_POST['search'];
        $searchResults = searchProducts($searchTerm);

        if ($searchResults !== false) {
            echo $searchResults; // Output HTML results
        } else {
            echo 'Error fetching products.';
        }
    } else {
        echo 'Invalid search term.';
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo 'Method not allowed.';
}
?>