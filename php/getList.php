<?php
	require "configuration.php";
	
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
	if (mysqli_connect_errno()){
  		echo "Failed to connect to MySQL: " . mysqli_connect_error();
  	}
  	$array = array();
  	 
  	if(!empty($_GET['varugrupp'])){
  		$department =utf8_decode($_GET['varugrupp']);
  		$stmt;
  		if($department==="Vin"){
  			
  			$redWine = utf8_decode('Rött vin');
  			$stmt = $mysqli->prepare("SELECT *FROM apk2 WHERE department in ('Vitt vin',
  				'Glogg och Gluhwein','Fruktvin','Rott vin','Madeira','Ovrigt starkvin',
  				'Portvin','Mousserande vin','Rosevin') ORDER BY apk DESC LIMIT 200");

  		}
  		else if($department==="Sprit"){
  			$stmt = $mysqli->prepare("SELECT *FROM apk2 WHERE department in 
  				('Aniskryddad sprit','Armagnac', 'Brandy och Vinsprit', 'Calvados', 'Cognac'
  					,'Genever', 'Gin', 'Grappa och Marc','Kryddad sprit','Likor','Okryddad sprit'
  				,'Ovrig sprit','Rom','Whisky' ) ORDER BY apk DESC LIMIT 200");
  			
  		}
  		else if($stmt = $mysqli->prepare("SELECT *FROM apk2 WHERE department = ? ORDER BY apk DESC LIMIT 200")){
	  		
	  		$stmt->bind_param("s", $department);
	  		
	    }
	    $array = addToArray($stmt, $department);
	    
  	}else{

  		$result = $mysqli->query("SELECT * FROM apk2 ORDER BY apk DESC LIMIT 200");
  		
	  	if($result){
	  		while($row = mysqli_fetch_array($result)){
	  			$temp = array();
	  			$i=0;
	  			foreach ($row as $key ) {
	  				if($i%2===0)
	  					array_push($temp,utf8_encode($key));
	  				$i++;
	  			}
	  			array_push($array, json_encode($temp));
	  		}

	  	}
  	}
	mysqli_close($mysqli);
  
  
  	echo json_encode($array);
	
  	function addToArray($stmt){
  		$array = array();
  		if ($stmt->execute()){
  			$stmt->bind_result($id, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);
	  		
	  		
	  		while($row = $stmt->fetch()){
	  			$temp = array(utf8_encode($id), utf8_encode($name), utf8_encode($name2), utf8_encode($department),
	  			               utf8_encode($volym), utf8_encode($price), utf8_encode($alcoholByVolym), utf8_encode($apk),utf8_encode($status) );

	  			array_push($array, json_encode($temp));
	  		}
	    }
	    return $array;
  	}
?>
