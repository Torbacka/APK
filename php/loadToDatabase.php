<?php
	$start = microtime(true);
	$csvFilepath = "articles.csv";
	$stringXml =  file_get_contents('http://www.systembolaget.se/Assortment.aspx?Format=Xml');
	$xml = simplexml_load_string($stringXml);
	
	

	insertToDatabase($xml);
    



	function insertToDatabase($xml){
	
		$mysqli = mysqli_connect($host,$username, $password,$dbname);
		if(!$mysqli){
				echo "Det här gick ju inte så bra!";
		}
		$mysqli->query("truncate apk2");
		$stmt = $mysqli->prepare("INSERT INTO apk2 (id,name,name2, department,volym,price, alcoholByVolym, apk, status) VALUES (?,?,?,?,?,?,?,?,?)");
		
		$stmt->bind_param("isssdddds", $id, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);
		$char = ",";
		$csvFile ="";
		foreach ( $xml->artikel as $attribute){

			$id=$attribute->nr;
			$name =utf8_decode(deleteComma($attribute->Namn));
			$name2 = utf8_decode(deleteComma($attribute->Namn2));
			$department = utf8_decode(getDep($attribute->Varugrupp));

			$price = floatval($attribute->Pant)+ floatval( $attribute->Prisinklmoms);
			
			//echo var_dump($attribute->Pant);
			
			$volym =  floatval($attribute->Volymiml);
			$alcoholByVolym = calcAlc($attribute->Alkoholhalt);
			
			$apk = calcAPK($price, $volym, $alcoholByVolym);
			$status = $attribute->Sortiment;
			$stmt->execute();
		}
		
	}
	function getDep($str){
		$array = explode(",", $str);
		return $array[0];
	}
	function deleteComma($str){
		$array = explode(",", $str);
		$ret = "";
		foreach ($array as $key) {
			$ret .=$key;
		}
		return $ret;

	}
	function calcAlc($str){
		$array = explode("%", $str);
		return floatval(($array[0]/100));
	}

	
	function calcAPK($price, $volym, $alc){
		if($price=='0'){
			return 0;
		}else{
			return floatval($volym)*floatval($alc)/floatval($price);
		}
	}

	echo $time_elapsed_secs = microtime(true) - $start;


?>