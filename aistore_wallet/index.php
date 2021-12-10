<?php


if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
}
function aistore_withdraw_enqueue_styles() {
wp_enqueue_style( 'aistore', '//stackpath.bootstrapcdn.com/bootstrap/5.0.1/css/bootstrap.min.css' );
wp_enqueue_script( 'aistore', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js');
wp_enqueue_style( 'aistore', get_template_directory_uri() . '/style.css');
}




add_action('wp_enqueue_scripts', 'aistore_withdraw_enqueue_styles');




include_once dirname(__FILE__) . '/admin/user_transaction_list.php';
include_once dirname(__FILE__) . '/admin/transaction_list.php';
include_once dirname(__FILE__) . '/admin/user_balance.php';
include_once dirname(__FILE__) . '/AistoreWallet.class.php';
include_once dirname(__FILE__) . '/Aistore_WithdrawalSystem.class.php';
include_once dirname(__FILE__) . '/Widthdrawal_requests.php';
// include_once dirname(__FILE__) . '/Withdrawal.php';
include_once dirname(__FILE__) . '/menu.php';


add_shortcode('aistore_transaction_history', array(
    'AistoreWallet',
    'aistore_transaction_history'
));

 add_shortcode('aistore_saksh_withdrawal_system', array(
    'Aistore_WithdrawalSystem',
    'aistore_saksh_withdrawal_system'
));
 
 
  add_shortcode('aistore_bank_account', array(
    'Aistore_WithdrawalSystem',
    'aistore_bank_account'
));


