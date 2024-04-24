<?php
  include_once 'session.php';
  require_once 'model/admin/admin.php';
  require_once 'model/admin/orderClass.php';

  $admin = new Admin();
  $order = new Order();

//   $admin->isAdmin();
  if ($order->checkOrder($order_ref, $code)) {
      header('Location: /404');
      exit();
  }

  if ($order->confirmOrder($order_ref)) {
    echo '<h1>Order Confirmed!</h1>';
  }

?>