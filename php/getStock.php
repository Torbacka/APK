<?php
	require "configuration.php";
	
	function getStock($artikelid){
		$tuCurl = curl_init();
		curl_setopt($tuCurl, CURLOPT_URL, "http://www.systembolaget.se/api/product/getstockbalance");
		curl_setopt($tuCurl, CURLOPT_POST, 1);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
		//$stringToArray = implode("\",\"",$array);
		$data = '{"productId":'.$artikelid.',"siteIds":["0114"]}';
		curl_setopt($tuCurl, CURLOPT_POSTFIELDS,$data);
		curl_setopt($tuCurl, CURLOPT_HTTPHEADER, array("Content-Type: application/json;charset=utf-8", "Content-length: ".strlen($data)));


		$trueuData1 = curl_exec($tuCurl);
		$arr = json_decode($trueuData1, true);
		var_dump($arr);
	}/*
	
	$i =1;
	foreach ($arr as $key => $value) {
		if($value['StockTextShort'] != "-"){
			var_dump($value);
			$i++;
		}
	}
	if(!curl_errno($tuCurl)){
	  $info = curl_getinfo($tuCurl);
	  echo 'Took ' . $info['total_time'] . ' seconds to send a request to ' . $info['url'];
	} else {
	  echo 'Curl error: ' . curl_error($tuCurl);
	}
	

	if(checkIfStockExist($store)){
		getStockFromDatabase($store);
	}else{
		getStockFromStore($store);
	}*/

	function checkIfStockExist($store){

	}
	function getStockFromDatabase($store){

	}
	function getStockFromStore($store){

	}
	function casheNewStock(){

	}
	getTopProducts();
	//Hämtar top 50 i listan 
	function getTopProducts(){
		$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
		$result = $mysqli->query("SELECT artikelid FROM apk ORDER BY apk DESC LIMIT 50");
		$array = array();
		if($result){
			while($row = mysqli_fetch_array($result)){
				echo $row[0];
	  			getStock($row[0]);
	  		}
		}
		return $array;
	}

?>