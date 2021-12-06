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
        add_options_page('Settings Admin', __('My Setting', 'aistore') , 'administrator', 'my-setting-admin', array(
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

        add_submenu_page('aistore_user_escrow_list', __('Setting', 'aistore') , __('Setting', 'aistore') , 'administrator', 'aistore_page_setting', array(
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
    }
    
       function aistore_escrow_admin_report(){
           	global $wpdb;
           	
           	        $escrow_admin_user_id = get_option('escrow_user_id');
           	        
     	$sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE {$wpdb->prefix}users.ID= ".$escrow_admin_user_id;
     	
     //	echo $sql;
     	
     	 $results = $wpdb->get_results($sql);
           ?>
       <h1> <?php _e('Escrow Admin Report', 'aistore') ?> </h1>
   
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    

<table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('ID', 'aistore'); ?></th>
      
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
		  
	    <th><?php _e('Amount', 'aistore'); ?></th> 
	    <th><?php _e('Type', 'aistore'); ?></th> 
	    <th><?php _e('Description', 'aistore'); ?></th> 
	      <th><?php _e('Date', 'aistore'); ?></th> 
            </tr>
            
        </thead>
        
        <tbody>
            <?php
            
  
        if ($results == null)
        {
            // _e("No transactions Found", 'aistore');

        }
        else
        {
             foreach ($results as $row):
             
?>
            <tr>
            	   <td>  <?php echo esc_attr($row->transaction_id); ?></td>
	
		  
		   
		   <td> 		   <?php echo esc_attr($row->user_email); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->balance); ?> </td>
		   
		   <td> 		   <?php echo esc_attr($row->amount." ".$row->currency); ?> </td>
		   
		  <td> 		   <?php echo esc_attr($row->type); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->description); ?> </td>
		   
		   	   <td> 		   <?php echo esc_attr($row->date); ?> </td>
		   </tr>
		   <?php
            endforeach;
        ?>
    
        </tbody>
        
        <?php } ?>
        
        <tfoot>
            <tr>
         <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
          	  
	    <th><?php _e('Amount', 'aistore'); ?></th> 
	    <th><?php _e('Type', 'aistore'); ?></th> 
	    <th><?php _e('Description', 'aistore'); ?></th> 
            </tr>
        </tfoot>
    </table>
    
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
    <?php
            
    }
    
    function aistore_escrow_dashboard(){
       
               $wallet = new AistoreWallet();
        $results = $wallet->aistore_wallet_currency();
    
            // foreach ($results as $c)
            // {
     $currency='USD';//$c->currency;
 
    
            // }
            
$users = get_users( );
            


            ?>
             <h1> <?php _e('User List', 'aistore') ?> </h1>
   
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    

<table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
		  
	  
            </tr>
            
        </thead>
        
        <tbody>
            <?php
            
  
        if ($users == null)
        {
            _e("No User Found", 'aistore');

        }
        else
        {
             foreach ($users as $row):
               //  print_r($users);
  $balance = $wallet->aistore_balance($row->ID, $currency);
?>
            <tr>
            	   <td>  <?php echo esc_attr($row->ID); ?></td>
		  		   <td> 		   <?php echo esc_attr($row->user_login); ?> </td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->user_email); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($balance); ?> </td>
		   
		 
		   </tr>
		   <?php
            endforeach;
        ?>
    
        </tbody>
        
        <?php } ?>
        
        <tfoot>
            <tr>
         <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
            </tr>
        </tfoot>
    </table>
    
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
    <?php
            
    }
    
    
    
    
    function aistore_message_setting(){
        
        ?>
        <h3><?php _e('Message Setting', 'aistore') ?></h3>
      
<form method="post" action="options.php">
    <?php settings_fields('aistore_message_page'); ?>
    <?php do_settings_sections('aistore_message_page'); ?>
    
       <table class="form-table">
        	 <tr valign="top"><th><?php _e('Note:', 'aistore') ?></th>
        	       <td scope="row"><?php _e(' This messages set to the wallet (debit/credit) payment transaction  message <br>details  for the escrow with escrow id<br>
        	       <strong>For Example: Payment transaction for the created escrow with escrow id #</strong>', 'aistore') ?></td>
        	         <td scope="row"><?php _e('This messages is set to be  for an escrow event<br><strong>For Example: Escrow Created Successfully</strong>', 'aistore') ?></td>
        	     </tr>
     
        
	 <tr valign="top">
        <th scope="row"><?php _e('Created Escrow', 'aistore') ?></th>
        <td>
            <textarea id="created_escrow_message" name="created_escrow_message" rows="2" cols="50">
<?php echo esc_attr(get_option('created_escrow_message')); ?>
</textarea>
          </td>
          
          <td>
            <textarea id="created_escrow_success_message" name="created_escrow_success_message" rows="2" cols="50">
<?php echo esc_attr(get_option('created_escrow_success_message')); ?>
</textarea>
          </td>
        </tr>
        
     
      <tr valign="top">
        <th scope="row"><?php _e('Accept Escrow', 'aistore') ?></th>
        <td>
             <textarea id="accept_escrow_message" name="accept_escrow_message" rows="2" cols="50">
<?php echo esc_attr(get_option('accept_escrow_message')); ?>
</textarea>
            </td>
            
              <td>
            <textarea id="accept_escrow_success_message" name="accept_escrow_success_message" rows="2" cols="50">
<?php echo esc_attr(get_option('accept_escrow_success_message')); ?>
</textarea>
          </td>
        </tr>
        
           
            <tr valign="top">
        <th scope="row"><?php _e('Dispute Escrow', 'aistore') ?></th>
        <td>
             <textarea id="dispute_escrow_message" name="dispute_escrow_message" rows="2" cols="50">
<?php echo esc_attr(get_option('dispute_escrow_message')); ?>
</textarea>
            </td>
            
              <td>
            <textarea id="dispute_escrow_success_message" name="dispute_escrow_success_message" rows="2" cols="50">
<?php echo esc_attr(get_option('dispute_escrow_success_message')); ?>
</textarea>
          </td>
        </tr>
        
        
      <tr valign="top">
        <th scope="row"><?php _e('Release Escrow', 'aistore') ?></th>
        <td>
             <textarea id="release_escrow_message" name="release_escrow_message" rows="2" cols="50">
<?php echo esc_attr(get_option('release_escrow_message')); ?>
</textarea>
            </td>
            
              <td>
            <textarea id="release_escrow_success_message" name="release_escrow_success_message" rows="2" cols="50">
<?php echo esc_attr(get_option('release_escrow_success_message')); ?>
</textarea>
          </td>
        </tr>
        
        
           
      <tr valign="top">
        <th scope="row"><?php _e('Cancel Escrow', 'aistore') ?></th>
        <td>
             <textarea id="cancel_escrow_message" name="cancel_escrow_message" rows="2" cols="50">
<?php echo esc_attr(get_option('cancel_escrow_message')); ?>
</textarea>
            </td>
              <td>
            <textarea id="cancel_escrow_success_message" name="cancel_escrow_success_message" rows="2" cols="50">
<?php echo esc_attr(get_option('cancel_escrow_success_message')); ?>
</textarea>
          </td>
        </tr>
        </table>
       <?php submit_button(); ?>

</form>
          <?php
    }

    function aistore_user_escrow_list()
    {

        global $wpdb;
              $page_id = get_option('details_escrow_page_id');
            if (isset($_REQUEST['id']))
        {
$id=sanitize_text_field($_REQUEST['id']);


      //  $id = sanitize_text_field($_REQUEST['id']);

        $user_email = get_the_author_meta('user_email', $id);

      

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE (sender_email=%s or receiver_email=%s ) order by id desc", $user_email, $user_email));
        }  
        else{
   
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system");
}

?>
  <h1> <?php _e('Escrow List', 'aistore') ?> </h1>
   
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    

<table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('Id', 'aistore'); ?></th>
        <th><?php _e('Title', 'aistore'); ?></th>
		    <th><?php _e('Status', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
		  <th><?php _e('Sender', 'aistore'); ?></th>
		  <th><?php _e('Receiver', 'aistore'); ?></th>
		  	  <th><?php _e('Date', 'aistore'); ?></th>
	  
            </tr>
            
        </thead>
        
        <tbody>
            <?php
            
  
        if ($results == null)
        {
            _e("No Escrow Found", 'aistore');

        }
        else
        {
             foreach ($results as $row):
   $url = admin_url('admin.php?page=disputed_escrow_details&eid=' . $row->id . '', 'https');

?>
            <tr>
            	   <td> 	 
		  <a href="<?php echo esc_html($url); ?>">
		   
		   <?php echo esc_attr($row->id); ?> </a> </td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->title); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->status); ?> </td>
		   
		   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo esc_attr($row->sender_email); ?> </td>
		   <td> 		   <?php echo esc_attr($row->receiver_email); ?> </td>
		     <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
		   </tr>
		   <?php
            endforeach;
        ?>
    
        </tbody>
        
        <?php } ?>
        
        <tfoot>
            <tr>
                   <th><?php _e('Id', 'aistore'); ?></th>
        <th><?php _e('Title', 'aistore'); ?></th>
		    <th><?php _e('Status', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
		  <th><?php _e('Sender', 'aistore'); ?></th>
		  <th><?php _e('Receiver', 'aistore'); ?></th>
		  	  <th><?php _e('Date', 'aistore'); ?></th>
            </tr>
        </tfoot>
    </table>
    
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
    
    <?php
    
    }
    
    
  

  

    

    //aistore_disputed_escrow_details
    function aistore_disputed_escrow_details()
    {

        global $wpdb;

        $eid = sanitize_text_field($_REQUEST['eid']);

        $object_escrow = new AistoreEscrowSystem();
        $aistore_escrow_currency = $object_escrow->get_escrow_currency();
        $escrow_admin_user_id = $object_escrow->get_escrow_admin_user_id();

        $user_id = get_current_user_id();
        $object_escrow = new AistoreEscrowSystem();
        $ipaddress = $object_escrow->aistore_ipaddress();
        $email_id = get_the_author_meta('user_email', $user_id);

        if (isset($_POST['upload_file']))
        {
            $upload_dir = wp_upload_dir();

            if (!empty($upload_dir['basedir']))
            {
                $user_dirname = $upload_dir['basedir'] . '/documents/' . $eid;
                if (!file_exists($user_dirname))
                {
                    wp_mkdir_p($user_dirname);
                }

                $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);

                move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                $image = $upload_dir['baseurl'] . '/documents/' . $eid . '/' . $filename;
                // save into database $image;
                

              
                    $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name,ipaddress) VALUES ( %d,%s,%d,%s,%s)", array(
                        $eid,
                        $image,
                        $user_id,
                        $filename,
                        $ipaddress
                    )));
            }
        }

        ob_start();

        if (!isset($eid))
        {

            $url = admin_url('admin.php?page=disputed_escrow_list', 'https');

?>
	<div><a href="<?php echo esc_html($url); ?>" >
	    <?php _e('Go To Escrow List Page', 'aistore'); ?> 
	     </a></div>
<?php
            return ob_get_clean();
        }

        if (isset($_POST['submit']) and $_POST['action'] == 'disputed')
        {
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = 'disputed'  WHERE id = '%d'", $eid));
      $dispute_escrow_success_message = get_option('dispute_escrow_success_message');

?>
<div>
<strong> <?php echo $dispute_escrow_success_message; ?></strong></div>
<?php
        }

        if (isset($_POST['submit']) and $_POST['action'] == 'accepted')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 'accepted', $eid));

            $amount = $wpdb->get_var($wpdb->prepare("SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid));
            
             $accept_escrow_message = get_option('accept_escrow_message');
            $escrow_details = $accept_escrow_message . $eid;
            
            
            

            $object_escrow_fee = new AistoreEscrowSystem();

            $escrow_fee = $object_escrow_fee->accept_escrow_fee($amount);

            $escrow_wallet = new AistoreWallet();

            $escrow_wallet->aistore_debit($user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details);
            sendNotificationAccepted($eid);
             $accept_escrow_success_message = get_option('accept_escrow_success_message');

?>

    
<strong> <?php echo $accept_escrow_success_message ?></strong>
<?php
            printf(__("Escrow Fee %d.", 'aistore') , $escrow_fee);

        }

        if (isset($_POST['submit']) and $_POST['action'] == 'released')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 'released', $eid));

            $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ", $eid));

            $escrow_amount = $escrow->amount;

            $escrow_reciever_email_id = $escrow->receiver_email;
            $user = get_user_by('email', $escrow_reciever_email_id);
            $id = $user->ID;
            $release_escrow_message = get_option('release_escrow_message');
            $escrow_details = $release_escrow_message . $eid;

            $escrow_wallet = new AistoreWallet();

            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);

            $escrow_wallet->aistore_credit($id, $escrow_amount, $aistore_escrow_currency, $escrow_details);
            
            $release_escrow_success_message = get_option('release_escrow_success_message');
            
