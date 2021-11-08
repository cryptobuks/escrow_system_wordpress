<?php
$escrow_id = $eid;

     $escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ",$escrow_id ));

 
 $Buyer_Deposit = Aistore_process_placeholder_Text(  get_option('buyer_deposit'),$escrow );


$Seller_Deposit = Aistore_process_placeholder_Text(  get_option('seller_deposit'),$escrow );
 
$created_escrow = Aistore_process_placeholder_Text(  get_option('created_escrow'),$escrow );

$partner_created_escrow = Aistore_process_placeholder_Text(  get_option('partner_created_escrow'),$escrow );

$Buyer_Mark_Paid = Aistore_process_placeholder_Text(  get_option('Buyer_Mark_Paid'),$escrow );

$accept_escrow = Aistore_process_placeholder_Text(  get_option('accept_escrow'),$escrow );


$partner_accept_escrow = Aistore_process_placeholder_Text(  get_option('partner_accept_escrow'),$escrow );


$dispute_escrow =Aistore_process_placeholder_Text(  get_option('dispute_escrow'),$escrow ) ;


$partner_dispute_escrow = Aistore_process_placeholder_Text(  get_option('partner_dispute_escrow'),$escrow );


$release_escrow = Aistore_process_placeholder_Text(  get_option('release_escrow'),$escrow );

$partner_release_escrow = Aistore_process_placeholder_Text(  get_option('partner_release_escrow'),$escrow );

 $partner_cancel_escrow = Aistore_process_placeholder_Text(  get_option('partner_cancel_escrow'),$escrow );
 
$cancel_escrow =Aistore_process_placeholder_Text(  get_option('cancel_escrow'),$escrow );



$partner_shipping_escrow =Aistore_process_placeholder_Text(  get_option('partner_shipping_escrow'),$escrow ); 

$shipping_escrow = Aistore_process_placeholder_Text(  get_option('shipping_escrow'),$escrow );
 
