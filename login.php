<?php
include 'database/g1_database.php';
session_start();
if (isset($_SESSION["user_session_id"])) {
    //check session
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
const DEFAULT_SESSION_TIMEOUT = 3600;
const PROLONGATED_SESSION_TIMEOUT = 10000;
if (isset($_POST["username"]) and isset( $_POST["password"])) {
    $session_username = $_POST["username"];
    $session_password = $_POST["password"];
    $session_timeout = isset($_POST["keep-connected"])? PROLONGATED_SESSION_TIMEOUT: DEFAULT_SESSION_TIMEOUT;
    $sql = "SELECT user_id,user_name FROM members WHERE user_name = '$session_username' and user_password = '$session_password'";
    $result = $g1_db->query($sql);

    $row = mysqli_fetch_row($result);
    print_r($row);

    echo "test";

    if (isset($_GET["redirect"])) {
        header("Location: ".$_GET["redirect"] ."?");
    }
    else if (isset($_GET["service"])) {
        header("Content-Type: application/json");
        echo json_encode([
            "session_id"=> 12,
            "user_id"=>1
        ]);
        exit;
    }
    else {
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

    <link rel="stylesheet" href="./styles/login.css">
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
        <div id="keep-connected"><input type="checkbox" id="input-keep-connected" name="keep-connected"><label for="input-keep-connected">Keep me connected</label></div>
        <input type="submit" value="Login">
    </form>
    <!--<script src="js/scripts.js"></script>-->
</body>

</html>