<?php
/*
  Plugin Name: Saksh WP SMTP
  Version: 4.1.1
  Plugin URI: #
  Author: susheelhbti
  Author URI: http://www.aistore2030.com/
  Description: Integrate wordpress to your mandrill , sendgrid , getresponse, email-marketing247 SMTP Server, Amazon SES or any SMTP Server.
 */

function my_scripts_method() {
  
    wp_enqueue_script( 'aistore', plugins_url( '/js/custom.js' , __FILE__ ), array( 'jquery' ) );
}


add_action( 'wp_enqueue_scripts', 'my_scripts_method' );



 include "setting.php";


add_shortcode( 'aistore_escrow_system', array( 'EscrowSystem', 'aistore_escrow_system' ) );

add_shortcode( 'aistore_escrow_list', array( 'EscrowSystem', 'aistore_escrow_list' ) );

add_shortcode( 'aistore_escrow_detail', array( 'EscrowSystem', 'aistore_escrow_detail' ) );


class EscrowSystem {
    
      // create escrow System
      
public static function aistore_escrow_system()
{ 
 
     
$instance = new Woo_Wallet_Wallet();
$user_id=get_current_user_id();

if(isset($_POST['submit']) and $_POST['action']=='escrow_system' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
   exit;
} 




$title=sanitize_text_field($_REQUEST['title']);
$amount=sanitize_text_field($_REQUEST['amount']);
$receiver_email=sanitize_text_field($_REQUEST['receiver_email']);
$term_condition=sanitize_text_field($_REQUEST['term_condition']);
$email_id = get_the_author_meta( 'user_email', get_current_user_id() );
$b=$instance->get_wallet_balance($user_id,'');

if($b<$amount){
      _e( '<p>Insufficent Balance</p>', 'aistore' ); 

}
else
{
    
$escrow_fee=get_option('escrow_create_fee');
$escrow_fee1=($escrow_fee / 100) * $amount;





    
    $new_amount=$amount-$escrow_fee1;
    
$instance->debit($user_id,$amount,$term_condition);

$instance->credit(get_option('escrow_user_id'),$escrow_fee1,$term_condition);
$txid=$instance->credit(get_option('escrow_user_id'),$new_amount,$term_condition);

$msg1="Invited by ".$email_id;
global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email ,term_condition,transaction_id,balance) VALUES ( %s, %d, %s, %s, %s, %s ,%d)", array( $title, $amount, $receiver_email,$email_id,$term_condition ,$txid , $b) ) );


$this_insert = $wpdb->insert_id;
$to = $receiver_email;
$subject = 'Escrow System';
$body =     $msg1;   


$headers = array('Content-Type: text/html; charset=UTF-8');
$msg="Successfully send payment with Escrow id : ".$this_insert;
wp_mail( $to, $subject, $body, $headers );

   $page_id=get_option('details_escrow_page_id'); 
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
	'eid'=> $this_insert,
), home_url() ) );

 
?>




<br>
<div><h1>Thank You</h1></div>
<div><?php echo esc_html($msg); ?></div>
<div>Escrow Fee : <?php echo esc_html($escrow_fee1); ?></div>
<div>Sender:<?php echo esc_html($email_id) ; ?></div>
<div>Receiver:<?php echo esc_html($receiver_email) ; ?></div>
<div><a href="<?php echo esc_html($url) ; ?>" >Go To Details Page </a></div>

<?php

}

}
?>
    
    <form method="POST" action="" name="escrow_system" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

                
                 <?php
            

                ?>
<label for="title">Title</label><br>
  <input class="input" type="text" id="title" name="title" required><br>
  

  <label for="amount">Amount</label><br>
  <input class="input" type="number" id="amount" name="amount" required><br>
 
   <input class="input" type="hidden" id="escrow_create_fee" name="escrow_create_fee" value= <?php echo get_option('escrow_create_fee');?>><br>
  <?php
    _e( " Available balance is : ", 'aistore' );
    
 
    



 $b=$instance->get_wallet_balance ($user_id);
 
  echo $b;



  ?>
 
  <br>
  
  Escrow Fee:  <b id="demo"></b>/-<br>
  Amount:  <b id="demo1"></b>/-<br>
  Total  Amount:  <b id="demo2"></b>/-<br>
