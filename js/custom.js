 

  
  jQuery(document).ready(function($){
      "use strict";
      
       $('#amount').change(function(){
           
var amount=document.getElementById('amount').value ;
var fee=document.getElementById('escrow_create_fee').value ;

     var escrow_fee=(fee / 100) * amount;
     
     var total=parseInt(amount) + parseInt(escrow_fee);
   
    
   
    
     
     
     
document.getElementById("escrow_fee").innerHTML =  Intl.NumberFormat().format(escrow_fee);
document.getElementById("escrow_amount").innerHTML =Intl.NumberFormat().format(amount) ;
document.getElementById("total").innerHTML =  Intl.NumberFormat().format(total);

 $(".feeblock").removeClass("hide");

       });     
 
});


