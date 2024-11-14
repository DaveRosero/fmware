<?php
require_once 'session.php';
require_once 'model/admin/admin.php';

class Dashboard extends Admin
{
    public function getDashboard($sort)
    {
        $current_date = new DateTime();
        switch ($sort) {
            case 'daily':
                $date = $current_date;
                $title = ['0:00', '3:00', '6:00', '9:00', '12:00', '15:00', '18:00', '21:00'];
                $sales_data = $this->salesDaily($date->format('Y-m-d'));
                $orders = $this->getDailyOrders($date->format('Y-m-d'));
                $sales = $this->getDailySales($date->format('Y-m-d'));
                $refunds = $this->getRefundDaily($date->format('Y-m-d'));
                $discount = $this->getDiscountDaily($date->format('Y-m-d'));
                $expenses = $this->getDailyExpenses(date('F j, Y', $date->getTimestamp()));
                break;
            case 'weekly':
                $date = clone $current_date;
                $date->modify('-6 day');
                $start_date = clone $current_date;
                $start_date->modify('-1 week');
                $end_date = clone $current_date;
                $title = $this->getWeeklyDates($date);
                $sales_data = $this->salesWeekly($title);
                $orders = $this->getWeeklyOrders($date->format('Y-m-d'));
                $sales = $this->getWeeklySales($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                $expenses = $this->getWeeklyExpenses($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                $refunds = $this->getRefundWeekly($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                $discount = $this->getDiscountWeekly($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                break;
            case 'monthly':
                $title = $this->getMonthlyTitles($current_date);
                $sales_data = $this->salesMonthly($title);
                $start_date = (new DateTime())->modify('-5 months')->format('Y-m-01');
                $end_date = $current_date->format('Y-m-t');
                $orders = $this->getMonthlyOrders($title);
                $sales = $this->getMonthlySales($title);
                $expenses = $this->getPastSixMonthsExpenses();
                $refunds = $this->getRefundMonthly();
                $discount = $this->getDiscountMonthly();
                break;
            default:
                $date = $current_date;
                break;
        }

        $end_date = date('F t, Y'); // End of the current month
        $start_date = date('F 1, Y', strtotime('-5 months')); // Start of the month 6 months ago

        $json = array(
            'orders' => '₱' . number_format($orders['total'], 2),
            'orders_count' => $orders['count'],
            'sales' => '₱' . number_format($sales['total'], 2),
            'sales_count' => $sales['count'],
            'discounts' => '₱' . number_format($sales['discounts'], 2),
            'expenses' => '₱' . number_format($expenses, 2),
            'line_title' => $title,
            'sales_data' => $sales_data,
            'start_date' => $start_date ?? 0,
            'end_date' => $end_date ?? 0,
            'profit' => '₱' . number_format((($orders['total'] + $sales['total']) - ($expenses + $sales['discounts'] + $refunds)), 2),
            'refunds' => '₱' . number_format($refunds, 2),
            'discount' => '₱' . number_format($discount, 2),
        );
        echo json_encode($json);
        return;
    }

    public function getDailyOrders($date)
    {
        $query = 'SELECT SUM(gross), COUNT(*) FROM orders WHERE DATE(date) = ? AND (status = "delivered" OR paid = "paid")';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total, $count);
        $stmt->fetch();
        $stmt->close();

        return [
            'total' => $total,
            'count' => $count
        ];
    }

    public function getWeeklyOrders($date)
    {
        $query = 'SELECT SUM(gross), COUNT(*) FROM orders 
              WHERE DATE(date) BETWEEN DATE_SUB(?, INTERVAL 1 WEEK) AND ? AND (status = "delivered" OR paid = "paid")';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $date, $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total, $count);
        $stmt->fetch();
        $stmt->close();

        return [
            'total' => $total,
            'count' => $count
        ];
    }

    public function getDailySales($date)
    {
        $query = 'SELECT SUM(total), SUM(discount), COUNT(*) FROM pos WHERE DATE(STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p")) = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total, $discount, $count);
        $stmt->fetch();
        $stmt->close();

        return [
            'total' => $total,
            'discounts' => $discount,
            'count' => $count
        ];
    }

    public function getWeeklySales($start_date, $end_date)
    {
        $query = 'SELECT SUM(total), SUM(discount), COUNT(*) 
              FROM pos 
              WHERE DATE(STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p")) BETWEEN ? AND ?';

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        // Bind parameters - expecting dates in "Y-m-d" format for both start_date and end_date
        $stmt->bind_param('ss', $start_date, $end_date);

        // Execute statement and check for errors
        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
        }

        // Bind result variables and fetch
        $stmt->bind_result($total, $discount, $count);
        $stmt->fetch();
        $stmt->close();

        // Return results
        return [
            'total' => $total,
            'discounts' => $discount,
            'count' => $count
        ];
    }


    public function getDailyExpenses($date)
    {
        $query = 'SELECT SUM(amount) FROM expenses WHERE date = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($expenses);
        $stmt->fetch();
        $stmt->close();

        $query = 'SELECT COALESCE(SUM(poi.actual_price * poi.received), 0)
                FROM purchase_order_items poi 
                INNER JOIN purchase_order po ON po.po_ref = poi.po_ref
                WHERE po.date = ? AND po.status IN (2, 3)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($po_total);
        $stmt->fetch();
        $stmt->close();

        $total = $expenses + $po_total;
        return $total;
    }

    public function getWeeklyExpenses($start_date, $end_date)
    {
        $query = 'SELECT SUM(amount) FROM expenses WHERE STR_TO_DATE(date, "%M %e, %Y") BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($expenses);
        $stmt->fetch();
        $stmt->close();

        $query = 'SELECT COALESCE(SUM(poi.actual_price * poi.received), 0) 
                FROM purchase_order po 
                INNER JOIN purchase_order_items poi ON poi.po_ref = po.po_ref
                WHERE STR_TO_DATE(date_received, "%M %e, %Y") BETWEEN ? AND ?
                AND po.status IN (2, 3)';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($po_total);
        $stmt->fetch();
        $stmt->close();

        $total = $expenses + $po_total;

        return $total;
    }

    public function getWeeklyDates($date)
    {
        $dates = array();
        for ($i = 0; $i < 7; $i++) {
            $dates[] = $date->format('Y-m-d');
            $date->modify('+1 day');
        }

        return $dates;
    }

    public function salesDaily($date)
    {
        $date_time = array(
            ['00:00:00', '03:00:00'],
            ['03:00:00', '06:00:00'],
            ['06:00:00', '09:00:00'],
            ['09:00:00', '12:00:00'],
            ['12:00:00', '15:00:00'],
            ['15:00:00', '18:00:00'],
            ['18:00:00', '21:00:00'],
            ['21:00:00', '23:59:59'],
        );

        $data = array();

        foreach ($date_time as $time) {
            $start_time = $time[0];
            $end_time = $time[1];

            // Query for Orders
            $orders_query = 'SELECT COALESCE(SUM(gross), 0) AS total_orders
                         FROM orders
                         WHERE DATE(date) = ?
                         AND TIME(date) BETWEEN ? AND ?
                         AND (status = "delivered" OR paid = "paid")';
            $stmt = $this->conn->prepare($orders_query);
            $stmt->bind_param('sss', $date, $start_time, $end_time);
            if (!$stmt->execute()) {
                die("Error in executing orders query: " . $stmt->error);
            }
            $stmt->bind_result($total_orders);
            $stmt->fetch();
            $stmt->close();

            // Query for Sales
            $sales_query = 'SELECT COALESCE(SUM(total), 0) AS total_sales
                        FROM pos
                        WHERE DATE(date) = ?
                        AND TIME(date) BETWEEN ? AND ?';
            $stmt = $this->conn->prepare($sales_query);
            $stmt->bind_param('sss', $date, $start_time, $end_time);
            if (!$stmt->execute()) {
                die("Error in executing sales query: " . $stmt->error);
            }
            $stmt->bind_result($total_sales);
            $stmt->fetch();
            $stmt->close();

            // Combine Orders and Sales
            $grand_total = $total_orders + $total_sales;
            $data[] = $grand_total;
        }

        return $data;
    }

    public function salesWeekly($dates)
    {
        $sales = array();
        foreach ($dates as $date) {
            $query = 'SELECT (
                      SELECT COALESCE(SUM(pos.total), 0) 
                      FROM pos 
                      WHERE DATE(pos.date) = ?
                  ) AS total_sales,
                  (
                      SELECT COALESCE(SUM(orders.gross), 0) 
                      FROM orders 
                      WHERE DATE(orders.date) = ?
                      AND (status = "delivered" OR paid = "paid")
                  ) AS total_orders';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $date, $date);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($total_sales, $total_orders);
            $stmt->fetch();
            $stmt->close();
            $sales[] = $total_sales + $total_orders;
        }
        return $sales;
    }

    public function getMonthlyTitles($current_date)
    {
        $titles = array();
        for ($i = 0; $i < 6; $i++) {
            $titles[] = $current_date->format('F Y');
            $current_date->modify('-1 month');
        }
        return array_reverse($titles); // Reverse the order to keep it chronological
    }

    public function salesMonthly($titles)
    {
        $sales = array();
        foreach ($titles as $title) {
            $month = DateTime::createFromFormat('F Y', $title)->format('Y-m');
            $query = 'SELECT (
                      SELECT COALESCE(SUM(pos.total), 0) 
                      FROM pos 
                      WHERE DATE_FORMAT(pos.date, "%Y-%m") = ?
                  ) AS total_sales,
                  (
                      SELECT COALESCE(SUM(orders.gross), 0) 
                      FROM orders 
                      WHERE DATE_FORMAT(orders.date, "%Y-%m") = ?
                      AND (status = "delivered" OR paid = "paid")
                  ) AS total_orders';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('ss', $month, $month);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($total_sales, $total_orders);
            $stmt->fetch();
            $stmt->close();
            $sales[] = $total_sales + $total_orders;
        }
        return $sales;
    }

    public function getMonthlyOrders($titles)
    {
        $total_orders = 0;
        $total_count = 0;

        foreach ($titles as $title) {
            $month = DateTime::createFromFormat('F Y', $title)->format('Y-m');
            $query = 'SELECT COALESCE(SUM(gross), 0), COUNT(*) 
                  FROM orders 
                  WHERE DATE_FORMAT(date, "%Y-%m") = ?
                  AND (status = "delivered" OR paid = "paid")';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('s', $month);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($total, $count);
            $stmt->fetch();
            $stmt->close();

            $total_orders += $total;
            $total_count += $count;
        }

        return [
            'total' => $total_orders,
            'count' => $total_count
        ];
    }

    public function getMonthlySales($titles)
    {
        $total_sales = 0;
        $total_discounts = 0;
        $total_count = 0;

        foreach ($titles as $title) {
            // Convert the month title to 'Y-m' format
            $month = DateTime::createFromFormat('F Y', $title)->format('Y-m');

            // Prepare query with STR_TO_DATE to parse the date from VARCHAR
            $query = 'SELECT COALESCE(SUM(total), 0), COALESCE(SUM(discount), 0), COUNT(*) 
                  FROM pos 
                  WHERE DATE_FORMAT(STR_TO_DATE(pos.date, "%M %d, %Y %h:%i %p"), "%Y-%m") = ?';

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }

            // Bind the month parameter
            $stmt->bind_param('s', $month);

            // Execute statement and check for errors
            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
            }

            // Bind result variables and fetch
            $stmt->bind_result($total, $discount, $count);
            $stmt->fetch();
            $stmt->close();

            // Accumulate totals
            $total_sales += $total;
            $total_discounts += $discount;
            $total_count += $count;
        }

