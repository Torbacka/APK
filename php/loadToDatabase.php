<?php
	$start = microtime(true);
	//Måste ha config filen :P
	require "configuration.php";
	$csvFilepath = "articles.csv";
	//Hämtar all data som finns på systembolagest api sida
	$stringXml =  file_get_contents('http://www.systembolaget.se/Assortment.aspx?Format=Xml');
	//Parsar den så den blir lätt att jobba med
	$xml = simplexml_load_string($stringXml);
	
	

	insertToDatabase($xml);
    


	//Sen skriver jag in alla värden till databasen
	function insertToDatabase($xml){
		//kopplar in till database
			
		if(!$mysqli){
			echo "Det här gick ju inte så bra!";
		}
		//Tömmer tabellen så att den blir nollställd
		$mysqli->query("truncate apk2");
		//sätter upp prepered
		$stmt = $mysqli->prepare("INSERT INTO apk2 (id,name,name2, department,volym,price, alcoholByVolym, apk, status) VALUES (?,?,?,?,?,?,?,?,?)");
		
		$stmt->bind_param("isssdddds", $id, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);
		$char = ",";
		$csvFile ="";
		//Loppar igenom alla artiklar
		foreach ( $xml->artikel as $attribute){

			$id = $attribute->nr;
			$name = utf8_decode(deleteComma($attribute->Namn));
			$name2 = utf8_decode(deleteComma($attribute->Namn2));
			$department = utf8_decode(getDep($attribute->Varugrupp));

			$price = floatval($attribute->Pant)+ floatval( $attribute->Prisinklmoms);
			
			
			
			$volym =  floatval($attribute->Volymiml);
			$alcoholByVolym = calcAlc($attribute->Alkoholhalt);
			
			$apk = calcAPK($price, $volym, $alcoholByVolym);
			$status = $attribute->Sortiment;
			//Kör satsen 
			$stmt->execute();
		}
		
	}
	//Hämtar bara varugrupp och inte annat
	function getDep($str){
		$array = explode(",", $str);
		return $array[0];
	}
	//Tar bort alla komma, 
	function deleteComma($str){
		$array = explode(",", $str);
		$ret = "";
		foreach ($array as $key) {
			$ret .=$key;
		}
		return $ret;
	}
	//gör om decimalformat på procent till vanliga procent
	function calcAlc($str){
		$array = explode("%", $str);
		return floatval(($array[0]/100));
	}

	//Räknar ut apk
	function calcAPK($price, $volym, $alc){
		if($price=='0'){
			return 0;
		}else{
			return floatval($volym)*floatval($alc)/floatval($price);
		}
	}

	echo $time_elapsed_secs = microtime(true) - $start;


?>