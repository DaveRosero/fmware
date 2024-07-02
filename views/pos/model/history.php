<?php
require_once 'model/database/database.php';

$mysqli = database();


$query = 'SELECT pos.pos_ref,
                 pos.date,
                 pos.total,
                 transaction_type.name,
                 pos.status
            FROM pos
            LEFT JOIN transaction_type ON pos.transaction_type_id = transaction_type.id';

$stmt = $mysqli->prepare($query);
$stmt->execute();
$stmt->bind_result($pos_ref, $date, $total, $name, $status);
while ($stmt->fetch()){
    echo '<tr>
                 <td class="align-middle">' . $pos_ref . '</td>
                 <td class="align-middle">' . date("Y/m/d h:i:sa", strtotime($date)) . '</td>
                 <td class="align-middle">₱' . number_format($total) . '</td>
                 <td class="align-middle">' . $name . '</td>
                 <td class="align-middle ">
                  <span class= "badge text-bg-primary">' . $status . '</span>
                 </td>
                 <td class="align-middle">
                        <button class="btn btn-primary"
                            data-product-id="' . $pos_ref . '"
                            data-bs-target="#historyView" 
                            data-bs-toggle="modal"
                        >
                            View
                        </button>
                    </td>
            </tr>';
}
$stmt->close();