?>
<div>
<strong> <?php echo $release_escrow_success_message; ?></strong></div>
<?php
        }

        if (isset($_POST['submit']) and $_POST['action'] == 'chat_custom_action')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }
            $object_escrow = new AistoreEscrowSystem();
            $ipaddress = $object_escrow->aistore_ipaddress();
            $message = sanitize_text_field(htmlentities($_POST['message']));
            $escrow_id = sanitize_text_field($_POST['escrow_id']);
            $user_login = get_the_author_meta('user_login', get_current_user_id());

            //issue 1
            $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login,ipaddress ) VALUES ( %d, %s, %s, %s ) ", array(
                $escrow_id,
                $message,
                $user_login,
                $ipaddress
            )));

            wp_die();

        }

        // Sender Create escrow  to excute cancel button
        // Receiver  accept or cancel escrow
        if (isset($_POST['submit']) and $_POST['action'] == 'cancelled')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 'cancelled', $eid));
            $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE  id=%d ", $eid));

            $escrow_amount = $escrow->amount;

            $sender_escrow_fee = $escrow->escrow_fee;
            $sender_email = $escrow->sender_email;
            $user = get_user_by('email', $sender_email);
            $sender_id = $user->ID;
            $cancel_escrow_message = get_option('cancel_escrow_message');
            $escrow_details = $cancel_escrow_message . $eid;
  

            $escrow_wallet = new AistoreWallet();

            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);

            $escrow_wallet->aistore_credit($sender_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);

            $cancel_escrow_fee = get_option('cancel_escrow_fee');

            if ($cancel_escrow_fee == 'yes')
            {
               $escrow_wallet->aistore_debit($escrow_admin_user_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details);

              $escrow_wallet->aistore_credit($sender_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details);
            
             $cancel_escrow_success_message = get_option('cancel_escrow_success_message');
             
            }
