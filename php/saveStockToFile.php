<?php
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://www.systembolaget.se/api/productsearch/search?subcategory=R%C3%B6tt%20vin&sortdirection=Ascending&site=0902&fullassortment=0");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
	$output = curl_exec($curl);

	$data = json_decode($output);
//	var_dump($data);
	$times =(($data->Metadata->DocCount)/10)+1;
	echo "\n";
	$products = array();
	var_dump($data);
	array_push($products, $data->ProductSearchResults->ProductsSearchResults);
	for($i =0;$i<$times;$i++){
		curl_setopt($curl, CURLOPT_URL, 'http://www.systembolaget.se/api/productsearch/search?subcategory=R%C3%B6tt%20vin&sortdirection=Ascending&site=0902&fullassortment=0&page='.$i);
		$data = json_decode(curl_exec($curl));
		array_push($products, $data->ProductSearchResults);
		echo $i."\n";
	}
	//var_dump($products);
	$myfile = fopen("output.txt", "w") or die("Unable to open file!");
	ftruncate ($myfile , 0 );
	//var_dump($products);
	$lines =0;
	foreach($products as $array){
		foreach($array as $value){
			$txt  = $value->ProductId."," .$value->ProductNameBold. "," .  $value->Category . "," . $value->QuantityText ."\n";
			//var_dump($value);
			fwrite($myfile, $txt);
			$lines++;
		}
	}
	fwrite($myfile, $lines);
	fclose($myfile);

?>
