<?php
    require_once 'model/user/user.php';
    require_once 'model/admin/logsClass.php';

    class Logout extends User {
        public function logout () {
            $logs = new Logs();

            $action_log = 'Logout';
            $date_log = date('F j, Y g:i A');
            $logs->newLog($action_log, $_SESSION['user_id'], $date_log);

            session_start();
            session_destroy();
            header('Location: /login');
        }
    }
?>