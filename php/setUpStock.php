<?php
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);

	$result = $mysqli->query("SELECT artikelid FROM apk ORDER BY apk DESC LIMIT 50");
	if($result){
		while($row = mysqli_fetch_array($result)){
			

		}
	}
?>