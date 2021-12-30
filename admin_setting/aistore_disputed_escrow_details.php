<?php
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








//
  global $wpdb;
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
                      $escrow_details = 'Send Payment To User Account  with escrow id # '.$eid;
                      
                       $escrow_wallet = new AistoreWallet();
                       
                    $new_amount = $escrow_fee+$escrow_amount;
                    
  
            

            $escrow_wallet->aistore_credit($sender_id, $new_amount, $aistore_escrow_currency, $escrow_details,$eid); 
                    
                    
                     $created_escrow_message = get_option('created_escrow_message');
        $escrow_details =$created_escrow_message .$eid;
                
                    
                        $escrow_wallet->aistore_debit($sender_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid); 
                    
                    
                     $escrow_details = 'Escrow Fee for the created escrow with escrow id #'.$eid;
                    
          $escrow_wallet->aistore_debit($sender_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid); 
            
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'paid'  WHERE id = '%d' ", $eid));
    
    
    
     sendNotificationPaymentAccepted($eid);

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

                    
                }
                
                if (isset($_POST['submit']) and $_POST['action'] == 'remove_escrow_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                           $eid =   sanitize_text_field($_REQUEST['ecsrow_id']);
                           
                        $escrow_wallet = new AistoreWallet();
                        $object_escrow = new AistoreEscrowSystem();
                        
                          $escrow_admin_user_id = $object_escrow->get_escrow_admin_user_id();
                    
                      $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s ", $eid));
                      
                      
                       $aistore_escrow_currency = $escrow->currency;
                      $escrow_amount = $escrow->amount;
                      $escrow_fee = $escrow->escrow_fee;
                       $sender_email = $escrow->sender_email;
            $user = get_user_by('email', $sender_email);
            $sender_id = $user->ID;
            
              $created_escrow_message = get_option('cancel_escrow_message');
        $escrow_details =$created_escrow_message .$eid;
                
                    
            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($sender_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid); 
                    
                    
            $escrow_details = 'Escrow Fee for the cancelled escrow with escrow id # '.$eid;
                    
          $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($sender_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid); 
            
            
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'Pending'  WHERE id = '%d' ", $eid));

                     sendNotificationPaymentRefund($eid);

                }
             
             
             
             ///////


        if (isset($_POST['submit']) and $_POST['action'] == 'disputed')
        {
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = 'disputed'  WHERE id = '%d'", $eid));
      $dispute_escrow_success_message = get_option('dispute_escrow_success_message');
sendNotificationDisputed($eid);
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
               $escrow_fee_deducted = get_option('escrow_fee_deducted');
               
             if($escrow_fee_deducted == 'accepted'){
            $escrow_wallet = new AistoreWallet();
            $escrow_wallet->aistore_debit($user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);
            
            
}

       
            
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
            $aistore_escrow_currency = $escrow->currency;
            $escrow_reciever_email_id = $escrow->receiver_email;
            $escrow_fee = $escrow->escrow_fee;
            $user = get_user_by('email', $escrow_reciever_email_id);
            $id = $user->ID;
            $release_escrow_message = get_option('release_escrow_message');
            $escrow_details = $release_escrow_message . $eid;
              $escrow_wallet = new AistoreWallet();
              
                 $escrow_fee_deducted = get_option('escrow_fee_deducted');
           if($escrow_fee_deducted == 'released'){

            $escrow_wallet->aistore_debit($id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details,$eid); // change variable name
            
}

          
          

            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);
            
            $release_escrow_success_message = get_option('release_escrow_success_message');
            sendNotificationReleased($eid);
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
            
   $url = admin_url('admin.php?page=disputed_escrow_details&eid=' . $escrow_id . '', 'https');
   ?>
   <meta http-equiv="refresh" content="0; URL=<?php echo esc_html($url); ?>" />
   <?php
             
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
            $aistore_escrow_currency = $escrow->currency;
            
            $sender_escrow_fee = $escrow->escrow_fee;
            $sender_email = $escrow->sender_email;
            $user = get_user_by('email', $sender_email);
            $sender_id = $user->ID;
            $cancel_escrow_message = get_option('cancel_escrow_message');
            $escrow_details = $cancel_escrow_message . $eid;
  

            $escrow_wallet = new AistoreWallet();

            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);

            $escrow_wallet->aistore_credit($sender_id, $escrow_amount, $aistore_escrow_currency, $escrow_details,$eid);

            $cancel_escrow_fee = get_option('cancel_escrow_fee');

            if ($cancel_escrow_fee == 'yes')
            {
               $escrow_wallet->aistore_debit($escrow_admin_user_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);

              $escrow_wallet->aistore_credit($sender_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details,$eid);
            
             $cancel_escrow_success_message = get_option('cancel_escrow_success_message');
              sendNotificationCancelled($eid);
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
         printf(__("Payment Status : %s", 'aistore') , $escrow->payment_status . "<br>");
        printf(__("Status : %s", 'aistore') , $escrow->status . "<br><br>");

        $object = new AistoreEscrowSystem();

        $object->accept_escrow_btn($escrow);

        $object->cancel_escrow_btn($escrow);

        $object->release_escrow_btn($escrow);
        
        

        $object->dispute_escrow_btn($escrow);
        
        
        
        

        $eid = $escrow->id;
        
        if($escrow->payment_status=='processing'){
    ?>
    <br><br>
    <form method="POST" action="" name="escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
	<input type="hidden" name="ecsrow_id" value="<?php echo $escrow->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Approve Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="escrow_payment" />
                </form>
             
                
                 <form method="POST" action="" name="reject_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="reject_ecsrow_id" value="<?php echo $escrow->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Reject Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="reject_payment" />
                </form>
                   <?php
                   
    
} 
                   
                   if($escrow->payment_status=='paid' && $escrow->status=='pending' ){
    ?>
                   
                 <form method="POST" action="" name="remove_escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="ecsrow_id" value="<?php echo $escrow->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Remove Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="remove_escrow_payment" />
                </form>
              
           
    <?php
            
        } 
        
        
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
	$eid=  $escrow->id;
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



<br><br>


<!--<div class="card col-12">-->
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    <?php
    

	global $wpdb;
		$eid=  $escrow->id;
           	
           	        // $escrow_admin_user_id = get_option('escrow_user_id');
           	        
     	$sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE  {$wpdb->prefix}aistore_wallet_transactions.reference=".$eid;
     	
     //	echo $sql;
     	
     	 $results = $wpdb->get_results($sql);
           ?>
       <h1> <?php _e('Transaction Report', 'aistore') ?> </h1>
   
    
    

            <?php
            
  
        if ($results == null)
        {
            _e("No transactions Found", 'aistore');

        }
        else
        {
            ?>
            
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
    
    
        <?php } ?>
    
    
    
    
    <br><br>
     <h1> <?php _e('All Notification', 'aistore') ?> </h1>  <br>
     <?php
      
	global $wpdb;
           		$eid=  $escrow->id;
 $sql = "SELECT * FROM {$wpdb->prefix}escrow_notification WHERE  {$wpdb->prefix}escrow_notification.reference_id=".$eid." order by id desc";
 
     	 $results = $wpdb->get_results($sql);
     	  if ($results == null)
        {
            _e("No Notification Found", 'aistore');

        }
        ?>
          <table  id="example1" class="display nowrap" style="width:100%">
      
        <thead>
     <tr>
         <th>Id</th>
      <th>Email</th>
      
     <th> Message</th>
        <th>Date</th>
   
    
     </tr>
      </thead>
<tbody>
     <?php
 	foreach ($results as $row):
            
?> 
  
    <tr>
        <td> 	 
		   <?php echo $row->id; ?></td>
           <td> 	 
		   <?php echo $row->user_email; ?></td>
		   <td> <?php echo html_entity_decode($row->message); ?></td>
		     <td><?php echo $row->created_at; ?></td>

    </tr>
            
            </tbody>
    <?php
        endforeach;
    ?>
    
      <tfoot>
            <tr>
       <th>Id</th>
      <th>Email</th>
     <th> Message</th>
        <th>Date</th>
            </tr>
        </tfoot>
        </table>
     <br><br>
    
    
    
    
    
    
     <h1> <?php _e('All Email', 'aistore') ?> </h1>  <br>
     <?php
      
	global $wpdb;
           		$eid=  $escrow->id;
 $sql = "SELECT * FROM {$wpdb->prefix}escrow_email WHERE  {$wpdb->prefix}escrow_email.reference_id=".$eid." order by id desc";
 
     	 $results = $wpdb->get_results($sql);
     	  if ($results == null)
        {
            _e("No Email Found", 'aistore');

        }
        ?>
          <table  id="example2" class="display nowrap" style="width:100%">
      
        <thead>
     <tr>
         <th>Id</th>
      <th>Email</th>
           <th>Subject</th>
     <th> Message</th>
        <th>Date</th>
   
    
     </tr>
      </thead>
<tbody>
     <?php
 	foreach ($results as $row):
            
?> 
  
    <tr>
        <td> 	 
		   <?php echo $row->id; ?></td>
           <td> 	 
		   <?php echo $row->user_email; ?></td>
		    <td> 	 
		   <?php echo $row->subject; ?></td>
		   <td> <?php echo html_entity_decode($row->message); ?></td>
		     <td><?php echo $row->created_at; ?></td>

    </tr>
            
            </tbody>
    <?php
        endforeach;
    ?>
    
      <tfoot>
            <tr>
       <th>Id</th>
      <th>Email</th>
        <th>Subject</th>
     <th> Message</th>
        <th>Date</th>
            </tr>
        </tfoot>
        </table>
     <br><br>
    
    
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

  $(document).ready(function() {
    $('#example1').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );


  $(document).ready(function() {
    $('#example2').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
<!--</div>-->
</div>