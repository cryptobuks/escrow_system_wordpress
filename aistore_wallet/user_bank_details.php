<?php
add_action( 'show_user_profile', 'aistore_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'aistore_extra_user_profile_fields' );

function aistore_extra_user_profile_fields( $user ) { ?>
    <h3><?php _e("Add Bank Details", "blank"); ?></h3>

    <table class="form-table">
    <tr>
        <th><label for="user_bank_detail"><?php _e("Bank Account Details"); ?></label></th>
        <td>
             <textarea id="user_bank_detail" name="user_bank_detail" rows="2" cols="50">
<?php echo esc_attr(get_the_author_meta('user_bank_detail', $user->ID)); ?>
</textarea>
        </td>
    </tr>
    <tr>
        <th><label for="user_deposit_instruction"><?php _e("Deposit Instructions"); ?></label></th>
        <td>
            <textarea id="user_deposit_instruction" name="user_deposit_instruction" rows="2" cols="50" >
<?php echo esc_attr(get_the_author_meta('user_deposit_instruction', $user->ID)); ?>
</textarea>
        </td>
    </tr>
    
    
         <tr>
    <th><label for="lock_bank_details"><?php _e(" Lock Bank Details"); ?></label></th>
        <td>
             <?php
    if( esc_attr( get_the_author_meta( 'lock_bank_details', $user->ID ) )==0){
        
    ?>
              <input type="checkbox" id="lock_bank_details" name="lock_bank_details" value="1"><br />
       <?php }
       else{
           ?>
           <input type="checkbox" id="lock_bank_details" name="lock_bank_details" value="1" checked><br />
           <?php }
           ?>
       
        </td>
    </tr>
  
    </table>
<?php }



add_action( 'personal_options_update', 'aistore_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'aistore_save_extra_user_profile_fields' );

function aistore_save_extra_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    
    if ( !current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    
 update_user_meta( $user_id, 'user_bank_detail', sanitize_text_field($_POST['user_bank_detail']) );

 update_user_meta( $user_id, 'user_deposit_instruction', sanitize_text_field($_POST['user_deposit_instruction'] ));
  update_user_meta( $user_id, 'lock_bank_details', sanitize_text_field($_POST['lock_bank_details'] ));
}

