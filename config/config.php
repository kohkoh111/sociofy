<?php
ob_start(); //Turns on output buffering
session_start();

$timezone = date_default_timezone_set("Asia/Tokyo");


// ローカル開発環境
//$con = mysqli_connect("localhost", "root", "root", "social",8889); 


// 本番環境用
$servername = "localhost";
$username = "qcxmfjwfwv";
$password = "CxDu9p4bUT";
$db = "qcxmfjwfwv";

//接続用
$con = mysqli_connect($servername, $username, $password,$db);

if(mysqli_connect_errno())
{
	echo "Failed to connect: " . mysqli_connect_errno();
}

?>
