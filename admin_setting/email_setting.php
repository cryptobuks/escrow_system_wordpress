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