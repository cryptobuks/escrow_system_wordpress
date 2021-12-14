<?php


class Aistore_WithdrawalSystem {
    
      public  static function aistore_bank_account()
{ 
     if(isset($_POST['submit']) and $_POST['action']=='bank_account_details' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 


    $user_id=get_current_user_id();
    
   // echo sanitize_text_field($_POST['user_bank_detail']);
 update_user_meta( $user_id, 'user_bank_detail', sanitize_text_field($_POST['user_bank_detail']) );
 update_user_meta( $user_id, 'user_deposit_instruction', sanitize_text_field($_POST['user_deposit_instruction']) );

     update_user_meta( $user_id, 'lock_bank_details', sanitize_text_field($_POST['lock_bank_details']) );
    
    
    ?>

<?php
}
else{
     
$user_id=get_current_user_id();
    $user = get_user_by( 'id', $user_id);
    ?>
    
    
<h3><?php _e("Add Bank Details", "blank"); ?></h3>
 <form method="POST" action="" name="bank_account_details" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

  <label for="user_bank_details"><?php _e("Bank Account Details"); ?></label><br>
        
        
        
           <?php
       if( esc_attr( get_the_author_meta( 'lock_bank_details', $user->ID ) )==0){
            
            ?>
   <textarea id="user_bank_detail" name="user_bank_detail" rows="2" cols="50">
<?php echo esc_attr(get_the_author_meta('user_bank_detail', $user->ID)); ?>
</textarea>
  <br />
            
  <?php
        }
        
        else{
    ?>
           <textarea id="user_bank_detail" name="user_bank_detail" rows="2" cols="50" readonly="true">
<?php echo esc_attr(get_the_author_meta('user_bank_detail', $user->ID)); ?>
</textarea><br />
            
            <?php } ?>
          
            
            <br>
            
      <label for="bank_account"><?php _e("Deposit Instructions"); ?></label><br>
        
         <?php
       if( esc_attr( get_the_author_meta( 'lock_bank_details', $user->ID ) )==0){
            
            ?>
    <textarea id="user_deposit_instruction" name="user_deposit_instruction" rows="2" cols="50" >
<?php echo esc_attr(get_the_author_meta('user_deposit_instruction', $user->ID)); ?>
</textarea><br />
            
  <?php
        }
        
        else{
    ?>
           <textarea id="user_deposit_instruction" name="user_deposit_instruction" rows="2" cols="50" readonly="true">
<?php echo esc_attr(get_the_author_meta('user_deposit_instruction', $user->ID)); ?>
</textarea><br />
            
            
            <?php } ?>
           
             <br>
             

             <?php
    if( esc_attr( get_the_author_meta( 'lock_bank_details', $user->ID ) )==0){
        
    ?>
              <input type="checkbox" id="lock_bank_details" name="lock_bank_details" value="1">
               <label for="lock_bank_details"> <?php _e( 'Lock Bank Details', 'aistore' ); ?> </label><br><br>
    
   
       <input 
 type="submit"  name="submit" value="<?php  _e( 'Submit', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="bank_account_details" />

<?php
}?>

    </form>
<?php
}
}



public static function aistore_saksh_withdrawal_system()
{ 
   if ( !is_user_logged_in() ) {
   
   return   "Please login to start ";
    
 
}


 global $wpdb;   
  
  $wallet = new AistoreWallet();
        $object_escrow = new AistoreEscrowSystem();
    
$user_id=get_current_user_id();

if(isset($_POST['submit']) and $_POST['action']=='withdrawal_request' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 

    $user_id=get_current_user_id();
      $description="Withdraw Balance";
      
      
$aistore_currency=sanitize_text_field($_REQUEST['aistore_currency']);
$amount=intval($_REQUEST['amount']);


$username = get_the_author_meta( 'user_email', get_current_user_id() );
  $balance = $wallet->aistore_balance($user_id, $aistore_currency);

  $withdraw = get_option('withdraw_fee');
 $withdraw_fee = ($withdraw / 100) * $amount;
$escrow_admin_user_id = get_option('escrow_user_id');

$new_amount = $amount+ $withdraw_fee;
if($balance>=$new_amount){
    
  

$res=( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}widthdrawal_requests ( amount,username,currency  ) VALUES ( %s, %s, %s)", array(  $amount, $username,$aistore_currency ) ) );

$wpdb->query($res);
$wid = $wpdb->insert_id;

 $wallet->aistore_debit($user_id, $amount, $aistore_currency, $description,$wid);
  
   $description="Withdraw Fee"; 
$wallet->aistore_debit($user_id, $withdraw_fee, $aistore_currency, $description,$wid);
$wallet->aistore_credit($escrow_admin_user_id, $withdraw_fee, $aistore_currency, $description,$wid);

// email to sender 


$to = $username;
$subject ="Withdrawal Request";

$user_id=get_current_user_id();
    $user = get_user_by( 'id', $user_id);
    
	 $withdraw_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => 449 ,
	'wid'=> $wid,
), home_url() ) );


