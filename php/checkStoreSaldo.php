<?php
	require "configuration.php";
	$mysqli = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD, DB_NAME);
	
	$departments=array("Röda",
	"Rött%20vin",
	"Vita",
	"Vitt%20vin",
	"Rosévin",
	"Mousserande%20vin",
	"Öl",
	"Blanddrycker",
	"Cider",
	"Alkoholfritt",
	"Aniskryddad%20sprit",
	"Aperitif",
	"Armagnac",
	"Bitter",
	"Brandy%20och%20Vinsprit",
	"Calvados",
	"Cognac",
	"Drinkar%20och%20Cock",
	"Fruktvin",
	"Gin",
	"Gl%C3%B6gg%20och%20Gl%C3%BChwein",
	"Grappa%20och%20Marc",
	"Kryddad%20sprit",
	"Likör",
	"Madeira",
	"Mj%C3%B6d",
	"Okryddad%20sprit",
	"Portvin",
	"Punsch",
	"Rom",
	"Sake",
	"Sherry",
	"Smaksatt%20sprit",
	"Smaksatt%20vin",
	"Tequila%20och%20Mezcal",
	"Vermouth",
	"Whisky",
	"Övrigt%20starkvin");
	$running = true;
	$i = 0;
	$k= 0;
	$h=0;
	for($n=0;$n<count($departments);$n++){
		echo "<br><br>". utf8_decode($departments[$n])."<br><br>";
		while($running){
			$tuCurl = curl_init();
			curl_setopt($tuCurl, CURLOPT_URL, "http://www.systembolaget.se/api/productsearch/search?subcategory=".$departments[$n]."&sortdirection=Ascending&site=0902&fullassortment=0&page=".$i."&nofilters=1");
			curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($tuCurl);
			$arr = json_decode($data, true);
			if(count($arr['ProductSearchResults'])==0){
				$running =false;
			}
			foreach ($arr['ProductSearchResults'] as $key => $value) {
				$storeid = "0902";
				 var_dump(intval($value['ProductNumber']));
				$artikelid =intval( $value['ProductNumber']);
				$stock = intval($value['QuantityText']);
				$monthly = 0;
				$weekly=0;
				$daily =0;
				$stmt = $mysqli->prepare("INSERT INTO stock (storeid,artikleid,stock, monthly, weekly, daily) VALUES (?,?,?,?,?,?)
  								ON DUPLICATE KEY UPDATE stock=".$stock.", monthly=monthly+(stock-".$stock.";");
				$stmt->bind_param("iiiiii", $storeid, $artikelid, $stock, $monthly, $weekly, $daily);
	
				
				$k++;
				$h++;
				$stmt->execute();
			}
			$i++;
		}
		echo "<br>".$k;
		$k=0;
		$running =true;
		$i=0;

	}
	echo "<br>".$h;
?>