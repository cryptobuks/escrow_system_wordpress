Dear <?php echo $seller_name;?>,
<br /><br />

 <?php /*echo $buyer_email;?> has deposited the required Escrow Amount of <?php echo $deposit_amount;*/?>
 <!--FEES CARGO ???-->
 

 
 
 <?php 
 $escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ",$eid ));

echo Aistore_process_placeholder_Text(  get_option('email_seller_deposit'),$escrow );

?>
<br /><br />
you can now start shipping the items to the buyer, your funds are secure!
With regards,

<br /><br />
The globalescrow.uk Team