        // Return the accumulated results
        return [
            'total' => $total_sales,
            'discounts' => $total_discounts,
            'count' => $total_count
        ];
    }


    public function getPastSixMonthsExpenses()
    {
        // Calculate the start date (6 months ago) and end date (current date)
        $end_date = date('F t, Y'); // End of the current month
        $start_date = date('F 1, Y', strtotime('-6 months')); // Start of the month 6 months ago

        // Query to sum up expenses for the given date range
        $query = 'SELECT SUM(amount) FROM expenses WHERE STR_TO_DATE(date, "%M %e, %Y") BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($expenses);
        $stmt->fetch();
        $stmt->close();

        // Query to sum up purchase order amounts for the given date range
        $query = 'SELECT COALESCE(SUM(poi.actual_price * poi.received), 0) AS total_price
                FROM purchase_order po
                INNER JOIN purchase_order_items poi ON poi.po_ref = po.po_ref
                WHERE STR_TO_DATE(po.date_received, "%M %e, %Y") 
                BETWEEN STR_TO_DATE(?, "%M %e, %Y") AND STR_TO_DATE(?, "%M %e, %Y")
                AND po.status IN (2, 3);';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($po_total);
        $stmt->fetch();
        $stmt->close();

        $query = 'SELECT COALESCE(SUM(po.shipping), 0) AS total_shipping
                FROM purchase_order po
                WHERE STR_TO_DATE(po.date_received, "%M %e, %Y") 
                BETWEEN STR_TO_DATE(?, "%M %e, %Y") AND STR_TO_DATE(?, "%M %e, %Y")
                AND po.status IN (2, 3);';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($shipping);
        $stmt->fetch();
        $stmt->close();

        $query = 'SELECT COALESCE(SUM(po.others), 0) AS total_shipping
                FROM purchase_order po
                WHERE STR_TO_DATE(po.date_received, "%M %e, %Y") 
                BETWEEN STR_TO_DATE(?, "%M %e, %Y") AND STR_TO_DATE(?, "%M %e, %Y")
                AND po.status IN (2, 3);';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($others);
        $stmt->fetch();
        $stmt->close();

        $total = $expenses + $po_total + $shipping + $others;

        return $total;
    }

    public function getRefundDaily($date)
    {
        $query = 'SELECT SUM(total_refund_value) FROM refunds WHERE DATE(refund_date) = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    public function getRefundWeekly($start_date, $end_date)
    {
        $query = 'SELECT SUM(total_refund_value) FROM refunds WHERE DATE(refund_date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    public function getRefundMonthly()
    { // Calculate the start date (6 months ago) and end date (current date)
        $end_date = date('Y-m-d'); // End of the current month
        $start_date = date('Y-m-d', strtotime('-6 months')); // Start of the month 6 months ago

        // Query to sum up expenses for the given date range
        $query = 'SELECT SUM(total_refund_value) FROM refunds WHERE DATE(refund_date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    public function getDiscountDaily($date)
    {
        $query = 'SELECT SUM(discount) FROM pos WHERE DATE(date) = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    public function getDiscountWeekly($start_date, $end_date)
    {
        $query = 'SELECT SUM(discount) FROM pos WHERE DATE(date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }

    public function getDiscountMonthly()
    { // Calculate the start date (6 months ago) and end date (current date)
        $end_date = date('Y-m-d'); // End of the current month
        $start_date = date('Y-m-d', strtotime('-6 months')); // Start of the month 6 months ago

        // Query to sum up expenses for the given date range
        $query = 'SELECT SUM(discount) FROM pos WHERE DATE(date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

        if (!$stmt) {
            die("Error in preparing statement: " . $this->conn->error);
        }

        if (!$stmt->execute()) {
            die("Error in executing statement: " . $stmt->error);
            $stmt->close();
        }

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

        return $total;
    }
}