<label for="address">Receiver Email:</label><br>
  <input class="input" type="text" id="receiver_email" name="receiver_email" required><br>
  
   <label for="term_condition">Term and condition</label><br>
  <textarea class="input" id="term_condition" name="term_condition">
  </textarea><br>
  
 
  

<input 
 type="submit" class="btn" name="submit" value="Submit"/>
<input type="hidden" name="action" value="escrow_system" />
</form> 
<?php
}





// Escrow List

 public static function aistore_escrow_list(){

	 
$email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE sender_email=%s", $email_id) 
                 );

?>
<h3><u>Out Going Escrow</u> </h3>
<?php 
 if($results==null)
	{
	    
	     _e( '<br><center><h3>Escrow List Not Found</h3></center>', 'aistore' ); 
	
	}
	else{
   
  ob_start();
     
  ?>
  
    <table class="table">
     
        <tr>
      
    <th>Id</th>
        <th>Title</th>
          <th>Amount</th> 
		  <th>Sender</th>
		  <th>Receiver</th>
</tr>

    <?php 
    
     
   
    foreach($results as $row):
     $page1=get_option('details_escrow_page_id'); 
	 
	 $url2 =  esc_url( add_query_arg( array(
    'page_id' => $page1,
    'eid' => $row->id,
), home_url() ) ); 

 ?>
 
 
      
    
      <tr>
           
		 
		   
		   
		   <td> 	<a href="<?php echo $url2; ?>" >

		   <?php echo $row->id ; ?> </a> </td>
  <td> 		   <?php echo $row->title ; ?> </td>
		  	   <td> 		   <?php echo $row->amount ; ?> </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
           
            </tr>
    <?php endforeach;
	
	}?>

    </table>
	
	
	
	
	
	<br><br>
	<h3><u>Incoming Escrow </u></h3>
<?php

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s", $email_id) 
                 );

    if($results==null)
	{
	    _e( '<br><center><h3>Escrow List Not Found</h3></center>', 'aistore' );
	
	}
	else{
     
  ?>
  
    <table class="table">
     
      
        <tr>
      
    <th>Id</th>
        <th>Title</th>
          <th>Amount</th> 
		  <th>Sender</th>
		  <th>Receiver</th>
</tr>

    <?php 
    
     

    foreach($results as $row):
	
  $page1=get_option('details_escrow_page_id'); 
	 
	 $url1 =  esc_url( add_query_arg( array(
    'page_id' => $page1,
    'eid' => $row->id,
), home_url() ) ); 

    ?> 
      <tr>
           
 
		   <td> 	<a href="<?php echo $url1; ?>" >
		   <?php echo $row->id ; ?> </a> </td>
		   <td> 		   <?php echo $row->title ; ?> </td>
		   <td> 		   <?php echo $row->amount ; ?> </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
		  
    </tr>
    <?php endforeach;
	
	}?>



    </table>
    <?php 
 return ob_get_clean();   
 
}




// Escrow Details

public static function aistore_escrow_detail( ){
	
	
$user_id= get_current_user_id();
	 
$email_id = get_the_author_meta( 'user_email',$user_id );
 

ob_start();


if(!isset($_REQUEST['eid']))
{
 
	$page_id=get_option('list_escrow_page_id'); 
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
), home_url() ) ); 

?>
	<div><a href="<?php echo $url ; ?>" >Go To Escrow List Page </a></div>
<?php	

return ob_get_clean();  
}

 $eid=$_REQUEST['eid'];


global $wpdb;

 
if(isset($_POST['submit']) and $_POST['action']=='disputed-1')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'disputed-1' , $eid   ) );
					  
				
?>
<div>
<strong> Disputed-1 Successfully</strong></div>
<?php
}


if(isset($_POST['submit']) and $_POST['action']=='disputed-2')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'disputed-2' , $eid   ) );
					  
				
?>
<div>
<strong> Disputed -2 Successfully</strong></div>
<?php
}

if(isset($_POST['submit']) and $_POST['action']=='disputed-3')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'disputed-3' , $eid   ) );
					  
		
		
		$amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 
//echo $amount;

 $details= 'Accept Escrow System';
