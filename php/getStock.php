<?php
	require "configuration.php";
	getStock(1);
	function getStock($artikelid){
		$tuCurl = curl_init();
		curl_setopt($tuCurl, CURLOPT_URL, "http://statistik.uhr.se/api/GetJQueryDataTableResultModel");
		curl_setopt($tuCurl, CURLOPT_POST, 1);
		curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
		//$stringToArray = implode("\",\"",$array);
		$data = '{"TableType":"SelectionRound1","ResultType":"AdmissionPoint","AdmissionRoundId":"HT2015","EducationOrgId":"SU","ProgKurs":"","Search":"","SelCriterionId":"","RecordStart":25,"RecordLength":100,"SortColumnIndex":0,"SortColumnDesc":false,"RequestNumber":93,"Paginate":true}';
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
	}

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
	}*/

?>
