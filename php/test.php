<?php
	$stock =189080923;
	require "configuration.php";
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
	$stmt = $mysqli->prepare("INSERT INTO stock (storeid,artikleid,stock, monthly, weekly, daily) VALUES (?,?,?,?,?,?)
  								ON DUPLICATE KEY UPDATE stock=".$stock.", monthly=monthly+(stock-".$stock.";");
	$stmt->bind_param("iiiiii", $storeid, $artikelid, $stock, $monthly, $weekly, $daily);

	$storeid =3;
	$artikelid =1234;
	$stock =130;
	$monthly =0;
	$daily=0;
	$weekly =0;
	
	$stmt->execute();


?>