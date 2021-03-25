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
    
   


}
register_activation_hook(__FILE__, 'aistore_plugin_table_install');
 

 
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

add_filter('woo_wallet_disallow_negative_transaction', '__return_false'); 
 