?>
<div>
<strong><?php echo $cancel_escrow_success_message; ?></strong></div>
<?php
        }

        if (aistore_isadmin())
        {

            $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s", $eid));

        }

        else
        {

            $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '" . $email_id . "' or receiver_email = '" . $email_id . "' ) and id=%s ", $eid));

        }

?>
	  <div>
	      <?php
        echo "<h1>#" . $escrow->id . " " . $escrow->title . "</h1><br>";

        printf(__("Term Condition : %s", 'aistore') , html_entity_decode($escrow->term_condition) . "<br>");
        printf(__("Sender :  %s", 'aistore') , $escrow->sender_email . "<br>");
        printf(__("Receiver : %s", 'aistore') , $escrow->receiver_email . "<br>");
        printf(__("Status : %s", 'aistore') , $escrow->status . "<br><br>");

        $object = new AistoreEscrowSystem();

        $object->accept_escrow_btn($escrow);

        $object->cancel_escrow_btn($escrow);

        $object->release_escrow_btn($escrow);

        $object->dispute_escrow_btn($escrow);

        $eid = $escrow->id;
        $escrow_documents = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_documents WHERE eid=%d", $eid));

?> 
  
    <table class="table">
    <?php
        foreach ($escrow_documents as $row):

?> 
	
	<div class="document_list">
   


  <p><a href="<?php echo esc_attr($row->documents); ?>" target="_blank">
	       <b><?php echo esc_attr($row->documents_name); ?></b></a></p>
  <h6 > <?php echo esc_attr($row->created_at); ?></h6>