$escrow_fee=get_option('escrow_create_dispute_fee');
$escrow_fee1=($escrow_fee / 100) * $amount;


$instance = new Woo_Wallet_Wallet();
$instance->debit($user_id,$escrow_fee1,$details);
$instance->credit(get_option('escrow_user_id'),$escrow_fee1,$details);
		
?>
<div>
<strong> Disputed -3 Successfully</strong></div>
<?php
_e( "<p>Escrow Fee :: ". "<b>".$escrow_fee1."</b></p> ", 'aistore' ); 
}



if(isset($_POST['submit']) and $_POST['action']=='accepted')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'accepted' , $eid   ) );

$amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 
//echo $amount;

 $details= 'Accept Escrow System';
$escrow_fee=get_option('escrow_accept_fee');
$escrow_fee1=($escrow_fee / 100) * $amount;


$instance = new Woo_Wallet_Wallet();


$instance->debit($user_id,$escrow_fee1,$details);
$instance->credit(get_option('escrow_user_id'),$escrow_fee1,$details);

?>
<div>
    
<strong> Accepted Successfully</strong></div>
<?php
 _e( "<p>Escrow Fee :: ". "<b>".$escrow_fee1."</b></p> ", 'aistore' ); 

}

if(isset($_POST['submit']) and $_POST['action']=='released')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
}  

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'released' , $eid   ) );
   
	$q = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
	$q2 = $wpdb->get_var( $wpdb->prepare( "SELECT receiver_email from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );

$amount=$q;
$reciever_email=$q2;

$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID from {$wpdb->prefix}users where user_login  = %s", $reciever_email ) );


 $details= 'Release Escrow System';
 
$instance = new Woo_Wallet_Wallet();
$instance->debit(get_option('escrow_user_id'),$amount,$details);
$instance->credit($id,$amount,$details);

?>
<div>
<strong> Released Successfully</strong></div>
<?php
}
 

if(isset($_POST['submit']) and $_POST['action']=='cancelled')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'cancelled' , $eid   ) );

$amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 
//echo $amount;

 $details= 'Cancel Escrow System';



$instance = new Woo_Wallet_Wallet();


$instance->debit(get_option('escrow_user_id'),$amount,$details);
$instance->credit($user_id,$amount,$details);

?>
<div>
<strong> Cancelled Successfully</strong></div>
<?php
}
 







////
$instance1 = new EscrowSystem();
 if ( ! $instance1->aistore_isadmin()) {


  $escrow = $wpdb->get_row( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s", $eid) 
                 );
 
 }
 
 else
 {
	  
	 
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '".   $email_id."' or receiver_email = '".   $email_id."' ) and id=%s ",$eid ));

 }

     echo "<h1>#". $escrow->id ." ".$escrow->title ."</h1>";
   _e( "<p>Term Condition <br /><br /> "."<b>".$escrow->term_condition."</b></p>", 'aistore' );
  _e( "<p>Sender: ". "<b>".$escrow->sender_email."</b></p> ", 'aistore' );   
 _e( "<p>Receiver: " . "<b>".$escrow->receiver_email."</b></p>", 'aistore' ); 
 _e( "<p>Status: " . "<b>".$escrow->status."</b></p>", 'aistore' ); 
	      

	   
	 
//include "buttons/accept_button.php";

$instance1->accept_escrow_btn($escrow);


//include "buttons/cancel_button.php";
$instance1->cancel_escrow_btn($escrow);


//include "buttons/release_button.php";
$instance1->release_escrow_btn($escrow);
 
//include "buttons/dispute_button.php";

if($escrow->status=='accepted'){
    
   

$instance1->dispute_escrow_btn1($escrow);
}
  if($escrow->status=='disputed-1'){
$instance1->dispute_escrow_btn2($escrow);

}

if($escrow->status=='disputed-2'){
$instance1->dispute_escrow_btn3($escrow);
}



//include "escrow_discussion.php";
$instance1->escrow_discussion($escrow);
 

 return ob_get_clean();  
	 
}


// Escrow Discussion

private	function escrow_discussion($escrow){
	
	$user_login = get_the_author_meta( 'user_login', get_current_user_id() );

	 global $wpdb;
if(isset($_POST['submit']) and $_POST['action']=='escrow_discussion')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return 'Sorry, your nonce did not verify.';
   
} 


