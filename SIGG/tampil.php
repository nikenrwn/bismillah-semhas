<?php

header('Content-Type: application/json');

$link = mysql_connect('localhost','root','');
mysql_select_db('belajar_google_maps', $link);

$posisi = explode(',', trim(urldecode($_GET['posisireklame'])));

$sql = "SELECT id_reklame, SIPR, alamat, lat_reklame, long_reklame,
		(6371 * acos(cos(radians(".$posisi[0].")) 
		* cos(radians(lat_reklame)) * cos(radians(long_reklame) 
		- radians(".$posisi[1].")) + sin(radians(".$posisi[0].")) 
		* sin(radians(lat_reklame)))) 
		AS jarak 
		FROM tb_reklame2
		HAVING jarak <= ".$_GET['jarak']." 
		ORDER BY jarak";

$data   = mysql_query($sql);
$json   = array();
$output = array();
$i = 0;

if (!empty($data)) {
	$json = '{"data": {';
	$json .= '"niken":[ ';
	while($x = mysql_fetch_array($data)){
	    $json .= '{';
	    $json .= '"id_reklame":"'.$x['id_reklame'].'",
	    		 "SIPR":"'.htmlspecialchars_decode($x['SIPR']).'",
	    		 "alamat":"'.htmlspecialchars_decode($x['alamat']).'",
			     "lat_reklame":"'.$x['lat_reklame'].'",
			     "long_reklame":"'.$x['long_reklame'].'",
			     "jarak":"'.$x['jarak'].'"
	             },';
	}
 
	$json = substr($json,0,strlen($json)-1);
	$json .= ']';
	$json .= '}}';
	 
	echo $json;
} 
?>