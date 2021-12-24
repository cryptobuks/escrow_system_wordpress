<?php
/*
Plugin Name: Saksh Escrow System
Version:  2.0
Plugin URI: #
Author: susheelhbti
Author URI: http://www.aistore2030.com/
Description: Saksh Escrow System is a plateform allow parties to complete safe payments.  


*/

if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
}

add_action('init', 'aistore_wpdocs_load_textdomain');

function aistore_wpdocs_load_textdomain()
{
    load_plugin_textdomain('aistore', false, basename(dirname(__FILE__)) . '/languages/');
}

function aistore_scripts_method()
{

  wp_enqueue_style('jquery-datatables-css', 'https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css');
  wp_enqueue_style('jquery-datatables-css', 'https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css'); 
  
   wp_enqueue_script('jquery-datatables-js', 'https://code.jquery.com/jquery-3.5.1.js', array() , null, true);
  wp_enqueue_script('jquery-datatables-js', 'https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js', array() , null, true);
   wp_enqueue_script('jquery-datatables-js', 'https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js', array() , null, true);
  wp_enqueue_script('jquery-datatables-js', 'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', array() , null, true);
    
  wp_enqueue_script('jquery-datatables-js', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js', array() , null, true);
  wp_enqueue_script('jquery-datatables-js', 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js', array() , null, true);
  wp_enqueue_script('jquery-datatables-js', 'https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js', array() , null, true);
    
   wp_enqueue_style('wpdocs-bootstrap-style', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css');
   wp_enqueue_script('wpdocs-bootstrap-bundle-script', 'https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js', array() , null, true);

    wp_enqueue_style('aistore', plugins_url('/css/custom.css', __FILE__) , array());
    wp_enqueue_script('aistore', plugins_url('/js/custom.js', __FILE__) , array(
        'jquery'
    ));
}

add_action('wp_enqueue_scripts', 'aistore_scripts_method');

function aistore_isadmin()
{

    $user = wp_get_current_user();
    $allowed_roles = array(
        'administrator'
    );
    if (array_intersect($allowed_roles, $user->roles))
    {
        return true;
    }
    else
    {

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
  ipaddress varchar(100)   NOT NULL,
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

    $table_escrow_documents = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "escrow_documents (
  id int(100) NOT NULL  AUTO_INCREMENT,
  eid  int(100) NOT NULL,
  documents  varchar(100)  NOT NULL,
   ipaddress varchar(100)   NOT NULL,
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
  currency  varchar(100)   NOT NULL,
  receiver_email varchar(100)  NOT NULL,
  sender_email varchar(100)   NOT NULL,
  escrow_fee int(100) NOT NULL,
  status varchar(100)   NOT NULL DEFAULT 'pending',
  payment_status varchar(100)   NOT NULL DEFAULT 'Pending',
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  
  
  PRIMARY KEY (id)
)  ";

    $table_escrow_notification = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "escrow_notification  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  type varchar(100) NOT NULL,
   message  text  NOT NULL,
   user_email  varchar(100)   NOT NULL,
  url varchar(100)   NOT NULL,
   reference_id bigint(20)   NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

$table_escrow_email = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "escrow_email  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  type varchar(100) NOT NULL,
   message  text  NOT NULL,
   user_email  varchar(100)   NOT NULL,
  url varchar(100)   NOT NULL,
   reference_id bigint(20)   NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

  $table_aistore_wallet_transactions = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_wallet_transactions  (
   	transaction_id  bigint(20)  NOT NULL  AUTO_INCREMENT,
  user_id bigint(20)  NOT NULL,
   reference bigint(20)   NULL,
   type   varchar(100)  NOT NULL,
   amount  decimal(10,2)    NOT NULL,
  balance  decimal(10,2)    NOT NULL,
    description  text  NOT NULL,
   currency  varchar(100)   NOT NULL,
   created_by  	bigint(20) NOT NULL,
   date  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (transaction_id)
) ";

    $table_aistore_wallet_balance = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "aistore_wallet_balance  (
     	id  bigint(20)  NOT NULL  AUTO_INCREMENT,
   	transaction_id  bigint(20)  NOT NULL,
  user_id bigint(20)  NOT NULL,
  balance  decimal(10,2)    NOT NULL,
   currency  varchar(100)   NOT NULL,
   created_by  	bigint(20) NOT NULL,
   date  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

  
    $table_withdrawal_requests = "CREATE TABLE   IF NOT EXISTS  " . $wpdb->prefix . "widthdrawal_requests  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  amount int(100) NOT NULL,
 
   method  varchar(100)   NOT NULL,   charges  varchar(100)   NOT NULL,
   username  varchar(100)   NOT NULL,
   currency  varchar(100)   NOT NULL,
  status  varchar(100)   NOT NULL DEFAULT 'pending',
   created_at  timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";

  $table_escrow_currency = "CREATE TABLE  IF NOT EXISTS  " . $wpdb->prefix . "escrow_currency  (
  id int(100) NOT NULL  AUTO_INCREMENT,
  currency varchar(100) NOT NULL,
   symbol  varchar(100)   NOT NULL,
  created_at timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ";
   
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($table_escrow_discussion);

    dbDelta($table_escrow_system);

    dbDelta($table_escrow_documents);

    dbDelta($table_escrow_notification);
    
    dbDelta($table_escrow_email);
    
    
      dbDelta($table_aistore_wallet_transactions);

    dbDelta($table_aistore_wallet_balance);
    
    dbDelta($table_withdrawal_requests);
    
    dbDelta($table_escrow_currency);




    email_notification_message();
    
    
    
    
     update_option('escrow_accept_fee', 5);
     update_option('escrow_create_fee', 5);
     update_option('withdraw_fee', 5);
    

}
register_activation_hook(__FILE__, 'aistore_plugin_table_install');

include_once dirname(__FILE__) . '/Escrow_list.php';
include_once dirname(__FILE__) . '/user_notification.php';
include_once dirname(__FILE__) . '/user_escrow.php';
include_once dirname(__FILE__) . '/email/sendnotification.php';
include_once dirname(__FILE__) . '/user_email_verification.php';
include_once dirname(__FILE__) . '/aistore_wallet/index.php';
include_once dirname(__FILE__) . '/email/notification_api.php';
include_once dirname(__FILE__) . '/AistoreEscrowSystem.class.php';

include_once dirname(__FILE__) . '/AistoreEscrowSettings.class.php';

add_shortcode('aistore_escrow_system', array(
    'AistoreEscrowSystem',
    'aistore_escrow_system'
));

add_shortcode('aistore_escrow_list', array(
    'AistoreEscrowSystem',
    'aistore_escrow_list'
));

add_shortcode('aistore_escrow_detail', array(
    'AistoreEscrowSystem',
    'aistore_escrow_detail'
));

add_shortcode('aistore_bank_details', array(
    'AistoreEscrowSystem',
    'aistore_bank_details'
));

 

function email_notification_message()
{
    
    //messages
     update_option('created_escrow_message', 'Payment transaction for the created escrow with escrow id #');
    update_option('created_escrow_success_message', 'Created Successfully');
    update_option('accept_escrow_message', 'Payment transaction for the accepted escrow with escrow id #');
    update_option('accept_escrow_success_message', 'Accepted Successfully');

    update_option('dispute_escrow_message', 'Payment transaction for the disputed escrow with escrow id #');
    update_option('dispute_escrow_success_message', 'Disputed Successfully');
    
    update_option('release_escrow_message', 'Payment transaction for the released escrow with escrow id #');
    update_option('release_escrow_success_message', 'Released Successfully');
 update_option('cancel_escrow_message', 'Payment transaction for the cancelled escrow with escrow id #');
    update_option('cancel_escrow_success_message', 'Cancelled Successfully');
    
    //notification
    

    update_option('created_escrow', 'You have successfully created the escrow # [EID]');
    update_option('partner_created_escrow', 'Your partner have successfully created the escrow # [EID]');
    update_option('accept_escrow', 'You have successfully  accepted the escrow # [EID]');
    update_option('partner_accept_escrow', 'Your partner have successfully accepted the escrow # [EID]');

    update_option('dispute_escrow', 'You have successfully  disputed the escrow # [EID]');
    update_option('partner_dispute_escrow', 'Your partner have successfully disputed the escrow # [EID]');
    update_option('release_escrow', 'You have successfully  released the escrow # [EID]');
    update_option('partner_release_escrow', 'Your partner have successfully released the escrow # [EID]');

    update_option('cancel_escrow', 'You have successfully  cancelled the escrow # [EID]');
    update_option('partner_cancel_escrow', 'Your partner have successfully cancelled the escrow # [EID]');
    update_option('shipping_escrow', 'you have updated the shipping details for the escrow# [EID]');
    update_option('partner_shipping_escrow', 'Your partner has updated the shipping details for the escrow# [EID]');

    update_option('buyer_deposit', 'Your payment  has been accepted for the escrow  # [EID]');
    update_option('seller_deposit', 'You have deposited the payment into  the escrow for  the transaction  escrow # [EID]');
    update_option('Buyer_Mark_Paid', 'You have successfully  marked escrow # [EID]');

    //email
    update_option('email_created_escrow', 'You have successfully created the escrow # [EID]');
    update_option('email_partner_created_escrow', 'Your partner have successfully created the escrow # [EID]');
    update_option('email_accept_escrow', 'You have successfully  accepted the escrow # [EID]');
    update_option('email_partner_accept_escrow', 'Your partner have successfully accepted the escrow # [EID]');

    update_option('email_dispute_escrow', 'You have successfully  disputed the escrow # [EID]');
    update_option('email_partner_dispute_escrow', 'Your partner have successfully disputed the escrow # [EID]');
    update_option('email_release_escrow', 'You have successfully  released the escrow # [EID]');
    update_option('email_partner_release_escrow', 'Your partner have successfully released the escrow # [EID]');

    update_option('email_cancel_escrow', 'You have successfully  cancelled the escrow # [EID]');
    update_option('email_partner_cancel_escrow', 'Your partner have successfully cancelled the escrow # [EID]');
    update_option('email_shipping_escrow', 'you have updated the shipping details for the escrow# [EID]');
    update_option('email_partner_shipping_escrow', 'Your partner has updated the shipping details for the escrow# [EID]');

    update_option('email_buyer_deposit', 'Your payment  has been accepted for the escrow  # [EID]');
    update_option('email_seller_deposit', 'You have deposited the payment into  the escrow for  the transaction  escrow # [EID]');
    update_option('email_Buyer_Mark_Paid', 'You have successfully  marked escrow # [EID]');

}

function Aistore_process_placeholder_Text($str, $escrow)
{

    $str = str_replace("[EID]", $escrow->id, $str);
    return $str;
}