$message=sanitize_text_field($_REQUEST['message']);
  
$wpdb->query( $wpdb->prepare( " INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array( $escrow->id, $message, $user_login ) ) );

} 
	  ob_start();
?>

     
	 

<form method="POST" action="" name="escrow_discussion" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

 
   <label for="message">Message</label><br>
  <textarea class="input" id="message" name="message">
  </textarea><br>
  
<input class="input" type="submit" name="submit" value="Submit"/>
<input type="hidden" name="action" value="escrow_discussion" />
</form> 
<?php

  $discussions = $wpdb->get_results( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion WHERE eid=%s", $escrow->id) 
                 );

?> 
  
    <table class="table">
    <?php
    foreach($discussions as $row):
     
    ?> 
	
	<div >
   
  <p><?php echo $row->message ; ?></p>
  <b><?php echo $row->user_login ; ?> </b>
  <h6 > <?php echo $row->created_at ; ?></h6>
</div>

<hr>
    
    <?php endforeach;?>
    </table>
	
	<?php
	
	}
	

 





 // Accept Button
 
private function  accept_escrow_btn($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );



	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";
	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";
	
	else 
		
		if($escrow->status=="disputed-1"  )
		return "";
		
	
	
else 
		
		if($escrow->status=="accepted"  )
		return "";


else 
	 if($escrow->sender_email == $user_email  )
		return "";
	
	

?>

 <form method="POST" action="" name="accepted" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Accept">
  <input type="hidden" name="action" value="accepted" />
</form> <?php } 



// cancel button

private function cancel_escrow_btn($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );



	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";

	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";
	
	else 
		
		if($escrow->status=="accepted"  )
		return "";
	

 $instance = new EscrowSystem();
 if ( ! $instance->aistore_isadmin()) {

	
		if($escrow->status=="disputed-1"  )
		return "";

 }

?>

 <form method="POST" action="" name="cancelled" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Cancel">
  <input type="hidden" name="action" value="cancelled" />
</form> <?php  

}





// release button

private function release_escrow_btn($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );



	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";
	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";
	else 
		
		if($escrow->status=="pending"  )
		return "";
	


 
 $instance = new EscrowSystem();
 if ( ! $instance->aistore_isadmin()) {



	 if($escrow->sender_email <> $user_email  )
		return "";
	
	
 }
	
	
	
	
?>

  
 <form method="POST" action="" name="released" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Release">
  <input type="hidden" name="action" value="released" />
</form> <?php 



}


// dispute button 1

private function  dispute_escrow_btn1($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );



	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";
	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";
	
	else 
		
		if($escrow->status=="disputed-1"  )
		return "";
		
		
	
	else 
		
		if($escrow->status=="pending"  )
		return "";

?>

 <form method="POST" action="" name="disputed-1" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Dispute-1">
  <input type="hidden" name="action" value="disputed-1" />
</form> <?php  }


// dispute button 2

private function  dispute_escrow_btn2($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );



	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";
	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";

	else 
		
		if($escrow->status=="disputed-2"  )
		return "";
		
	else 
		
		if($escrow->status=="pending"  )
		return "";

?>

 <form method="POST" action="" name="disputed-2" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Dispute-2">
  <input type="hidden" name="action" value="disputed-2" />
</form> <?php  }


// dispute button 3

private function  dispute_escrow_btn3($escrow)
{
	 global $wpdb;
	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );

	if($escrow->status=="closed"  )
		return "";
	
	else 
		
		if($escrow->status=="released"  )
		return "";
	
	else 
		
		if($escrow->status=="cancelled"  )
		return "";

	else 
		
		if($escrow->status=="disputed-3"  )
		return "";
		
	else 
		
		if($escrow->status=="pending"  )
		return "";

?>

 <form method="POST" action="" name="disputed-3" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Dispute-3">
  <input type="hidden" name="action" value="disputed-3" />
</form> <?php  }


// admin
 function aistore_isadmin()
{
$user = wp_get_current_user();
 $allowed_roles = array( 'administrator');
 if (  array_intersect( $allowed_roles, $user->roles ) ) {
	 return true;
 }
	else{
		
		return false;
		
	}
}



}



