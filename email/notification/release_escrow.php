Hi, 
<br /><br />

 
 <?php 
 $escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ",$eid ));

echo Aistore_process_placeholder_Text(  get_option('email_release_escrow'),$escrow );

?>
 <br />
 
<br />

With Regards

