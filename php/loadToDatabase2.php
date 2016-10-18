<?php     
	$start = microtime(true);     
	//Måste ha config filen :P     require
		
	require "configuration.php"; 
	$arrContextOptions=array(
    	"ssl"=>array(
        	"verify_peer"=>false,
        	"verify_peer_name"=>false,
    		),
	); 
	$stringXml = file_get_contents('https://www.systembolaget.se/Assortment.aspx?Format=Xml', false, stream_context_create($arrContextOptions));
	//Parsar den så den blir lätt att jobba med     
	$xml = simplexml_load_string($stringXml);
	
	

	insertToDatabase($xml);
    


	//Sen skriver jag in alla värden till databasen
	function insertToDatabase($xml){
		//kopplar in till database
		$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
		if(!$mysqli){
				echo "Det här gick ju inte så bra!";
		}
		//Tömmer tabellen så att den blir nollställd
		$mysqli->query("truncate apk_data");
		//sätter upp prepered
		$stmt = $mysqli->prepare("INSERT INTO apk_data (id,item_id,name,name2, department,volym,price, alcohol_by_volym, apk, status) VALUES (?,?,?,?,?,?,?,?,?,?)");
		

		$stmt->bind_param("iisssdddds", $id,$artikelid, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);
		$char = ",";
		$csvFile ="";
		//Loppar igenom alla artiklar
		foreach ( $xml->artikel as $attribute){

			$id=$attribute->nr;
			$artikelid = $attribute->Artikelid;
			$name =utf8_decode(deleteComma($attribute->Namn));
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
