 

  
  jQuery(document).ready(function($){
      "use strict";
      
       $('#amount').change(function(){
           
var amount=document.getElementById('amount').value ;
var fee=document.getElementById('escrow_create_fee').value ;

     var escrow_fee=(fee / 100) * amount;
     
     var total=parseInt(amount) + parseInt(escrow_fee);
   
    alert("Escrow Fee: "+ escrow_fee);
   
document.getElementById("demo").innerHTML = escrow_fee;
document.getElementById("demo1").innerHTML = amount;
document.getElementById("demo2").innerHTML = total;
       });     
 
});


