<?php
include 'database/database.php';
include 'utilities.php';

//CONSTANTS

$_G1_ROOT = "/home/vol1_1/epizy.com/epiz_24643072/";

$_G1_DATA_PATH = $_SERVER['DOCUMENT_ROOT'].'/../../data.group1.fr/htdocs';
$_G1_MEMBER_PATH = $_SERVER['DOCUMENT_ROOT'].'/../../member.group1.fr/htdocs';
$_G1_MAIN_PATH = $_SERVER['DOCUMENT_ROOT'].'/../../htdocs';
$_G1_LOGIN_URL = "http://group1.fr/login.php";
$_G1_LOGOUT_URL = "http://group1.fr/logout.php";

function g1_hash($text) {
	return hash('sha256', $text);
}

function get_username($user_id) {
    global $g1_db;
    $sql_request = "SELECT user_name FROM members WHERE user_id=?";
    $sql_statement = $g1_db->prepare($sql_request);
    $sql_statement->bind_param("i", $user_id);
    $sql_statement->execute();
    $result = $sql_statement->get_result();
    $usernames = $result->fetch_all(MYSQLI_ASSOC);
    return $usernames[0]["user_name"];
}

function search_with_username($str) {
	global $g1_db;
	$sql_request = "SELECT user_id FROM members WHERE user_name LIKE '%$str%'";
	$result = $g1_db->query($sql_request);

}

function renew_session($session_id) {
	global $g1_db;
	$_SESSION['session_timeout'] = max(time() + 3600, $_SESSION['session_timeout']);
	$sql_request = "UPDATE sessions set timeout_timestamp=".$_SESSION['session_timeout']." where session_id=$session_id";
	$result = $g1_db->query($sql_request);

	// FETCH ALL AND RETURN LIST
}

function require_login($url_after_login='')
{
	// code...
	if (check_session()) {
		// HERE CHECK SESSION DATABASE

	}
	else {
		session_unset();
		session_destroy();
		ini_set('session.cookie_domain', '.group1.fr' );
		ini_set('session.gc_maxlifetime', 31536000);
		session_set_cookie_params(31536000);
		session_start();
		header("Location: https://group1.fr/login.php?redirect=".$url_after_login);
	}
}

function check_session()
{
	global $g1_db;
	if (!isset($_SESSION['session_id']) || !isset($_SESSION['session_timeout']) || time() > $_SESSION['session_timeout']) {
		session_unset();
		session_destroy();
		ini_set('session.cookie_domain', '.group1.fr' );
		ini_set('session.gc_maxlifetime', 31536000);
		session_set_cookie_params(31536000);
		session_start();
		return 0;

	}
	$session_id = $_SESSION['session_id'];
	$start_time = $_SESSION['session_start'];
	$session_timeout = $_SESSION['session_timeout'];
	$userid = $_SESSION['user_id'];

	$sql = "SELECT * FROM sessions where session_id=$session_id and user_id=$userid and start_timestamp=$start_time and timeout_timestamp=$session_timeout";
	$result = $g1_db->query($sql);

	$count = mysqli_num_rows($result);

	if ($count>0) {
		// Okay, we'll consider this user is valid
		renew_session($session_id);
		return 1;
	}
	return 0;
}

function check_permissions($path, $user, $url_after_login="")
{
	// code...
	if (0==1) {

	}
	else {
		header('Location:http://group1.fr/logout.php?redirect=http://group1.fr/login.php?redirect='.$url_after_login);
	}
}

function debug_mode() {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

/*ini_set('display_errors', 'On');
error_reporting(E_ALL);*/

//require_login("https://group1.fr/framework_lib/overall.php");
?>
