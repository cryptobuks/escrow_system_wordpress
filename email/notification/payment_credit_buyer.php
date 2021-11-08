
Hi, <br /><br />

<!--Your deposit has been credited in the escrow 
<?php 
// echo $eid; 
?> 
and now the seller will ship the product.-->

<?php 
     $escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ",$eid ));

echo Aistore_process_placeholder_Text(  get_option('email_buyer_deposit'),$escrow );

?>
<br />
  
<br />

With Regards