</div>

<hr>
    
    <?php
        endforeach;

?>
    </table>
<br>
	   <div>  
    

 
	<label for="documents"> <?php _e('Documents', 'aistore'); ?> : </label>
<form  method="post"  action="<?php echo admin_url('admin-ajax.php') . '?action=custom_action&eid=' . $eid; ?>" class="dropzone" id="dropzone">
    <?php
        wp_nonce_field('aistore_nonce_action', 'aistore_nonce');
?>
  <div class="fallback">
    <input id="file" name="file" type="file" multiple  />
    <input type="hidden" name="action" value="custom_action" type="submit"  />
  </div>

</form>


       
    
    
     <div id="feedback"></div>
     
     </div>
     <br>
     
     <?php
        $message_page_url = get_option('escrow_message_page');

        if ($message_page_url == 'no')
        {
            return "";

        }

        $user_login = get_the_author_meta('user_login', get_current_user_id());

?>

     
	 
<div>
    <br>
<form method="POST" action="" name="chat_custom_action" enctype="multipart/form-data"  >
<?php
        wp_nonce_field('aistore_nonce_action', 'aistore_nonce');
?>
   <label for="message">   <?php _e('Message', 'aistore') ?></label><br>



  
  <?php
        $content = '';
        $editor_id = 'message';

        $settings = array(
            'tinymce' => array(
                'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
                'toolbar2' => '',
                'toolbar3' => ''

            ) ,
            'textarea_rows' => 1,
            'teeny' => true,
            'quicktags' => false,
            'media_buttons' => false
        );

        wp_editor($content, $editor_id, $settings);

?>

 
 <?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
 
 <input type="hidden" name="action" value="chat_custom_action" />
 <input type="hidden" name="escrow_id"  id="escrow_id" value="<?php echo esc_attr($escrow->id); ?>" />
 
  <input type="submit"  name="submit" value="<?php _e('Submit Message', 'aistore') ?>">

</form> 
</div>

<!--<div id="feedback"></div>-->

 <br>
 <div class="card">
	
	<?php
        $discussions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion  WHERE eid=%d order by id desc", $eid));

        foreach ($discussions as $row):

?> 
	
	<div class="discussionmsg">
   
  <p><?php echo html_entity_decode($row->message); ?></p>
  
  <br /><br />
  <b><?php echo $row->user_login; ?> </b>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>
 
<hr>
    
    <?php
        endforeach;

