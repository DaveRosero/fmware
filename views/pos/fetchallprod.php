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
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card border-secondary shadow-sm rounded">
                        <img src="asset/images/products/' . $row['image'] . '" class="card-img-top img-fluid" alt="' . htmlspecialchars($row['name']) . '">
                        <div class="card-body d-flex flex-column">
                             <h5 class="card-title text-dark product-name">' . htmlspecialchars($row['name']) . ' <small class="text-muted">(' . htmlspecialchars($row['variant_name']) . ')</small></h5>
                             <p class="card-text">Unit: <strong>' . htmlspecialchars($row['unit_value']) . ' ' . strtoupper($row['unit_name']) . '</strong></p>
                             <p class="card-text">Stock: <span class="badge ' . ($row['qty'] == 0 ? 'bg-danger' : 'bg-success') . '">' . $row['qty'] . '</span></p>
                            <div class="d-grid">
                                 <button class="btn btn-primary btn-lg' . ($disabled ? ' disabled' : '') . ' cart-button" 
                                    data-product-id="' . $row['id'] . '"
                                    data-product-price="' . $row['unit_price'] . '">
                                    <i class="fas fa-cart-plus"></i> Add to Cart
                                 </button>
                             </div>
                        </div>
                        <div class="card-footer text-center">
                            <h5 class="m-0 text-success">₱ ' . number_format($row['unit_price']) . '</h5>
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