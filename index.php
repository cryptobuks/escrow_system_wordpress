<?php
/*
Plugin Name: Saksh Escrow System
Version:  1.0
Plugin URI: #
Author: susheelhbti
Author URI: http://www.aistore2030.com/
Description: Saksh Escrow System is a plateform allow parties to complete safe payments.  


*/
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


add_action('init', 'aistore_wpdocs_load_textdomain');



function aistore_wpdocs_load_textdomain()
{
    load_plugin_textdomain('aistore', FALSE, basename(dirname(__FILE__)) . '/languages/');
}


function aistore_scripts_method()
{
    


    
    wp_enqueue_style('aistore', plugins_url('/css/custom.css', __FILE__), array());
    wp_enqueue_script('aistore', plugins_url('/js/custom.js', __FILE__), array(
        'jquery'
    ));
}




add_action('wp_enqueue_scripts', 'aistore_scripts_method');







function aistore_isadmin()
{
    
    $user          = wp_get_current_user();
    $allowed_roles = array(
        'administrator'
    );
    if (array_intersect($allowed_roles, $user->roles)) {
        return true;
    } else {
        
        return false;
        
    }
}
 

function aistore_plugin_table_install()
{
    global $wpdb;
    
    
    
    
    $table_escrow_discussion = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "escrow_discussion  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  eid int(100) NOT NULL,
   message  text  NOT NULL,
   user_login  varchar(100)   NOT NULL,
  status  varchar(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
    
    
    
    
    $table_escrow_documents = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "escrow_documents (
  id int(100) NOT NULL  AUTO_INCREMENT,
  eid  int(100) NOT NULL,
  documents  varchar(100)  NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
   user_id  int(100) NOT NULL,
  documents_name  varchar(100)  DEFAULT NULL,
  PRIMARY KEY (id)
)  ";
    
    
    $table_escrow_system = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "escrow_system (
  id int(100) NOT NULL AUTO_INCREMENT, 
  title varchar(100)   NOT NULL,
  term_condition text ,
  amount int(100) NOT NULL,
  receiver_email varchar(100)  NOT NULL,
  sender_email varchar(100)   NOT NULL,
  escrow_fee int(100) NOT NULL,
  status varchar(100)   NOT NULL DEFAULT 'pending',
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  
  
  PRIMARY KEY (id)
)  ";
    
    
    
     
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    
    dbDelta($table_escrow_discussion);
    
    dbDelta($table_escrow_system);
    
    dbDelta($table_escrow_documents);
    
     email_notification_message();


}
register_activation_hook(__FILE__, 'aistore_plugin_table_install');
 include_once dirname(__FILE__) . '/Escrow_list.php';
 include_once dirname(__FILE__) . '/user_escrow.php';
include_once dirname(__FILE__) . '/email/sendnotification.php';
include_once dirname(__FILE__) . '/user_email_verification.php';
//include_once dirname(__FILE__) . '/invoice.php';

include_once dirname(__FILE__) . '/AistoreEscrowSystem.class.php';


include_once dirname(__FILE__) . '/AistoreSettingsPage.class.php';


add_shortcode('aistore_escrow_system', array(
    'AistoreEscrowSystem',
    'aistore_escrow_system'
));

add_shortcode('escrow_system_part2', array(
    'AistoreEscrowSystem',
    'escrow_system_part2'
));

add_shortcode('aistore_escrow_list', array(
    'AistoreEscrowSystem',
    'aistore_escrow_list'
));

add_shortcode('aistore_escrow_detail', array(
    'AistoreEscrowSystem',
    'aistore_escrow_detail'
));



function email_notification_message(){
    //notification
    
    
	update_option(  'created_escrow','You have successfully created the escrow # [EID]' );
	update_option( 'partner_created_escrow', 'Your partner have successfully created the escrow # [EID]' );
	update_option( 'accept_escrow', 'You have successfully  accepted the escrow # [EID]' );
	update_option( 'partner_accept_escrow', 'Your partner have successfully accepted the escrow # [EID]' );
	
	update_option( 'dispute_escrow', 'You have successfully  disputed the escrow # [EID]' );
	update_option( 'partner_dispute_escrow', 'Your partner have successfully disputed the escrow # [EID]' );
	update_option( 'release_escrow', 'You have successfully  released the escrow # [EID]' );
	update_option( 'partner_release_escrow', 'Your partner have successfully released the escrow # [EID]' );
	
	
	update_option( 'cancel_escrow', 'You have successfully  cancelled the escrow # [EID]' );
	update_option( 'partner_cancel_escrow', 'Your partner have successfully cancelled the escrow # [EID]' );
	update_option( 'shipping_escrow', 'you have updated the shipping details for the escrow# [EID]' );
	update_option( 'partner_shipping_escrow', 'Your partner has updated the shipping details for the escrow# [EID]' );
	
	update_option( 'buyer_deposit', 'Your payment  has been accepted for the escrow  # [EID]' );
	update_option( 'seller_deposit', 'You have deposited the payment into  the escrow for  the transaction  escrow # [EID]' );
	update_option( 'Buyer_Mark_Paid', 'You have successfully  marked escrow # [EID]' );

//email

update_option( 'email_created_escrow' ,'You have successfully created the escrow # [EID]');
	update_option( 'email_partner_created_escrow', 'Your partner have successfully created the escrow # [EID]' );
	update_option( 'email_accept_escrow', 'You have successfully  accepted the escrow # [EID]' );
	update_option( 'email_partner_accept_escrow', 'Your partner have successfully accepted the escrow # [EID]' );
	
	update_option( 'email_dispute_escrow', 'You have successfully  disputed the escrow # [EID]' );
	update_option( 'email_partner_dispute_escrow', 'Your partner have successfully disputed the escrow # [EID]' );
	update_option( 'email_release_escrow', 'You have successfully  released the escrow # [EID]' );
	update_option( 'email_partner_release_escrow', 'Your partner have successfully released the escrow # [EID]' );
	
	
	update_option( 'email_cancel_escrow', 'You have successfully  cancelled the escrow # [EID]' );
	update_option( 'email_partner_cancel_escrow', 'Your partner have successfully cancelled the escrow # [EID]' );
	update_option( 'email_shipping_escrow', 'you have updated the shipping details for the escrow# [EID]' );
	update_option( 'email_partner_shipping_escrow', 'Your partner has updated the shipping details for the escrow# [EID]' );
	
	update_option( 'email_buyer_deposit', 'Your payment  has been accepted for the escrow  # [EID]' );
	update_option( 'email_seller_deposit', 'You have deposited the payment into  the escrow for  the transaction  escrow # [EID]' );
	update_option( 'email_Buyer_Mark_Paid', 'You have successfully  marked escrow # [EID]' );

}



function  Aistore_process_placeholder_Text($str,$escrow)
 {
     
   $str = str_replace("[EID]", $escrow->id, $str);
     return $str;
 }


 
