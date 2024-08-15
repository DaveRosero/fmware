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
                $expenses = $this->getDailyExpenses(date('F j, Y', $date->getTimestamp()));
                break;
            case 'weekly':
                $start_date = clone $current_date;
                $start_date->modify('-1 week');
                $end_date = clone $current_date;
                $title = $this->getWeeklyDates($start_date);
                $sales_data = [1, 2, 3, 4, 5, 6, 7];
                $orders = $this->getWeeklyOrders();
                $sales = $this->getWeeklySales($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                $expenses = $this->getWeeklyExpenses($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));
                break;
            case 'monthly':
                $current_date->modify('-1 month');
                $date = $current_date;
                break;
            default:
                $date = $current_date;
                break;
        }

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
            'end_date' => $end_date ?? 0
        );
        echo json_encode($json);
        return;
    }

    public function getDailyOrders($date)
    {
        $query = 'SELECT SUM(gross), COUNT(*) FROM orders WHERE DATE(date) = ?';
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

    public function getWeeklyOrders()
    {
        $query = 'SELECT SUM(gross), COUNT(*) FROM orders 
              WHERE DATE(date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND CURDATE()';
        $stmt = $this->conn->prepare($query);

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
        $query = 'SELECT SUM(total), SUM(discount), COUNT(*) FROM pos WHERE DATE(date) = ?';
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
        $query = 'SELECT SUM(total), SUM(discount), COUNT(*) FROM pos WHERE DATE(date) BETWEEN ? AND ?';
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $start_date, $end_date);

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

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

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

        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();

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

            $query = 'SELECT DATE_FORMAT(o.date, "%H:%i") AS time,
                        SUM(o.gross) AS total_orders,
                        COALESCE(SUM(p.total), 0) AS total_sales
                    FROM orders o
                    LEFT JOIN pos p
                    ON DATE(o.date) = DATE(p.date)
                    AND TIME(p.date) BETWEEN ? AND ?
                    WHERE DATE(o.date) = ?
                    AND TIME(o.date) BETWEEN ? AND ?
                    GROUP BY DATE(o.date), DATE_FORMAT(o.date, "%H:%i")
                    ORDER BY DATE(o.date), time';
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param('sssss', $start_time, $end_time, $date, $start_time, $end_time);

            if (!$stmt) {
                die("Error in preparing statement: " . $this->conn->error);
            }

            if (!$stmt->execute()) {
                die("Error in executing statement: " . $stmt->error);
                $stmt->close();
            }

            $stmt->bind_result($time, $total_orders, $total_sales);
            $grand_total = 0;
            while ($stmt->fetch()) {
                $grand_total += $total_orders;
            }
            $stmt->close();

            $data[] = $grand_total;
        }

        return $data;
    }
}
