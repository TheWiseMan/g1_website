<?php
include 'database/g1_database.php';
include 'framework.php';
const DEFAULT_SESSION_TIMEOUT = 3600;
const PROLONGATED_SESSION_TIMEOUT = 10000;
const SESSION_STATUS_VALID = 1;
const SESSION_STATUS_INVALID = 0;
$current_session_status = SESSION_STATUS_INVALID;
session_set_cookie_params(315_360_000, '/', '.group1.fr');
session_start();
$temp = g1_session::validate($g1_db);
if ($temp[0] === TRUE) {
    $temp_session = $temp[1];
    //print_r($temp_session);
    $session_id = session_id();
    $user_id = $temp_session[1];
    $service = $temp_session[5]?:"";
    $session_end = $temp_session[3];
    $session_end = $temp_session[4];
    $current_session_status = SESSION_STATUS_VALID;
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_POST["username"]) and isset($_POST["password"])) {
    $session_username = $_POST["username"];
    $session_password = g1_utils::g1_hash($_POST["password"]);
    $session_timeout = isset($_POST["keep-connected"]) ? PROLONGATED_SESSION_TIMEOUT : DEFAULT_SESSION_TIMEOUT;
    $sql = "SELECT user_id,user_name FROM members WHERE user_name = '$session_username' and user_password = '$session_password'";
    $sql_result = $g1_db->query($sql);
    $row = mysqli_fetch_row($sql_result);
    $result_count = mysqli_num_rows($sql_result);
    if ($result_count < 1) { // no user found
        g1_session::purge();
        $current_session_status = SESSION_STATUS_INVALID;
        header("Location: ?invalid&" . $_SERVER['QUERY_STRING']);
        exit;
    }
    $session_id = session_id();
    $session_start = time();
    $session_end = time() + $session_timeout;
    $user_id = $row[0];
    $user_name = $row[1];
    $service = $_GET["service"]?:"group1";

    $sql_request_create_session = "INSERT INTO `sessions` (`session_id`, `user_id`, `session_token`, `start_timestamp`, `timeout_timestamp`, `service`) VALUES (NULL, '$user_id', '$session_id', '$session_start', '$session_end', '$service')";

    if ($g1_db->query($sql_request_create_session) === TRUE) {
        $_SESSION["user_id"] = $user_id;
        $_SESSION["user_name"] = $user_name;
        $_SESSION["session_start"] = $session_start;
        $_SESSION["session_end"] = $session_end;
        $current_session_status = SESSION_STATUS_VALID;
    }
}
if ($current_session_status == SESSION_STATUS_VALID) {
    if (isset($_GET["redirect"])) {
        header("Location: " . $_GET["redirect"] . "?session=$session_id&user=$user_id");
    } else if (isset($_GET["service"])) {
        header("Content-Type: application/json");
        echo json_encode([
            "session_id" => $session_id,
            "user_id" => $user_id,
            "session_start" => $session_start,
            "session_end" => $session_end,
            "service"=>$service
        ]);
        exit;
    } else {
        echo "success.";
    }
}
//header("Content-Type: application/json");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Group1 - Login</title>
    <meta name="description" content="Login">
    <meta name="author" content="WiseMan">

    <meta property="og:title" content="Group1 Login">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.group1.fr">
    <meta property="og:description" content="The new group1 login page">
    <meta property="og:image" content="image.png">

    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <!--<link rel="icon" type="image/png" href="favicon.png"/>-->
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet" href="../styles/login.css">
</head>

<body>
    <!--	CONTENT...	-->


    <?php
    if (isset($_GET["service"])) {
        echo "<h1><span class='service-name'>" . $_GET["service"] . "</span><br>needs you to login with Group1</h1>";
    } else {
        echo "<h1>Group1 Login</h1>";
    }
    ?>
    <form id="login-form" method="post" action="">
        <input type="username" id="input-username" placeholder="USERNAME" name="username">
        <input type="password" id="input-password" placeholder="PASSWORD" name="password">
        <div id="keep-connected"><input type="checkbox" id="input-keep-connected" name="keep-connected"><label
                for="input-keep-connected">Keep me connected</label></div>
        <input type="submit" value="Login">
    </form>
    <!--<script src="js/scripts.js"></script>-->
</body>

</html>