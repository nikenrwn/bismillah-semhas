<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'belajar_google_maps';   //nama database
$koneksi = mysql_connect($host,$user,$pass);

	if(!$koneksi){
		die("Cannot connect to database.");
		}
		
	mysql_select_db($db);

?>

