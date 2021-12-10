<?php
class AistoreEscrowSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array(
            $this,
            'aistore_add_plugin_page'
        ));
        add_action('admin_init', array(
            $this,
            'aistore_page_register_setting'
        ));
      
        
        
        
         add_action('admin_init', array(
            $this,
            'aistore_message_register_setting'
        ));
        
        

    }

    /**
     * Add options page
     */
    public function aistore_add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page('Settings Admin', __('Escrow Setting', 'aistore') , 'administrator', 'Escrow-setting-admin', array(
            $this,
            'aistore_page_setting'
        ));

        add_menu_page(__('Escrow System', 'aistore') , __('Escrow System', 'aistore') , 'administrator', 'aistore_user_escrow_list');


        add_submenu_page('aistore_user_escrow_list', __('Escrow List', 'aistore') , __('Escrow List', 'aistore') , 'administrator', 'aistore_user_escrow_list', array(
            $this,
            'aistore_user_escrow_list'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Disputed Escrow List', 'aistore') , __('Disputed Escrow', 'aistore') , 'administrator', 'disputed_escrow_list', array(
            $this,
            'aistore_disputed_escrow_list'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Disputed Escrow Details', 'aistore') , __('', 'aistore') , 'administrator', 'disputed_escrow_details', array(
            $this,
            'aistore_disputed_escrow_details'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Escrow Setting', 'aistore') , __('Escrow Setting', 'aistore') , 'administrator', 'aistore_page_setting', array(
            $this,
            'aistore_page_setting'
        ));

      
        add_submenu_page('aistore_user_escrow_list', __('Notification Setting', 'aistore') , __('Notification Setting', 'aistore') , 'administrator', 'notification_setting', array(
            $this,
            'aistore_notification_setting'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Email Setting', 'aistore') , __('Email Setting', 'aistore') , 'administrator', 'email_setting', array(
            $this,
            'aistore_email_setting'
        ));
        
        add_submenu_page('aistore_user_escrow_list', __('Escrow Message Setting', 'aistore') , __('Escrow Message Setting', 'aistore') , 'administrator', 'message_setting', array(
            $this,
            'aistore_message_setting'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Payment Process', 'aistore') , __('Payment Process', 'aistore') , 'administrator', 'payment_process', array(
            $this,
            'aistore_payment_process'
        ));


        add_submenu_page('aistore_user_escrow_list', __('Dashboard', 'aistore') , __('Dashboard', 'aistore') , 'administrator', 'escrow_dashboard', array(
            $this,
            'aistore_escrow_dashboard'
        ));

        add_submenu_page('aistore_user_escrow_list', __('Escrow Admin Report', 'aistore') , __('Escrow Admin Report', 'aistore') , 'administrator', 'admin_report', array(
            $this,
            'aistore_escrow_admin_report'
        ));
        
        
        add_submenu_page('aistore_user_escrow_list', __('All Notification', 'aistore') , __('All Notification', 'aistore') , 'administrator', 'all_notification', array(
            $this,
            'aistore_escrow_all_notification'
        ));
    }
    
    
    function aistore_escrow_all_notification(){
         include_once dirname(__FILE__) . '/admin_setting/aistore_escrow_all_notification.php';
    }
      function aistore_escrow_admin_report(){
          
       include_once dirname(__FILE__) . '/admin_setting/aistore_escrow_admin_report.php';
    }
    
    function aistore_escrow_dashboard(){
              
 include_once dirname(__FILE__) . '/admin_setting/aistore_escrow_dashboard.php';
              }
    
    function aistore_message_setting(){
        
 include_once dirname(__FILE__) . '/admin_setting/escrow_message_setting.php';
    }

    function aistore_user_escrow_list()
    {
     include_once dirname(__FILE__) . '/admin_setting/aistore_user_escrow_list.php';

    }
    
    function aistore_disputed_escrow_details()
    {
     include_once dirname(__FILE__) . '/admin_setting/aistore_disputed_escrow_details.php';
    }

    // disputed escrow list
    function aistore_disputed_escrow_list()
    {
      include_once dirname(__FILE__) . '/admin_setting/aistore_disputed_escrow_list.php';
    }
    
    
    function aistore_page_setting()
    {
    include_once dirname(__FILE__) . '/admin_setting/page_setting.php';
    }

    function aistore_notification_setting()
    {
     include_once dirname(__FILE__) . '/admin_setting/notification_setting.php';
    }
    
    function aistore_email_setting()
    {
   include_once dirname(__FILE__) . '/admin_setting/email_setting.php';
    }

    function aistore_payment_process()
    {
        include_once dirname(__FILE__) . '/admin_setting/aistore_payment_process.php';
    }
        
    // page Setting
    function aistore_page_register_setting()
    {
        //register our settings
        register_setting('aistore_page', 'add_escrow_page_id');
        register_setting('aistore_page', 'list_escrow_page_id');
        register_setting('aistore_page', 'details_escrow_page_id');
        register_setting('aistore_page', 'bank_details_page_id');
        register_setting('aistore_page', 'notification_page_id');
         register_setting('aistore_page', 'aistore_transaction_history_page_id');
        register_setting('aistore_page', 'aistore_saksh_withdrawal_system');
        register_setting('aistore_page', 'aistore_bank_account');
        
        

        register_setting('aistore_page', 'escrow_user_id');
        register_setting('aistore_page', 'escrow_create_fee');
        register_setting('aistore_page', 'escrow_accept_fee');
        register_setting('aistore_page', 'escrow_message_page');
        register_setting('aistore_page', 'cancel_escrow_fee');
        
        
        register_setting('aistore_page', 'bank_details');
        register_setting('aistore_page', 'deposit_instructions');
        // register_setting('aistore_page', 'aistore_escrow_currency');
        
    }

    function aistore_notification_register_setting()
    {
        register_setting('aistore_notification_page', 'created_escrow');
        register_setting('aistore_notification_page', 'partner_created_escrow');
        register_setting('aistore_notification_page', 'accept_escrow');
        register_setting('aistore_notification_page', 'partner_accept_escrow');

        register_setting('aistore_notification_page', 'dispute_escrow');
        register_setting('aistore_notification_page', 'partner_dispute_escrow');
        register_setting('aistore_notification_page', 'release_escrow');
        register_setting('aistore_notification_page', 'partner_release_escrow');

        register_setting('aistore_notification_page', 'cancel_escrow');
        register_setting('aistore_notification_page', 'partner_cancel_escrow');
        register_setting('aistore_notification_page', 'shipping_escrow');
        register_setting('aistore_notification_page', 'partner_shipping_escrow');

        register_setting('aistore_notification_page', 'buyer_deposit');
        register_setting('aistore_notification_page', 'seller_deposit');
        register_setting('aistore_notification_page', 'Buyer_Mark_Paid');

    }
    //email
    function aistore_email_register_setting()
    {
        register_setting('aistore_email_page', 'email_created_escrow');
        register_setting('aistore_email_page', 'email_partner_created_escrow');
        register_setting('aistore_email_page', 'email_accept_escrow');
        register_setting('aistore_email_page', 'email_partner_accept_escrow');

        register_setting('aistore_email_page', 'email_dispute_escrow');
        register_setting('aistore_email_page', 'email_partner_dispute_escrow');
        register_setting('aistore_email_page', 'email_release_escrow');
        register_setting('aistore_email_page', 'email_partner_release_escrow');

        register_setting('aistore_email_page', 'email_cancel_escrow');
        register_setting('aistore_email_page', 'email_partner_cancel_escrow');
        register_setting('aistore_email_page', 'email_shipping_escrow');
        register_setting('aistore_email_page', 'email_partner_shipping_escrow');

        register_setting('aistore_email_page', 'email_buyer_deposit');
        register_setting('aistore_email_page', 'email_seller_deposit');
        register_setting('aistore_email_page', 'email_Buyer_Mark_Paid');
    }
    
    
    
       //message
    function aistore_message_register_setting()
    {
        register_setting('aistore_message_page', 'created_escrow_message');
     
        register_setting('aistore_message_page', 'accept_escrow_message');

        register_setting('aistore_message_page', 'dispute_escrow_message');
        register_setting('aistore_message_page', 'release_escrow_message');

        register_setting('aistore_message_page', 'cancel_escrow_message');
        
         register_setting('aistore_message_page', 'created_escrow_success_message');
         register_setting('aistore_message_page', 'accept_escrow_success_message');
         register_setting('aistore_message_page', 'dispute_escrow_success_message');
         register_setting('aistore_message_page', 'release_escrow_success_message');
         register_setting('aistore_message_page', 'cancel_escrow_success_message');
        
      
    }
    

 
  
  
        
}

if (is_admin()) $AistoreEscrowSettings = new AistoreEscrowSettings();

