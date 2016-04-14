<?php
	require "configuration.php";
	if(isset($_GET['numberOfRow'])){
		$numberOfRow = json_decode($_GET['numberOfRow']);
	}
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno()){
  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
  	$array = array();
  	//Om det finns en varugrupp i _GET så ska man ta hänsyn till den
  	if(!empty($_GET['varugrupp'])){
  		$department =utf8_decode($_GET['varugrupp']);
  		$stmt;
  		//Om det är vin så hämtar vi alla vin relaterade varor
  		if($department==="Vin"){
  			$redWine = utf8_decode('Rött vin');
  			$stmt = $mysqli->prepare("SELECT * FROM apk_data WHERE department in ('Vitt vin',
  				'Glogg och Gluhwein','Fruktvin','Rott vin','Madeira','Ovrigt starkvin',
  				'Portvin','Mousserande vin','Rosevin') ORDER BY apk DESC LIMIT ?,50");
  				$stmt->bind_param("i", $numberOfRow);
			$array = addToArray($stmt);
  		}
  		//Om det är sprit hämtar vi alla sprit relaterade varor
  		else if($department==="Sprit"){
  			$stmt = $mysqli->prepare("SELECT * FROM apk_data WHERE department in 
  				('Aniskryddad sprit','Armagnac', 'Brandy och Vinsprit', 'Calvados', 'Cognac'
  					,'Genever', 'Gin', 'Grappa och Marc','Kryddad sprit','Likor','Okryddad sprit'
  				,'Ovrig sprit','Rom','Whisky' ) ORDER BY apk DESC LIMIT ?,50");
  			$stmt->bind_param("i", $numberOfRow);
			$array = addToArray($stmt);
  		}else if($department === "Silja"){
        $stmt = $mysqli->prepare("SELECT * FROM apk_silja ORDER BY apk DESC LIMIT ?,50");
        $stmt->bind_param("i", $numberOfRow);
        $array = addToArray($stmt);
      }else if($stmt = $mysqli->prepare("SELECT * FROM apk_data WHERE department = ? ORDER BY apk DESC LIMIT ?,50")){
	  		$stmt->bind_param("si", $department,$numberOfRow);
		   	$array = addToArray($stmt);
		 }
  	}
  	//Om det inte finns en varugrupp så behöver man inte ta hänsyn till den
  	else{
  		//Hämtar top 50 värden från tabellen apk2
  		$stmt = $mysqli->prepare("SELECT * FROM apk_data ORDER BY apk DESC LIMIT ?, 50");
  		$stmt->bind_param("i", $numberOfRow);
  		$array = addToArray($stmt);
  	}
  	//Stänger kopplingen till databasen
	mysqli_close($mysqli);
  
  	//Skickar arrayen som json
  	echo json_encode($array);
	
  	function addToArray($stmt){
  		$array = array();
  		if ($stmt->execute()){
  			$stmt->bind_result($id,$artikelid, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);
	  		
	  		
	  		while($row = $stmt->fetch()){
	  			$temp = array(utf8_encode($id),utf8_encode($artikelid), utf8_encode($name), utf8_encode($name2), utf8_encode($department),
	  			               utf8_encode($volym), utf8_encode($price), utf8_encode($alcoholByVolym), utf8_encode($apk),utf8_encode($status) );

	  			array_push($array, json_encode($temp));
	  		}
	    }
	    return $array;
  	}
?>
