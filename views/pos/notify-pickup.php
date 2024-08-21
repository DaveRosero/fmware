<?php
require_once 'model/database/database.php';

$mysqli = database();

$query = 'SELECT COUNT(*) FROM orders WHERE status = "pending"';
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("Error in preparing statement: " . $mysqli->error);
}

if (!$stmt->execute()) {
    die("Error in executing statement: " . $stmt->error);
}

$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

if ($count > 0) {
    echo '
          <a href="#pickup-searchModal" class="dropdown-item d-flex justify-content-between align-items-center" data-bs-toggle="modal" id="pending-orders-link">
              <span>
                  <i class="bi bi-hourglass-split"></i>
                  Pending Pick-Up Orders
              </span>
              <span class="text-secondary fs-7 ms-2">'.$count.'</span>
          </a>';
}
?>
