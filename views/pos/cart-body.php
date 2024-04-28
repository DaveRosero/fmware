<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT pos_cart.product_id,
                    pos_cart.qty,
                    pos_cart.price,
                    pos_cart.discount,
                    product.name
                FROM pos_cart
                INNER JOIN product ON pos_cart.product_id = product.id';
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $qty, $price, $discount, $name);
$tbody = '';
$cart_total = array();
while ($stmt->fetch()) {
    $total_price = $price * $qty;   
    if ($discount != 0) {
        // $discount_value = $total_price * ($discount / 100);
        $total_price -= $discount;
    }
    $tbody .= '<tr>
                    <td class="align-middle">' . $name . '</td>
                    <td class="align-middle">₱' . $price . '</td>
                    <td class="align-middle">
                        <div class="input-group">
                            <button 
                                class="btn btn-sm btn-outline-secondary minus-qty" 
                                type="button"
                                data-product-id="' . $id . '"
                                data-product-qty="' . $qty . '"
                            >
                            <i class="fas fa-minus"></i>
                            </button>
                            <input 
                                type="text" 
                                class="form-control text-center w-20 qty-input" 
                                value="' . $qty . '" 
                                style="max-width: 100px;"
                            >
                            <button 
                                class="btn btn-sm btn-outline-secondary add-qty" 
                                type="button"
                                data-product-id="' . $id . '"
                                data-product-qty="' . $qty . '"
                            >
                            <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="input-group">
                            <input 
                                type="number" 
                                class="form-control text-center w-20 discount" 
                                value="' . $discount . '"
                                style="max-width: 80px;"
                            >
                            <button 
                            class="btn btn-sm btn-outline-secondary apply-discount" 
                            type="button"
                            data-product-id="' . $id . '"
                            data-product-discount="' . $discount . '"
                            >
                            Apply
                            </button>
                        </div>
                    </td>
                    <td class="align-middle">₱' . $total_price . '</td>
                    <td class="align-middle">
                        <button class="btn btn-danger cart-delete"
                            data-product-id="' . $id . '"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>';
    $cart_total[] = $total_price;
}

$stmt->close();

$json = array();
$json['tbody'] = $tbody;
$json['cart_total'] = array_sum($cart_total);
echo json_encode($json);
