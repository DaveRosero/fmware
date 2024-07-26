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
$tbody_modal = '';
$cart_total = array();

while ($stmt->fetch()) {
    $total_price = $price * $qty;

    $tbody .= '<tr style="text-align: center;">
                    <td>' . htmlspecialchars($name) . '</td>
                    <td>' . htmlspecialchars($unit_value) . ' ' . htmlspecialchars(strtoupper($unit)) . '</td>
                    <td>' . htmlspecialchars($variant) . '</td>
                    <td>
                        <div class="input-group input-group-sm" style="justify-content: center; display: flex;">
                            <button 
                                class="btn btn-outline-secondary minus-qty" 
                                type="button"
                                data-product-id="' . htmlspecialchars($id) . '"
                                data-product-qty="' . htmlspecialchars($qty) . '"
                            >
                                <i class="fas fa-minus"></i>
                            </button>
                            <input 
                                type="number" 
                                class="form-control text-center qty-input" 
                                value="' . htmlspecialchars($qty) . '" 
                                id="quantity-input-' . htmlspecialchars($id) . '"
                                style="max-width: 60px; text-align: center;"
                            >
                            <button 
                                class="btn btn-outline-secondary add-qty" 
                                type="button"
                                data-product-id="' . htmlspecialchars($id) . '"
                                data-product-qty="' . htmlspecialchars($qty) . '"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </td>
                    <td class="cart-price">₱' . number_format($total_price, 2) . '</td>
                    <td>
                        <button class="btn btn-danger cart-delete"
                            data-product-id="' . htmlspecialchars($id) . '"
                        >
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>';

    $tbody_modal .= '<tr style="text-align: center;">
                    <td>' . htmlspecialchars($name) . '</td>
                    <td>' . htmlspecialchars($unit_value) . ' ' . htmlspecialchars(strtoupper($unit)) . '</td>
                    <td>' . htmlspecialchars($variant) . '</td>
                    <td>₱' . number_format($price, 2) . '</td>
                    <td>' . htmlspecialchars($qty) . '</td>
                    <td>₱' . number_format($total_price, 2) . '</td>
                </tr>';

    $cart_total[] = $total_price;
}

$stmt->close();

// Assuming a discount value passed from the client, else default to 0
$discount = isset($_POST['discount']) ? floatval($_POST['discount']) : 0;
$total_with_discount = array_sum($cart_total) - $discount;

$json = array();
$json['tbody'] = $tbody;
$json['tbody_modal'] = $tbody_modal;
$json['cart_total'] = number_format($total_with_discount, 2);
echo json_encode($json);
?>