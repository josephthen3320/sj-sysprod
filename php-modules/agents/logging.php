<?php

    /** Preamble **/
    include $_SERVER['DOCUMENT_ROOT'] . "/php-modules/logging/get_user_information.php";

    /** Utility Functions **/

    function getClientIPAddress() {
        return $_SERVER['REMOTE_ADDR'];
    }

    function getClientUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    function getClientUserAgentComma() {
        return implode(",", explode(";", getClientUserAgent()));
    }

    function getSessionId() {
        session_start();
        return session_id();
    }



    /** Logging Functions **/

    function logLogin($user_id, $status) {

        $numArgs = func_num_args();
        if ($numArgs <=0) { exit(); }

        $args = func_get_args();

        $user_id    = $args[0];
        $status     = $args[1];

        if (isset($args[2])) {
            $username_submit = $args[2];
        } else {
            $username_submit = null;
        }

        // Login successful
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $log_conn = getConnLog();

        $ipaddr     = getClientIPAddress();
        $user_agent = getClientUserAgent();

        switch ($status) {
            case 0:
                $status = "SUCCESS;; USERNAME={$username_submit}";
                break;
            default:
            case 1:
                $status = "FAILED;; USERNAME={$username_submit}";
                break;
            case 2:
                $status = "BLOCKED;; USERNAME={$username_submit}";
                break;

        }

        $log_detail = "LOGIN {$status};; {$user_agent}";

        $log_sql = "INSERT INTO user_activity_log (user_id, activity_id, activity_detail, ip_address)
                        VALUES ('$user_id', '11', '$log_detail', '$ipaddr')";

        $log_conn->query($log_sql);

        $log_conn->close();
    }


    function logLogout($user_id, $u) {

        // Logout success
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $log_conn = getConnLog();

        $ipaddr     = getClientIPAddress();
        $user_agent = getClientUserAgent();


        $log_detail = "LOGOUT;; USERNAME={$u};; {$user_agent}";

        $log_sql = "INSERT INTO user_activity_log (user_id, activity_id, activity_detail, ip_address)
                        VALUES ('$user_id', '12', '$log_detail', '$ipaddr')";

        $log_conn->query($log_sql);

        $log_conn->close();
    }


    function logGeneric($user_id, $activity_id, $details) {
        include_once $_SERVER['DOCUMENT_ROOT'] . "/php-modules/db.php";
        $log_conn = getConnLog();

        $ipaddr     = getClientIPAddress();
        $user_agent = getClientUserAgent();

        $details = reformatDetails($details);

        $log_sql = "INSERT INTO user_activity_log (user_id, activity_id, activity_detail, ip_address)
                        VALUES ('$user_id', '$activity_id', '$details', '$ipaddr')";

        $log_conn->query($log_sql);

        $log_conn->close();
    }


    function reformatDetails($str){
        $tmp = explode(";", $str);
        $str = implode(";;", $tmp);

        return $str;
    }