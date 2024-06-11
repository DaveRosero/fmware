<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT pos_cart.product_id,
                    pos_cart.qty,
                    pos_cart.price,
                    product.name,
                    product.unit_value,
                    unit.name,
                    variant.name
                FROM pos_cart
                INNER JOIN product ON pos_cart.product_id = product.id
                INNER JOIN variant ON variant.id = product.variant_id
                INNER JOIN unit ON unit.id = product.unit_id';
$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($id, $qty, $price, $name, $unit_value, $unit, $variant);
$tbody = '';
$cart_total = array();
while ($stmt->fetch()) {
    $total_price = $price * $qty;   
    $tbody .= '<tr>
                    <td class="align-middle">' . $name . '</td>
                    <td class="align-middle">' . $unit_value . ' ' . strtoupper($unit) .'</td>
                    <td class="align-middle">' . $variant . '</td>
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
                                style="max-width: 50px;"
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