 $body="Hello, <br>
 
     <h2> withdraw request have successfully for the withdraw ID ".$wid." </h2>".
     
     "<br>Withdraw ID is: ".$wid.
      "<br>Amount: ".$amount.
     "<br>Process Withdraw system to :<br>".
         $withdraw_page_id_url."<br>" ;
    
   $body.=" <br><h2> Bank Account  Details </h2><br>
   <table>
    <tr><td>Bank Details :</td></tr>
    <tr><td>".$user->user_bank_details."</td></tr>
    
    <tr><td>Deposit Instructions :</td></tr>
    <tr><td>".$user->user_deposit_instructions."</td></tr>

</table>

   ";
  
  //$body.=__( 'Your Recevier Email'.$receiver_email, 'aistore' );
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

}

else{
    _e( 'Insufficient Balance', 'aistore' ); 
}


    ?>
    
   

<?php
}
else{
?>

   <form method="POST" action="" name="withdrawal_request" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

                
                 

<?php 
     $user_id=get_current_user_id();


    ?>
    <h6><?php  _e( 'Withdrawal Request', 'aistore' );?> </h6>
  <label for="amount"><?php   _e( 'Amount', 'aistore' ); ?></label><br>
  
  <input class="input" type="number" id="amount" name="amount" min="1"  required  class="form-control" style="width:100%;" ><br>

  <label for="title"><?php _e('Currency', 'aistore'); ?></label><br>
  <?php
            global $wpdb;
            $wallet = new AistoreWallet();
        $results = $wallet->aistore_wallet_currency();
        
        
?>
       <select name="aistore_currency" id="aistore_currency" >
                <?php
            foreach ($results as $c)
            {

                echo '	<option  value="' . $c->symbol . '">' . $c->currency . '</option>';

            }
?>
           
  
</select><br>
  <?php

?>


<?php 
    _e( 'Account balance is:', 'aistore' ) ;
       
        
 global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_currency  order by id desc"
);
 foreach ($results as $row):
$currency=  $row->currency; 
 $wallet = new AistoreWallet();

        $balance = $wallet->aistore_balance($user_id, $currency);

         echo "<br>".$balance . " " . $currency;
       
   endforeach;

 ?>
  
 


<br>
	<br>
<input 
 type="submit"  name="submit" value="<?php  _e( 'Withdrawal', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="withdrawal_request" />
</form> 
<br>
<hr>

<h6> <?php _e( 'Bank Account Details', 'aistore' ); ?></h6>
<?php
$user_id=get_current_user_id();
    $user = get_user_by( 'id', $user_id);
    
    if($user->user_bank_details==="NULL"){
       _e( 'Please Add Bank Account Details', 'aistore' );
    }
    else{
?>
<table>
    <tr><td>Bank Details :</td></tr>
    <tr><td><?php echo esc_attr(get_the_author_meta('user_bank_detail')); ?></td></tr>
    
    <tr><td>Deposit Instructions :</td></tr>
    <tr><td><?php echo esc_attr(get_the_author_meta('user_deposit_instruction')); ?></td></tr>
</table>
<?php
}
?>

<br>
<hr>
<h6><?php _e( 'Withdraw Report', 'aistore' ); ?></h6>
<?php
$current_user_email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

  $results = $wpdb->get_results( 
                     $wpdb->prepare("SELECT * FROM {$wpdb->prefix}widthdrawal_requests WHERE username=%s order by id desc limit 100", $current_user_email_id) 

                 );


 
 if($results==null)
	{
	      echo "<div class='no-result'>";
	      
	     _e( 'Withdrawal List Not Found', 'aistore' ); 
	  echo "</div>";
	}
	else{
   
  ob_start();
     
  ?>
  
    <table class="table">
     
        <tr>
      
    <th><?php   _e( 'ID', 'aistore' ); ?></th>
        <th><?php   _e( 'Amount', 'aistore' ); ?></th>
        
		    <th><?php   _e( 'Status', 'aistore' ); ?></th>  
		    <th><?php   _e( 'Date', 'aistore' ); ?></th>
		 
</tr>

    <?php 
    
     
   
    foreach($results as $row):
  
	
 ?>
 
 
      
    
      <tr>
           
		 
		   
		   
		   <td> 

		   <?php echo esc_attr($row->id) ; ?></td>

   
		   
		  	   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency;?>  </td>
		  
	
		    <td> 		   <?php echo esc_attr($row->status) ; ?> </td>
		    	   <td> 		   <?php echo esc_attr($row->created_at); ?> </td>

            </tr>
    <?php endforeach;
	
	}?>

    </table>
<?php
}

}
}

?>