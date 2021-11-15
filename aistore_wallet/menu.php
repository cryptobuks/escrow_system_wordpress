<?php
 
if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
} 


 

add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page() {
  
  
  add_menu_page( 'Wallet Account', 'Wallet Account', 'manage_options', 'wallet_account');

    
  add_submenu_page( 'wallet_account'  , 'Wallet Account', 'Debit/Credit', 'manage_options', 'wallet_account', 'wallet_account',  90 );

    
  add_submenu_page( 'wallet_account'  ,     'Wallet Account', 'Currency Setting', 'manage_options', 'currency_setting', 'currency_setting',  90 );
  
     
  add_submenu_page( 'wallet_account'  , 'Wallet Account', 'All Wallet Balance', 'manage_options', 'balance_list', 'balance_list',  90 );

    
  add_submenu_page( 'wallet_account'  ,     'Wallet Account', 'All Transaction List', 'manage_options', 'all_wallet_transaction', 'all_wallet_transaction',  90 );
  
   add_submenu_page( 'wallet_account'  , 'Wallet Account', 'Withdrawal', 'manage_options', 'withdrawal', 'withdrawal',  90 );

    
  add_submenu_page( 'wallet_account'  ,'Wallet Account', 'Withdrawal List', 'manage_options', 'withdrawal_list', 'withdrawal_list',  90 );

    
}

function wallet_account(){

    include dirname(__FILE__) . "/admin/debit_credit.php";

}

function currency_setting(){
    include_once dirname(__FILE__) . '/admin/currency_setting.php';
}

function balance_list(){

 $user_url = admin_url('users.php', 'https'); ?>
 <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($user_url); ?>" />
 <?php
}

function all_wallet_transaction(){
include_once dirname(__FILE__) . '/admin/transaction_list.php';
}


function withdrawal(){

include_once dirname(__FILE__) . '/Withdrawal.php';
}

function withdrawal_list(){
include_once dirname(__FILE__) . '/Widthdrawal_requests.php';
}