?>
</div>
</div>
<?php
    }

    // disputed escrow list
    function aistore_disputed_escrow_list()
    {

        global $wpdb;
        $page_id = get_option('details_escrow_page_id');

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system WHERE status = 'disputed'");

?>
    <h1> <?php _e('All disputed escrows', 'aistore') ?> </h1>
	
	     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    
  <table  id="example" class="display nowrap" style="width:100%">
      
        <thead>
     <tr>
         <th>Id</th>
      <th>Title</th>
     <th> Status</th>
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
  
    
     </tr>
      </thead>
<tbody>
      

    <?php
        if ($results == null)
        {

            _e("No Escrow Found", 'aistore');

        }
        else
        {

            foreach ($results as $row):

                $url = admin_url('admin.php?page=disputed_escrow_details&eid=' . $row->id . '', 'https');

?>
      <tr>

		   
		   <td> 	 
		  <a href="<?php echo ($url); ?>">  <?php echo $row->id; ?></a></td>
		  
		   
		   <td> 		   <?php echo $row->title; ?> </td>
		  
		   <td> 		   <?php echo $row->status; ?> </td>
		  
		   
		   <td> 		   <?php echo $row->amount  . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo $row->sender_email; ?> </td>
		   <td> 		   <?php echo $row->receiver_email; ?> </td>
		  
              
            </tr>
            
            </tbody>
    <?php
            endforeach;
        }
?>

	

    </table>
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
	<?php
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
    

 
    function aistore_page_setting()
    {

        $pages = get_pages();

?>
	  <div class="wrap">
	  
	  <div class="card">
	  
<h3><?php _e('Escrow Setting', 'aistore') ?></h3>
 
	                    
<p><?php _e('Step 1', 'aistore') ?></p>


<?php
        if (isset($_POST['submit']) and $_POST['action'] == 'create_all_pages')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify.', 'aistore');

                exit;
            }
           

            $my_post = array(
                'post_title' => 'Create Escrow',
                'post_type' => 'page',
                'post_content' => '[aistore_escrow_system]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $add_escrow_page_id = wp_insert_post($my_post);

            update_option('add_escrow_page_id', $add_escrow_page_id);

            $my_post = array(
                'post_title' => 'Escrow List',
                'post_type' => 'page',
                'post_content' => '[aistore_escrow_list] ',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $list_escrow_page_id = wp_insert_post($my_post);

            update_option('list_escrow_page_id', $list_escrow_page_id);

            $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Escrow Detail',
                'post_content' => '[aistore_escrow_detail]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $details_escrow_page_id = wp_insert_post($my_post);

            update_option('details_escrow_page_id', $details_escrow_page_id);
            
         $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Bank Detail',
                'post_content' => '[aistore_bank_details]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $details_bank_page_id = wp_insert_post($my_post);

            update_option('bank_details_page_id', $details_bank_page_id);
            
            $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Escrow Notification',
                'post_content' => '[aistore_notification]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $notification_page_id = wp_insert_post($my_post);

            update_option('notification_page_id', $notification_page_id);
            
               $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Transaction History',
                'post_content' => '[aistore_transaction_history]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $aistore_transaction_history_page_id = wp_insert_post($my_post);

            update_option('aistore_transaction_history_page_id', $aistore_transaction_history_page_id);
            
            
              $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Withdrawal',
                'post_content' => '[aistore_saksh_withdrawal_system]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $aistore_saksh_withdrawal_system = wp_insert_post($my_post);

            update_option('aistore_saksh_withdrawal_system', $aistore_saksh_withdrawal_system);
            
             $my_post = array(
                'post_type' => 'page',
                'post_title' => 'Bank Account',
                'post_content' => '[aistore_bank_account]',
                'post_status' => 'publish',
                'post_author' => 1
            );

            // Insert the post into the database
            $aistore_bank_account = wp_insert_post($my_post);

            update_option('aistore_bank_account', $aistore_bank_account);
            
            
            //add currency
             global $wpdb;

            // add currency also
            $wpdb->query("INSERT INTO {$wpdb->prefix}escrow_currency ( currency, symbol  ) VALUES ( 'USD' ,'USD')");


             email_notification_message();


        }
        else
        {

?>
<table class="form-table">
 <form method="POST" action="" name="create_all_pages" enctype="multipart/form-data"> 
    <?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
    
<p><?php _e(' Create all pages with short codes automatically to ', 'aistore') ?>

<input class="input" type="submit" name="submit" value="<?php _e('Click Here', 'aistore') ?>"/>
<input type="hidden" name="action"  value="create_all_pages"/></td></tr>
    </form>
    </table>
    <hr>
<?php
        }
?>
<p><?php _e('Create 8 pages with short codes and select here  ', 'aistore') ?></p>


<form method="post" action="options.php">
    <?php settings_fields('aistore_page'); ?>
    <?php do_settings_sections('aistore_page'); ?>
	
    <table class="form-table">
	
	 <tr valign="top">
        <th scope="row"><?php _e('Create Escrow form', 'aistore') ?></th>
        <td>
		<select name="add_escrow_page_id"  >
		 
		 
     <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('add_escrow_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 
	 
	 
</select>


<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_escrow_system] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>

</td>
        </tr>  
        
        	
	
	
		
		  <tr valign="top">
        <th scope="row"><?php _e('Escrow List page', 'aistore') ?></th>
        <td>
		<select name="list_escrow_page_id">
		  
		   <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('list_escrow_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 

		   
		   
		   
</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_escrow_list] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>




</td>
        </tr>  
		
		
		 <tr valign="top">
        <th scope="row"><?php _e('Escrow Details Page', 'aistore') ?></th>
        <td>
		<select name="details_escrow_page_id" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('details_escrow_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 
	 
	 
		 
					  
					
 
</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_escrow_detail] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>





</td>
        </tr> 
        
        
        	 <tr valign="top">
        <th scope="row"><?php _e('Bank Details Page', 'aistore') ?></th>
        <td>
		<select name="bank_details_page_id" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('bank_details_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 
	 
	 
		 
					  
					
 
</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_bank_details] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>





</td>
        </tr>
        
        
        
        	 <tr valign="top">
        <th scope="row"><?php _e('Notification Page', 'aistore') ?></th>
        <td>
		<select name="notification_page_id" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('notification_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 

</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_notification] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>

</td>
        </tr>
        
        
        
        
        	 <tr valign="top">
        <th scope="row"><?php _e('Transaction History Page', 'aistore') ?></th>
        <td>
		<select name="aistore_transaction_history_page_id" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('aistore_transaction_history_page_id'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 

</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_transaction_history] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>

</td>
        </tr>
        
        
        
        
        	 <tr valign="top">
        <th scope="row"><?php _e('Withdraw Page', 'aistore') ?></th>
        <td>
		<select name="aistore_saksh_withdrawal_system" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('aistore_saksh_withdrawal_system'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 

</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_saksh_withdrawal_system] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>

</td>
        </tr>
        
        
        
        
        	 <tr valign="top">
        <th scope="row"><?php _e('Bank Account Page', 'aistore') ?></th>
        <td>
		<select name="aistore_bank_account" >
		 
		 
		  <?php
        foreach ($pages as $page)
        {

            if ($page->ID == get_option('aistore_bank_account'))
            {
                echo '	<option selected value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
            else
            {

                echo '	<option  value="' . $page->ID . '">' . $page->post_title . '</option>';

            }
        } ?> 

</select>

<p><?php _e('Create a page add this shortcode ', 'aistore') ?> <strong> [aistore_bank_account] </strong> <?php _e('and then select that page here.', 'aistore') ?> </p>

</td>
        </tr>
        
        
        </table>
        
        	<hr/>
        	
<p><?php _e('Step 2', 'aistore') ?></p>


<p><?php _e('Create an admin account and set its ID this will be used to hold payments ', 'aistore') ?></p>

        	
        <table class="form-table">
        
        <h3><?php _e('Admin Escrow Setting', 'aistore') ?></h3>
        
		 <tr valign="top">
        <th scope="row"><?php _e('Escrow Admin ID ', 'aistore') ?></th>
        <td>
		<select name="escrow_user_id" >
		 
		 
		  <?php
        $escrow_admin_user_id = get_option('escrow_user_id');
        $blogusers = get_users(['role__in' => ['administrator']]);

        foreach ($blogusers as $user)
        {

            if ($user->ID == $escrow_admin_user_id)
            {
                echo '	<option selected value="' . $user->ID . '">' . $user->display_name . '</option>';

            }
            else
            {

                echo '	<option  value="' . $user->ID . '">' . $user->display_name . '</option>';

            }
        } ?> 
  </tr>  
</select>
<?php 
 $_new_user_url = admin_url('user-new.php', 'https'); ?>
<p><?php _e('Add a new admin user with admin roll and then  ', 'aistore') ?><a href="<?php echo $_new_user_url; ?>">Click Here</a></p>

<p><?php _e('Add an user with admin roll and then select its ID here', 'aistore') ?></p>
 <tr valign="top">
 <th scope="row"><?php _e('Chat system public people show or not', 'aistore') ?></th>
        <td>
            <?php $escrow_message_page = get_option('escrow_message_page'); ?>
            
            <select name="escrow_message_page" id="escrow_message_page">
               
            <option selected value="yes" <?php selected($escrow_message_page, 'yes'); ?>>Yes</option>
            <option value="no" <?php selected($escrow_message_page, 'no'); ?>>No</option>
  
</select>
	
</td>
        </tr>  
        
        
        
 
        

 <tr valign="top">
 <th scope="row"><?php _e('Cancel Escrow fee refund or not ', 'aistore') ?></th>
        <td>
            <?php $cancel_escrow_fee = get_option('cancel_escrow_fee'); ?>
            
            <select name="cancel_escrow_fee" id="cancel_escrow_fee">
               
            <option selected value="yes" <?php selected($cancel_escrow_fee, 'yes'); ?>>Yes</option>
            <option value="no" <?php selected($cancel_escrow_fee, 'no'); ?>>No</option>
  
</select>
	
</td>
        </tr>  
              
  
    </table>
    
    <?php _e('  [ Admin who will manage escrow fee/disputes etc ]', 'aistore') ?>
  

    	<hr/>
        	 
        	
<p><?php _e('Step 3', 'aistore') ?></p>


<p><?php _e('Set fee here for the profits percentage ', 'aistore') ?></p>


        <table class="form-table">
        
        <h3><?php _e('Escrow Fee Setting', 'aistore') ?></h3>
        
	 <tr valign="top">
        <th scope="row"><?php _e('Escrow Create Fee', 'aistore') ?></th>
        <td><input type="number" name="escrow_create_fee" value="<?php echo esc_attr(get_option('escrow_create_fee')); ?>" />%</td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php _e('Escrow Accept Fee', 'aistore') ?></th>
        <td><input type="number" name="escrow_accept_fee" value="<?php echo esc_attr(get_option('escrow_accept_fee')); ?>" />%</td>
        </tr>
        
       
  
  
    </table>
    
    <hr>
    
    <p><?php _e('Step 4', 'aistore') ?></p>





        <table class="form-table">
        
        <h3><?php _e('Bank Account Details', 'aistore') ?></h3>
        

           <tr valign="top">
        <th scope="row"><?php _e('Bank Details', 'aistore') ?></th>
        <td>
             <textarea id="bank_details" name="bank_details" rows="2" cols="50">
<?php echo esc_attr(get_option('bank_details')); ?>
</textarea>
        </td>
        </tr>
        
           <tr valign="top">
        <th scope="row"><?php _e('Deposit Instructions', 'aistore') ?></th>
        <td>
             <textarea id="deposit_instructions" name="deposit_instructions" rows="2" cols="50">
<?php echo esc_attr(get_option('deposit_instructions')); ?>
</textarea>
        </td>
        </tr>
       
  
  
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
</div>
 <?php
    }

    function aistore_notification_setting()
    {
?>
      <h3><?php _e('Notification Setting', 'aistore') ?></h3>
      
<form method="post" action="options.php">
    <?php settings_fields('aistore_notification_page'); ?>
    <?php do_settings_sections('aistore_notification_page'); ?>
    
       <table class="form-table">
        
     
        
	 <tr valign="top">
        <th scope="row"><?php _e('Created Escrow', 'aistore') ?></th>
        <td>
            <textarea id="created_escrow" name="created_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('created_escrow')); ?>
</textarea>
          </td>
        </tr>
        
         <tr valign="top">
        <th scope="row"><?php _e('Partner Created Escrow', 'aistore') ?></th>
        <td>
             <textarea id="partner_created_escrow" name="partner_created_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_created_escrow')); ?>
</textarea>
           </td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php _e('Accept Escrow', 'aistore') ?></th>
        <td>
             <textarea id="accept_escrow" name="accept_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('accept_escrow')); ?>
</textarea>
            </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Accept Escrow', 'aistore') ?></th>
        <td>
              <textarea id="partner_accept_escrow" name="partner_accept_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_accept_escrow')); ?>
</textarea>
           </td>
        </tr>
  
  
      
      <tr valign="top">
        <th scope="row"><?php _e('Dispute Escrow', 'aistore') ?></th>
        <td>
             <textarea id="dispute_escrow" name="dispute_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('dispute_escrow')); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Dispute Escrow', 'aistore') ?></th>
        <td>
              <textarea id="partner_dispute_escrow" name="partner_dispute_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_dispute_escrow')); ?>
</textarea>
          </td>
        </tr>
  
  
  
     
      <tr valign="top">
        <th scope="row"><?php _e('Release Escrow', 'aistore') ?></th>
        <td>
             <textarea id="release_escrow" name="release_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('release_escrow')); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Release Escrow', 'aistore') ?></th>
        <td>
              <textarea id="partner_release_escrow" name="partner_release_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_release_escrow')); ?>
</textarea>
          </td>
        </tr>
        
        
             <tr valign="top">
        <th scope="row"><?php _e('Cancel Escrow', 'aistore') ?></th>
        <td>
             <textarea id="cancel_escrow" name="cancel_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('cancel_escrow')); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Cancel Escrow', 'aistore') ?></th>
        <td>
              <textarea id="partner_cancel_escrow" name="partner_cancel_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_cancel_escrow')); ?>
</textarea>
          </td>
        </tr>
        
        
        
             <tr valign="top">
        <th scope="row"><?php _e('Buyer Deposit', 'aistore') ?></th>
        <td>
             <textarea id="buyer_deposit" name="buyer_deposit" rows="2" cols="50">
<?php echo esc_attr(get_option('buyer_deposit')); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Seller Deposit', 'aistore') ?></th>
        <td>
              <textarea id="seller_deposit" name="seller_deposit" rows="2" cols="50">
<?php echo esc_attr(get_option('seller_deposit')); ?>
</textarea>
          </td>
        </tr>
        
        
        
        
           <tr valign="top">
        <th scope="row"><?php _e('Shipping Escrow', 'aistore') ?></th>
        <td>
             <textarea id="shipping_escrow" name="shipping_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('shipping_escrow')); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Shipping Escrow', 'aistore') ?></th>
        <td>
              <textarea id="partner_shipping_escrow" name="partner_shipping_escrow" rows="2" cols="50">
<?php echo esc_attr(get_option('partner_shipping_escrow')); ?>
</textarea>
          </td>
        </tr>
        
                  <tr valign="top">
        <th scope="row"><?php _e('Buyer Mark Paid', 'aistore') ?></th>
        <td>
              <textarea id="Buyer_Mark_Paid" name="Buyer_Mark_Paid" rows="2" cols="50">
<?php echo esc_attr(get_option('Buyer_Mark_Paid')); ?>
</textarea>
          </td>
        </tr>
  
    </table>
       <?php submit_button(); ?>

</form>
      <?php
    }

 

    function aistore_email_setting()
    {

?>
      <h3><?php _e('Email Setting', 'aistore') ?></h3>
        
  <?php
        $editor = array(
            'tinymce' => array(
                'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
                'toolbar2' => '',
                'toolbar3' => ''

            ) ,
            'textarea_rows' => 1,
            'teeny' => true,
            'quicktags' => false,
            'media_buttons' => false
        );

?>
<form method="post" action="options.php">
    <?php settings_fields('aistore_email_page'); ?>
    <?php do_settings_sections('aistore_email_page'); ?>
    
       <table class="form-table">
        
     
        
	 <tr valign="top">
        <th scope="row"><?php _e('Created Escrow', 'aistore') ?></th>
        <td>
              <?php
        $content = esc_attr(get_option('email_created_escrow'));;
        $editor_id = 'email_created_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
          </td>
        </tr>
        
         <tr valign="top">
        <th scope="row"><?php _e('Partner Created Escrow', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_partner_created_escrow'));;
        $editor_id = 'email_partner_created_escrow';

        wp_editor($content, $editor_id, $editor);

?>
           </td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php _e('Accept Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_accept_escrow'));;
        $editor_id = 'email_accept_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
            </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Accept Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_partner_accept_escrow'));;
        $editor_id = 'email_partner_accept_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
           </td>
        </tr>
  
  
      
      <tr valign="top">
        <th scope="row"><?php _e('Dispute Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_dispute_escrow'));;
        $editor_id = 'email_dispute_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Dispute Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_partner_dispute_escrow'));;
        $editor_id = 'email_partner_dispute_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
           
          </td>
        </tr>
  
  
  
     
      <tr valign="top">
        <th scope="row"><?php _e('Release Escrow', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_release_escrow'));;
        $editor_id = 'email_release_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Release Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_partner_release_escrow'));;
        $editor_id = 'email_partner_release_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
          </td>
        </tr>
        
        
             <tr valign="top">
        <th scope="row"><?php _e('Cancel Escrow', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_cancel_escrow'));;
        $editor_id = 'email_cancel_escrow';

        wp_editor($content, $editor_id, $editor);

?>
         
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Cancel Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_partner_cancel_escrow'));;
        $editor_id = 'email_partner_cancel_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
          </td>
        </tr>
        
        
        
             <tr valign="top">
        <th scope="row"><?php _e('Buyer Deposit', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_buyer_deposit'));;
        $editor_id = 'email_buyer_deposit';

        wp_editor($content, $editor_id, $editor);

?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Seller Deposit', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_seller_deposit'));;
        $editor_id = 'email_seller_deposit';

        wp_editor($content, $editor_id, $editor);

