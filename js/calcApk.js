$(document).ready(function(){
	$(".submit").click(function(){
		var volym = $(".volym").val();
		
		var alc = $(".alc").val();
		
		var price = $(".price").val();
		
		var apk = (volym*(alc/100))/price;
		
		$(".apk-value").html(roundedToFixed(apk, 3));
	});
	function roundedToFixed(_float, _digits){
  		var rounder = Math.pow(10, _digits);
	  	return (Math.round(_float * rounder) / rounder).toFixed(_digits);
	}
	
});