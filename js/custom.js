

jQuery(document).ready(function ($) {
 

	$('#amount').change(function () {

		var amount = document.getElementById('amount').value;
		var currency = document.getElementById('aistore_escrow_currency').value;
		var fee = document.getElementById('escrow_create_fee').value;

		var escrow_fee = (fee / 100) * amount;

		var total = (amount) - parseInt(escrow_fee);

// alert(currency);
 jQuery('#currency').val(currency);
        //  $("#aistore_escrow_currency").val($('#mySelect').val());
        document.getElementById("escrow_currency").innerHTML = currency;
		document.getElementById("escrow_fee").innerHTML = Intl.NumberFormat().format(escrow_fee);
		document.getElementById("escrow_amount").innerHTML = Intl.NumberFormat().format(amount);
		document.getElementById("total").innerHTML = Intl.NumberFormat().format(total);

		$(".feeblock").removeClass("hide");

	});
	


});


jQuery(document).ready(function($) {
    
	$('.dropzone').on('submit', function(e) {
	    alert("upload files");
		e.preventDefault();
		var $form = $(this);
	
		$.post($form.attr('action'), $form.serialize(), function(data) {
			alert('This is data returned from the server ' + data);
		}, 'json');
				
	});
		
 
		
});
 
jQuery(document).ready(function($) {
    
		  if (document.getElementById('escrow_id') !==null) {
     console.log('it exists!');

	
var escrow_id = document.getElementById('escrow_id').value;
   var $form = $(this);
	  $.ajax({
        type: "GET",
     url : ajaxurl,
            data :{action: "escrow_discussion",  id :escrow_id},
        success: function(data){
          

            	document.getElementById("feedback").innerHTML = data;

        }
    });
      	
 
	$('.wordpress-ajax-form').on('submit', function(e) {
		e.preventDefault();
  // alert("hello");
		var $form = $(this);
	
		$.post($form.attr('action'), $form.serialize()+
        '&my_nonce=' + 'aistore_nonce' +
        '&action=custom_action', function(data) {
			alert('This is data returned from the server ' + data);
		}, 'json');
		
		
	 

	var escrow_id = document.getElementById('escrow_id').value;
//	alert("Your Escrow id is ::"+escrow_id);
		  $.ajax({
        type: "GET",
     url : ajaxurl,
            data : {action: "escrow_discussion",  id :escrow_id},
        success: function(data){
          
          
            	document.getElementById("feedback").innerHTML = data;

        }
    });
    

		
	});
	}
});








jQuery('#newCustomerForm').submit(ajaxSubmit);

function ajaxSubmit() {
    var newCustomerForm = jQuery(this).serialize();

  

    return false;
}