?>
          </td>
        </tr>
        
        
        
        
           <tr valign="top">
        <th scope="row"><?php _e('Shipping Escrow', 'aistore') ?></th>
        <td>
            <?php
        $content = esc_attr(get_option('email_shipping_escrow'));;
        $editor_id = 'email_shipping_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php _e('Partner Shipping Escrow', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_partner_shipping_escrow'));;
        $editor_id = 'email_partner_shipping_escrow';

        wp_editor($content, $editor_id, $editor);

?>
          
            
          </td>
        </tr>
        
                  <tr valign="top">
        <th scope="row"><?php _e('Buyer Mark Paid', 'aistore') ?></th>
        <td>
            
            <?php
        $content = esc_attr(get_option('email_Buyer_Mark_Paid'));;
        $editor_id = 'email_Buyer_Mark_Paid';

        wp_editor($content, $editor_id, $editor);

?>
          
          </td>
        </tr>
  
    </table>
       <?php submit_button(); ?>

</form>
      <?php
    }

  
    function aistore_payment_process()
    {
        
        ?>
         <div id="row ">
<div id="col-md-6"   >
<?php
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system  order by id desc");

?>
  <h1> <?php _e('Escrow Payment', 'aistore') ?> </h1>
  
  <br>
  <p> <?php _e('<strong>Note : </strong>  To approve  or reject an escrow payment , in the escrow payment to click Approve button or Reject button', 'aistore') ?></p><br>
     
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    
  <table  id="example" class="display nowrap" style="width:100%">
         <thead>
     <tr>
         <th>Id</th>
      <th>Title</th>
    
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
        <th>Payment Status</th>
       <th>Date</th>
       <th>Action</th>
     </tr>
      </thead>
<tbody>
    <?php

        if ($results == null)
        {
            _e("No Escrow Found", 'aistore');

        }
        else
        {
            foreach ($results as $row):

?> 
      <tr>

		   <td> 	
		   
		   <?php echo esc_attr($row->id); ?></td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->title); ?> </td>
		  
		   <!--<td> 		   <?php echo esc_attr($row->status); ?> </td>-->
		   
		   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo esc_attr($row->sender_email); ?> </td>
		   <td> 		   <?php echo esc_attr($row->receiver_email); ?> </td>
		      <td> 		   <?php echo esc_attr($row->payment_status); ?> </td>
		     <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
       
                <td><?php
                if (isset($_POST['submit']) and $_POST['action'] == 'escrow_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                    $eid =   sanitize_text_field($_REQUEST['ecsrow_id']);
                     $object_escrow = new AistoreEscrowSystem();
                       $escrow_admin_user_id = $object_escrow->get_escrow_admin_user_id();
                    
                      $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s ", $eid));
                      
                      
                       $aistore_escrow_currency = $escrow->currency;
                      $escrow_amount = $escrow->amount;
                      $escrow_fee = $escrow->escrow_fee;
                       $sender_email = $escrow->sender_email;
            $user = get_user_by('email', $sender_email);
            $sender_id = $user->ID;
                      $escrow_details = 'Admin Send Payment To User Account';
                      
                       $escrow_wallet = new AistoreWallet();
                       
                    $new_amount = $escrow_fee+$escrow_amount;
                    
            $escrow_wallet->aistore_debit($escrow_admin_user_id, $new_amount, $aistore_escrow_currency, $escrow_details);
            

            $escrow_wallet->aistore_credit($sender_id, $new_amount, $aistore_escrow_currency, $escrow_details); 
                    
                    
                    
                     $escrow_details = 'User Send Payment to Admin';
                    
                        $escrow_wallet->aistore_debit($sender_id, $new_amount, $aistore_escrow_currency, $escrow_details);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $new_amount, $aistore_escrow_currency, $escrow_details); 
                    
                    
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'paid'  WHERE id = '%d' ", $eid));
    
    
    
    

                    echo esc_attr($row->payment_status);

                }
                
                
                
                      if (isset($_POST['submit']) and $_POST['action'] == 'reject_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                           $eid =   sanitize_text_field($_REQUEST['reject_ecsrow_id']);
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'Rejected'  WHERE id = '%d' ", $eid));

                    echo esc_attr($row->payment_status);

                }
                
                if (isset($_POST['submit']) and $_POST['action'] == 'remove_escrow_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                           $eid =   sanitize_text_field($_REQUEST['ecsrow_id']);
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'Pending'  WHERE id = '%d' ", $eid));

                    echo esc_attr($row->payment_status);

                }
                else
                {
?>

<?php
if($row->payment_status=='process'){
    ?>
    <form method="POST" action="" name="escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
	<input type="hidden" name="ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Approve Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="escrow_payment" />
                </form>
             
                
                 <form method="POST" action="" name="reject_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="reject_ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Reject Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="reject_payment" />
                </form>
                   <?php } 
                   
                   if($row->payment_status=='paid'){
    ?>
                   
                 <form method="POST" action="" name="remove_escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Remove Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="remove_escrow_payment" />
                </form>
                  <?php } 
                } ?></td>
            </tr>
    <?php
            endforeach;
        }

?>


</tbody>
    <tfoot>
             <tr>
         <th>Id</th>
      <th>Title</th>
    
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
        <th>Payment Status</th>
       <th>Date</th>
       <th>Action</th>
     </tr>
        </tfoot>
    </table>
    
    </div>
  
    </div>
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
	<?php
    }

}

if (is_admin()) $AistoreEscrowSettings = new AistoreEscrowSettings();

