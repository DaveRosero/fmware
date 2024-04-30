<?php
    require_once 'model/home/home.php';

    class Shop extends Home {
        public function notFilter ($filter) {
            $brands = $this->getBrandArray();
            $categories = $this->getCategoryArray();

            if ($filter === 'all') {
                return false;
            }

            if (in_array($filter, $brands)){
                return false;
            }

            if (in_array($filter, $categories)){
                return false;
            }

            return true;
        }

        public function showCategories () {
            $query = 'SELECT name FROM category';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($name);
                    $categories = '';
                    while ($stmt->fetch()) {
                        $categories .= '<li>
                                        <i class="fa-solid fa-caret-right me-2"></i>
                                        <a class="text-decoration-none" href="/shop/'.$name.'">'.$name.'</a>
                                    </li>';
                    }
                    $stmt->close();
                    echo $categories;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function showBrands () {
            $query = 'SELECT name FROM brand';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($name);
                    $categories = '';
                    while ($stmt->fetch()) {
                        $categories .= '<li>
                                        <i class="fa-solid fa-caret-right me-2"></i>
                                        <a class="text-decoration-none" href="/shop/'.$name.'">'.$name.'</a>
                                    </li>';
                    }
                    $stmt->close();
                    echo $categories;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getBrandArray () {
            $query = 'SELECT name FROM brand';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($name);
                    $brands = array();
                    while ($stmt->fetch()) {
                        $brands[] = $name;
                    }
                    $stmt->close();
                    return $brands;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCategoryArray () {
            $query = 'SELECT name FROM category';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($name);
                    $categories = array();
                    while ($stmt->fetch()) {
                        $categories[] = $name;
                    }
                    $stmt->close();
                    return $categories;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getBrandId ($filter) {
            $query = 'SELECT id FROM brand WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $filter);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id);
                    $stmt->fetch();
                    $stmt->close();
                    return $id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function getCategoryId ($filter) {
            $query = 'SELECT id FROM category WHERE name = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $filter);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id);
                    $stmt->fetch();
                    $stmt->close();
                    return $id;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function filterProducts ($filter, $brands, $categories) {
            if ($filter === 'all') {
                $this->showProducts();
            }

            if (in_array($filter, $brands)) {
                $this->showFilteredProductsbyBrand($filter);
            }

            if (in_array($filter, $categories)) {
                $this->showFilteredProductsbyCategory($filter);
            }
        }

        public function showProducts() {    
            $query = 'SELECT product.id, product.name, product.image FROM product
                    WHERE EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id) 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    echo $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function showFilteredProductsbyBrand ($filter) {
            $brand_id = $this->getBrandId($filter);
            $query = 'SELECT product.id, product.name, product.image FROM product
                    WHERE EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id)
                    AND product.brand_id = ? 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $brand_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    echo $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function showFilteredProductsbyCategory ($filter) {
            $brand_id = $this->getCategoryId($filter);
            $query = 'SELECT product.id, product.name, product.image FROM product
                    WHERE EXISTS 
                        (SELECT 1 FROM price_list WHERE price_list.product_id = product.id)
                    AND product.category_id = ? 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('i', $brand_id);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    echo $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function searchProduct ($search) {
            $searchTerm = '%'.$search.'%';
            $query = 'SELECT id, name, image 
                    FROM product 
                    WHERE name LIKE ? 
                    AND (SELECT 1 FROM price_list WHERE price_list.product_id = product.id) 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $searchTerm);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    return $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function searchProductbyBrand ($search, $filter) {
            $searchTerm = '%'.$search.'%';
            $query = 'SELECT id, name, image 
                    FROM product 
                    WHERE name LIKE ?
                    AND brand_id = ? 
                    AND (SELECT 1 FROM price_list WHERE price_list.product_id = product.id) 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $searchTerm, $filter);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    return $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }

        public function searchProductbyCategory ($search, $filter) {
            $searchTerm = '%'.$search.'%';
            $query = 'SELECT id, name, image 
                    FROM product 
                    WHERE name LIKE ?
                    AND category_id = ? 
                    AND (SELECT 1 FROM price_list WHERE price_list.product_id = product.id) 
                    GROUP BY product.name';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $searchTerm, $filter);
            if ($stmt) {
                if ($stmt->execute()) {
                    $stmt->bind_result($id, $name, $image);
                    $products = '';
                    while ($stmt->fetch()) {
                        $products .= '<div class="col-md-4">
                                        <div class="card mb-4 shadow-sm">
                                            <a href="/view-product/product/'.$id.'">
                                                <img src="/asset/images/products/'.$image.'" class="card-img-top" alt="'.$name.'">
                                            </a>
                                            <div class="card-body">
                                                <h5 class="card-title">'.$name.'</h5>
                                            </div>
                                        </div>
                                    </div>';
                    }
                    $stmt->close();
                    return $products;
                } else {
                    die("Error in executing statement: " . $stmt->error);
                    $stmt->close();
                }
            } else {
                die("Error in preparing statement: " . $this->conn->error);
            }
        }
    }

?>