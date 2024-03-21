<?php
    require_once 'model/home/home.php';

    class Product extends Home {
        public function getProducts () {
            $conn = $this->getConnection();

            $query = 'SELECT product.id,
                            product.name,
                            product.image,
                            price_list.unit_price 
                    FROM product
                    INNER JOIN price_list ON price_list.product_id = product.id';
            $stmt = $conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image, $price);
                    while ($stmt->fetch()) {
                        echo '<div class="col-md-3">
                                <div class="card">
                                    <img src="asset/images/products/'.$image.'" class="card-img-top mx-auto d-block" alt="" style="width: 200px;">
                                    <div class="card-body">
                                        <h5 class="card-title">'.$name.'</h5>
                                        <p class="card-text">₱'.$price.'</p>
                                        <button 
                                            class="btn btn-primary add-cart"
                                            data-product-id="'.$id.'"
                                        >
                                            <i class="fas fa-shopping-cart"></i> Add to Cart
                                        </button>
                                        <span class="text-danger" id="cart-feedback"></span>
                                    </div>
                                </div>
                            </div>';
                    }
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $conn->error);
            }
        }
    }
?>