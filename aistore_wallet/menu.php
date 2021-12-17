<?php
 
if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
} 


 

add_action( 'admin_menu', 'register_my_custom_menu_page' );
function register_my_custom_menu_page() {
  
  
  add_menu_page( 'Wallet Account', 'Wallet Account', 'manage_options', 'aistore_debit_credit');

    
  add_submenu_page( 'aistore_debit_credit'  , 'Wallet Account', 'Debit/Credit', 'manage_options', 'aistore_debit_credit', 'aistore_debit_credit',  90 );

    
  add_submenu_page( 'aistore_debit_credit'  ,     'Wallet Account', 'Currency Setting', 'manage_options', 'currency_setting', 'currency_setting',  90 );
  
     
  add_submenu_page( 'aistore_debit_credit'  , 'Wallet Account', 'All Wallet Balance', 'manage_options', 'balance_list', 'balance_list',  90 );

  
   add_submenu_page( 'aistore_debit_credit'  , 'Wallet Account', 'Withdrawal', 'manage_options', 'withdrawal', 'withdrawal',  90 );

    
  add_submenu_page( 'aistore_debit_credit'  ,'Wallet Account', 'Withdrawal List', 'manage_options', 'withdrawal_list', 'withdrawal_list',  90 );

    
}

function aistore_debit_credit(){

    include dirname(__FILE__) . "/admin/debit_credit.php";

}

function currency_setting(){
    include_once dirname(__FILE__) . '/admin/currency_setting.php';
}

function balance_list(){
 include_once dirname(__FILE__) . '/admin/all_wallet_balance.php';


}



function withdrawal(){

include_once dirname(__FILE__) . '/Withdrawal.php';
}

function withdrawal_list(){
include_once dirname(__FILE__) . '/Widthdrawal_requests.php';
}
