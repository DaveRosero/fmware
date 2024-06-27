<?php
    require_once 'model/user/user.php';
    require_once 'model/admin/logsClass.php';

    class Logout extends User {
        public function logout () {
            $logs = new Logs();

            switch ($_SESSION['group']) {
                case 'user':
                    $json['redirect'] = '/';
                    $action_log = 'Customer Logout';
                    break;
                case 'delivery':
                    $json['redirect'] = '/scan-qr';
                    $action_log = 'Delivery Logout';
                    break;
                case 'admin':
                    $json['redirect'] = '/dashboard';
                    $action_log = 'Dashboard Logout';
                    break;
                default:
                    $json['redirect'] = '/pos';
                    $action_log = 'Point of Sale Logout';
                    break;
            }

            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

            session_start();
            session_destroy();
            header('Location: /login');
        }
    }
?>