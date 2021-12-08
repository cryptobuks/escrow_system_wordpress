<?php
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