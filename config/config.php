<?php
ob_start(); //Turns on output buffering
session_start();

$timezone = date_default_timezone_set("Asia/Tokyo");


// ローカル開発環境
//$con = mysqli_connect("localhost", "root", "root", "social",8889); //Connection variable


// 本番環境用
// $url = parse_url(getenv("CLEARDB_DATABASE_URL"));
// $server = $url["host"];
// $username = $url["user"];
// $password = $url["pass"];
// $db = substr($url["path"], 1);

// $con = mysqli_connect($server, $username, $password, $db);

$servername = "localhost";
$username = "qcxmfjwfwv";
$password = "CxDu9p4bUT";
$db = "qcxmfjwfwv";
// Create connection
$con = mysqli_connect($servername, $username, $password,$db);

if(mysqli_connect_errno())
{
	echo "Failed to connect: " . mysqli_connect_errno();
}

?>
