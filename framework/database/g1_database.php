<?php 
	$servername ="localhost";
	$username = "group1_g1";
	$password = "o&~8SOTPCoWg";
	$dbname = "group1_g1";
	//CONNECTION
	function conn($servername, $username, $password, $dbname)
	{
		try{
		$conn = new PDO("mysql:host=$servername;$dbname", $username, $password);
		//PDO ERROR MODE EXCEPTION
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			die($e->getMessage());
		}
		return $conn;
	}
	$g1_db = mysqli_connect($servername,$username,$password,$dbname);
	define("G1_DATABASE", $g1_db);
?>