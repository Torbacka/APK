$(document).ready(function(){
    loadTable();
    $("#apkTable").on('click', 'tbody tr', function() {
        
        window.document.location = $(this).data("href");
        
    });
  
});
function loadTable(){
    var deferreds;
    var getData = new QueryData();
    var phpUrl = "php/getList.php";
    if('varugrupp' in getData){
        phpUrl += "?varugrupp="+getData.varugrupp;
    }
    var count = $('#apkTable tr').length-1;
    console.log(count);


    $.getJSON(phpUrl,{numberOfRow: count}, function(result){
        var n = 0;
    
        $.each(result, function(i, field){
                var array = JSON.parse(field);
                var string;
                var color="";
                console.log(array);
                //<tr class="clickable-row" data-href="url://google.se/  " >     
                var string = "<tr  data-href='http://www.systembolaget.se/"+array[0].substring(0,array[0].length-2) +"' >";
                    
                

                string =string.concat("<td>"+(n+1+count)+"</td>");
                //string = string.concat("<td><a href='http://www.systembolaget.se/"+ array[0].substring(0,array[0].length-2) +"'>" + array[0]+"</a></td>");
                for(var i =2; i <array.length-1;i++){
                        if(i===2){
                            string = string.concat("<td>"+ array[i] +", <small>"+array[i+1]+"</small>" +"</td>");
                        }else if(i===3){

                        }else if(i ===7){
                            array[i] *=100;
                            
                            array[i] = roundedToFixed(array[i], 1); 
                            string = string.concat("<td>"+ array[i] +"</td>"); 
                        }else if(i ===8){
                            array[i]= roundedToFixed(array[i], 3);
                            string = string.concat("<td>"+ array[i] +"</td>");
                        }else{
                            string = string.concat("<td>"+ array[i] +"</td>");
                        }
                    
                    
                }
                n++;
                string =string.concat("</tr>");
                $("tbody").append(string);
                
        });
        
    }).done(function(data){
       $('.table').footable({forceRefresh:true});
           
       
    });
   
    jQuery(function($){
           
            $('.table').footable();
            
    });
    
  

      
    function getColor(array,n){
        var color ="";
        if(n%2==0){
            if(array[9].substring(0,2)==="FS" ){
                color = "greenRow";
               
            }
            else{
                 //console.log(array[array.length-1] + "   "+ color);
                color = "redRow";
            }
        }else{
            if(array[9].substring(0,2)==="FS"){
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

}
