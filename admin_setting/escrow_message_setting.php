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