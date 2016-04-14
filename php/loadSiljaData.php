<?php

	require "configuration.php"; 
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
	if(!$mysqli){
			echo "Det här gick ju inte så bra!";
	}
	//Tömmer tabellen så att den blir nollställd
	$mysqli->query("truncate apk_silja");
	$mysqli->close();

	$allMildProducts = getAlcArray("mild");
	insertToDatabase($allMildProducts);	

	$allStrongProducts = getAlcArray("strong");
	insertToDatabase($allStrongProducts);	
	
	
	function getAlcArray($type){
		$allProducts = array();
		$url = "http://shopping.tallink.com/api/catalog/sv/TUR-STO/".$type."_alcohol?asc=true&manualSortingEnabled=false&orderBy=brandName&page=";
		//Hämtar första sidan med produkter och räknar ut hur många sidor som är kvar
		$dataUrl = $url . 0;
		$oneProductPage = json_decode(file_get_contents($dataUrl));
		array_push($allProducts, $oneProductPage->catalogItems);
		//var_dump($oneProductPage);
		$numberOfPages = intval($oneProductPage->totalItems/22)+1;

		//Loopar igenom resten av sidorna.
		for ($i=1; $i < $numberOfPages; $i++) { 
			$dataUrl = "";
			$dataUrl = $url .$i;
			$oneProductPage = json_decode(file_get_contents($dataUrl));
			array_push($allProducts, $oneProductPage->catalogItems);
			
		}
		return $allProducts;
	}

	function insertToDatabase($complatePageArray){
		//kopplar in till database
		

		$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
		if(!$mysqli){
				echo "Det här gick ju inte så bra!";
		}
		
		//sätter upp prepered
		$stmt = $mysqli->prepare("INSERT INTO apk_silja (id,item_id,name,name2, department,volym,price, alcohol_by_volym, apk, status) VALUES (?,?,?,?,?,?,?,?,?,?)");

		if(!$stmt){
			echo "Det gick inte så bra att göra prepare";
		}
		$stmt->bind_param("iisssdddds", $id,$artikelid, $name, $name2, $department, $volym, $price, $alcoholByVolym, $apk, $status);

		//Loppar igenom alla artiklar
		foreach ( $complatePageArray as $pageArray){

			foreach ($pageArray as $value) {
				$id=$value->id;
				$artikelid = $value->siljaOscarCode;
				if (isset($value->brandName)) {
					$name = utf8_decode($value->brandName);
				}else{
					$name = "";
				}
				if(isset($value->itemCatalogName)){
					$name2 = utf8_decode($value->itemCatalogName);
				}else{
					$name2 = "";
				}
				if(isset($value->productSpecificationTranslated)){
					$department = utf8_decode($value->productSpecificationTranslated);
				}else{
					echo $value->brandName."\n";
				}
				//$department = $value->productSpecificationTranslated;
				$price = $value->lowestPrice;
				if(strpos($value->size, '*')){
					$volym = getVolym($value->size);
				}else{
					$volym =$value->size;
				}
				
				$alcoholByVolym = $value->alc;
				$apk = calcAPK($price, $volym, $alcoholByVolym);
				if(isset($value->campaignType)){
					$status = $value->campaignType;
				}else{
					$status = "";
				}
				$stmt->execute();
			}
		}
	}

	//Gör om en sträng av 24cl*33st till volym
	function getVolym($string){
		$array = explode("*", $string);
		return $array[0] * $array[1];
	}
	//gör procent till decimalformat 
	function procentToDecimal($float){
		
		return $float/100;
	}

	//Räknar ut apk
	function calcAPK($price, $volym, $alc){
		if($price=='0'){
			return 0;
		}else{
			return floatval($volym)*10*floatval(procentToDecimal($alc))/floatval($price);
		}
	}
?>
