<?php

require_once 'model/database/database.php';

// Function to fetch all products from the database
function fetchAllProducts()
{
    $mysqli = database(); // Assuming this function returns your database connection

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
              ORDER BY product.name ASC';

    // Execute query
    $result = $mysqli->query($query);

    // Check if there are products found
    if ($result->num_rows > 0) {
        $output = '';

        // Fetch and generate HTML for each product
        while ($row = $result->fetch_assoc()) {
            $disabled = ($row['qty'] == 0) ? 'disabled' : '';
            $output .= '
                <div class="col item-card">
                    <div class="card">
                        <img src="asset/images/products/' . $row['image'] . '" class="card-img-top" alt="' . htmlspecialchars($row['name']) . '">    
                        <h5 class="card-title p-2 w-50 bg-success text-white"> ₱ ' . number_format($row['unit_price']) . ' </h5>
                        <div class="card-body">
                            <p class="card-title h6">' . htmlspecialchars($row['name']) . ' (' . htmlspecialchars($row['variant_name']) . ')</p>
                            <div class="item-info">
                                <div class="d-flex justify-content-between">
                                    <p> ' . htmlspecialchars($row['unit_value']) . ' ' . strtoupper($row['unit_name']) . '</p>
                                    <div class="d-flex">
                                        <p>Stock:</p>
                                        <p> ' . $row['qty'] . ' </p>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button class="btn btn-primary cart-button" 
                                    data-product-id="' . $row['id'] . '"
                                    data-product-price="' . $row['unit_price'] . '"
                                    ' . $disabled . '
                                >
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        // Free result set
        $result->free();
    } else {
        $output = '<p>No products found.</p>';
    }

    // Close database connection
    $mysqli->close();

    return $output;
}

// Handle AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allProducts = fetchAllProducts();

    // Output HTML response
    echo $allProducts;
} else {
    http_response_code(405); // Method Not Allowed
    echo 'Method not allowed.';
}
?>