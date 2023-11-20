<?php
class g1_utils
{
    static function g1_hash($text)
    {
        return hash('sha256', $text);
    }
}
class g1_session
{
    static function validate($database)
    {
        if (isset($_SESSION["user_id"])) {
            //check session
            $session_token = session_id();
            $user_id = $_SESSION["user_id"];
            $current_time = time();
            $sql_request_check_validity = "SELECT * FROM sessions WHERE user_id = $user_id and session_token = $session_token and (timeout_timestamp==0 or timeout_timestamp>$current_time)";
            $result = $database->query($sql_request_check_validity);
            $result_count = mysqli_num_rows($result);
            if ($result_count > 0) {
                // valid session
                return true;
            }
            return false;
        }
    }
}
?>