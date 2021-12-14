<?php

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
            
            $escrow_user_id=sanitize_text_field($_REQUEST['escrow_user_id']);
             $user_id = username_exists( $escrow_user_id );
   
        if ( ! $user_id ) {
        $user_id = wp_insert_user( array(
          'user_login' => $escrow_user_id,
          'user_pass' => $escrow_user_id,
          'user_email' => $escrow_user_id,
          'first_name' => $escrow_user_id,
          'last_name' => $escrow_user_id,
          'display_name' => $escrow_user_id,
          'role' => 'administrator'
        ));
        
        update_option( 'escrow_user_id', $user_id);
        update_option( 'escrow_user_name', $escrow_user_id);
         update_option( 'escrow_file_type', 'pdf');
        }  
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
    
<p><?php _e(' Create all pages with short codes automatically to ', 'aistore')?>
<br><br>
<?php  _e( 'Escrow Admin Email ID: ', 'aistore' ) ?>
<input type="email" name="escrow_user_id" value="<?php echo esc_attr( get_option('escrow_user_name') ); ?>" required />

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
        
        
        
         <tr valign="top">
 <th scope="row"><?php _e('Upload File type', 'aistore') ?></th>
        <td>
            <?php $escrow_file_type = get_option('escrow_file_type'); ?>
            
            <select name="escrow_file_type" id="escrow_file_type">
                
               
            <option selected value="pdf" <?php selected($escrow_file_type, 'pdf'); ?>>pdf</option>
            <option value="xlsx" <?php selected($escrow_file_type, 'xlsx'); ?>>xlsx</option>
  
   <option value="ppt" <?php selected($escrow_file_type, 'ppt'); ?>>ppt</option>
      <option value="doc" <?php selected($escrow_file_type, 'doc'); ?>>doc</option>
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
        
         <tr valign="top">
        <th scope="row"><?php _e('Withdraw Fee', 'aistore') ?></th>
        <td><input type="number" name="withdraw_fee" value="<?php echo esc_attr(get_option('withdraw_fee')); ?>" />%</td>
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

