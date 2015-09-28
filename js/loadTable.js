$(document).ready(function(){
    var deferreds;
	var getData = new QueryData();
	var phpUrl = "php/getList.php";
	if('varugrupp' in getData){
		phpUrl += "/?varugrupp="+getData.varugrupp;
	}
    
	
	$.getJSON(phpUrl,null, function(result){
		var n = 0;
	
        $.each(result, function(i, field){
        		var array = JSON.parse(field);
        		var string;
                var color="";
                //<tr class="clickable-row" data-href="url://google.se/" >
        		
                var string = "<tr class='clickable-row "+getColor(array,n)+"' data-href='http://www.systembolaget.se/"+array[0].substring(0,array[0].length-2)+"'>";
                    
        		

        		string =string.concat("<td>"+(n+1)+"</td>");
        		//string = string.concat("<td><a href='http://www.systembolaget.se/"+ array[0].substring(0,array[0].length-2) +"'>" + array[0]+"</a></td>");
        		for(var i =1; i <array.length-1;i++){
        				if(i ===6){
        					array[i] *=100;
        					
        					array[i] = roundedToFixed(array[i], 1);	 
        				}
        				if(i ===7){
        					array[i]= roundedToFixed(array[i], 3);
        				}
        				string = string.concat("<td>"+ array[i] +"</td>");
        			
        			
        		}



                n++;
        		string =string.concat("</tr>");
            	$("tbody").append(string);
            	
        });
    
      
       
        
    });
   
   
    
  
    $("table").trigger("update");
      
    function getColor(array,n){
        var color ="";
        if(n%2==0){
            if(array[8].substring(0,2)==="FS" ){
                color = "greenRow";
               
            }
            else{
                 //console.log(array[array.length-1] + "   "+ color);
                color = "redRow";
            }
        }else{
            if(array[8].substring(0,2)==="FS"){
              color = "darkGreenRow";
            }
            else{
                //console.log(array[array.length-1] + "   "+ color);
                color = "darkRedRow";
            }
        }
        return color;
        

    }
    function roundedToFixed(_float, _digits){
  		var rounder = Math.pow(10, _digits);
	  	return (Math.round(_float * rounder) / rounder).toFixed(_digits);
	}
	function $_GET(q,s) {
    	s = (s) ? s : window.location.search;
    	var re = new RegExp('&amp;'+q+'=([^&amp;]*)','i');
    	return (s=s.replace(/^\?/,'&amp;').match(re)) ?s=s[1] :s='';
	}
	function encode_utf8( s ) {
  		return unescape( encodeURIComponent( s ) );
	}
});
