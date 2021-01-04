<?php
 
add_action( 'admin_init', 'allow_subscriber_uploads' );


function allow_subscriber_uploads() {
    

     
  
    $contributor = get_role( 'customer' );
    $contributor->add_cap('upload_files');
    
}






class EscrowSystem {
    
    
  
 
 
    public static function escrow_syetem_part2()
{
    if(isset($_POST['submit']) and $_POST['action']=='create_escrow_page_2' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify.', 'aistore' );

   exit;
} 

    
    $eid=$_REQUEST['eid'];
    $user_id=get_current_user_id();
    
    $term_condition=sanitize_text_field($_REQUEST['term_condition']);
    
    global $wpdb; 
    
    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET term_condition = '%s'  WHERE id = '%d'", 
   $term_condition , $eid   ) );
    
     
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents';
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            move_uploaded_file($_FILES['file']['tmp_name'], $user_dirname .'/'. $filename);
            
            $image= $upload_dir['baseurl'].'/documents/'.$filename;
            
            // save into database  $image
            
                     

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id) VALUES ( %d,%s,%d)", array( $eid,$image,$user_id) ) );
        }
        
   
	 
	 $details_escrow_page_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );

    ?>
    
    
<br>
<div><h1> <?php  _e( 'Thank You', 'aistore' ) ?></h1></div>

<div><a href="<?php echo esc_html($details_escrow_page_url) ; ?>" >
    <?php  _e( 'Go To Escrow Details Page', 'aistore' ) ?>
     </a></div>

<?php
} 

    else{
    ?>
    
    <form method="POST" action="" name="create_escrow_page_2" enctype="multipart/form-data"> 
    <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
    

 
   <label for="term_condition"> <?php  _e( 'Term And Condition', 'aistore' ) ?></label><br>
   
   



  
  <?php
  
$content   = '';
$editor_id = 'term_condition';


   
wp_editor( $content, $editor_id);

?><br>
	<label for="documents"><?php  _e( 'Documents', 'aistore' ) ?>: </label>
     <input type="file" name="file" required /><br>
     
<br><br>
<input class="input" type="submit" name="submit" value="Submit"/>
<input type="hidden" name="action" value="create_escrow_page_2" />
    </form>
    

    <?php
    } 
    }
    
    
    

    
      // create escrow System
      
public static function aistore_escrow_system()
{ 
 
    




$wallet = new Woo_Wallet_Wallet();
$user_id=get_current_user_id();

if(isset($_POST['submit']) and $_POST['action']=='escrow_system' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$title=sanitize_text_field($_REQUEST['title']);
$amount=sanitize_text_field($_REQUEST['amount']);
$receiver_email=sanitize_text_field($_REQUEST['receiver_email']);

$sender_email = get_the_author_meta( 'user_email', get_current_user_id() );
$user_balance=$wallet->get_wallet_balance($user_id,'');


if($user_balance<$amount){
      _e( 'Insufficent Balance', 'aistore' ); 

}
else
{
     
$escrow_fee =(get_option('escrow_create_fee') / 100) * $amount;





    
    $new_amount=$amount-$escrow_fee ;
    
    
    global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email ,transaction_id,balance) VALUES ( %s, %d, %s, %s, %d ,%d)", array( $title, $amount, $receiver_email,$sender_email ,4 , $user_balance) ) );



$eid = $wpdb->insert_id;


 $details="Payment transaction for the escrow ".$eid ; 
 
 
$wallet->debit($user_id,$amount,$details);

$wallet->credit(get_option('escrow_user_id'),$escrow_fee ,$details);
$txid=$wallet->credit(get_option('escrow_user_id'),$new_amount,$details);

 




$msg= printf(

__( 'Successfully send payment with Escrow id %d.', 'aistore' ),
$eid
);




	 $create_escrow_page_2_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('create_escrow_page_2'),
	'eid'=> $eid,
), home_url() ) );

// email to sender 




// email to receiver


$to = $receiver_email;
$subject =$details;


	 $url1  =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );


 $body="Dear sir, <br>
     <h2>Your partner ".$sender_email." have invited you for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited by : ".$sender_email.
     
     "<br>Escrow ID is:".$eid.
     "<br>Accept Escrow system to :<br><br><u>Click here </u>:".
         $url1 ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

?>




<br>
<div><h1><?php   _e( 'Thank You', 'aistore' ); ?> </h1></div>

<div><?php echo esc_html($msg); ?></div>



<div><?php  
printf(__( 'Escrow Fee %s.', 'aistore' ),
$escrow_fee
); ?> </div>

<div>   <?php   printf(__( 'Sender %s.', 'aistore'),$sender_email ); ?>   </div>


<div>  <?php   printf(__( 'Receiver %s.', 'aistore'),$receiver_email ); ?> </div>

<div><a href="<?php echo esc_html($create_escrow_page_2_url) ; ?>" > <?php   _e( 'Go To Next Step', 'aistore' ); ?> </a></div>

<?php

}

}
else{
?>
    
    <form method="POST" action="" name="escrow_system" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

                
                 <?php
            

                ?>
<label for="title"><?php   _e( 'Title', 'aistore' ); ?></label><br>
  <input class="input" type="text" id="title" name="title" required><br>
  

  <label for="amount"><?php   _e( 'Amount', 'aistore' ); ?></label><br>
  <input class="input" type="number" id="amount" name="amount" required><br>
 
   <input class="input" type="hidden" id="escrow_create_fee" name="escrow_create_fee" value= <?php echo get_option('escrow_create_fee');?>><br>
  <?php
    _e( " Available balance is ", 'aistore' );
    
 
    



 $balance=$wallet->get_wallet_balance ($user_id);
 
  echo $balance;



  ?>
 
  <br>
  <?php   _e( 'Escrow Fee', 'aistore' ); ?>
  
  :  <b id="escrow_fee"></b>/-  (<?php echo get_option('escrow_create_fee');?> %)<br>
     <?php   _e( 'Amount', 'aistore' ); ?> :  <b id="amount"></b>/-<br>
     
   <?php   _e( 'Total  Amount', 'aistore' ); ?> :   
  :  <b id="total"></b>/-<br><br>
  
  
  
<label for="receiver_email"><?php   _e( 'Receiver Email', 'aistore' ); ?>:</label><br>
  <input class="input" type="text" id="receiver_email" name="receiver_email" required><br>
  
  



<br><br>
<input 
 type="submit" class="btn" name="submit" value="Submit"/>
<input type="hidden" name="action" value="escrow_system" />
</form> 
<?php
}
}







// Escrow List

 public static function aistore_escrow_list(){

	 
$current_user_email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE sender_email=%s", $current_user_email_id) 
                 );

?>
<h3><u><?php   _e( 'Out Going Escrow', 'aistore' ); ?></u> </h3>
<?php 
 if($results==null)
	{
	    
	     _e( 'Escrow List Not Found', 'aistore' ); 
	
	}
	else{
   
  ob_start();
     
  ?>
  
    <table class="table">
     
        <tr>
      
    <th><?php   _e( 'Id', 'aistore' ); ?></th>
        <th><?php   _e( 'Title', 'aistore' ); ?></th>
          <th><?php   _e( 'Amount', 'aistore' ); ?></th> 
		  <th><?php   _e( 'Sender', 'aistore' ); ?></th>
		  <th><?php   _e( 'Receiver', 'aistore' ); ?></th>
</tr>

    <?php 
    
     
   
    foreach($results as $row):
  
	 
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $row->id,
), home_url() ) ); 

 ?>
 
 
      
    
      <tr>
           
		 
		   
		   
		   <td> 	<a href="<?php echo $details_escrow_page_id_url; ?>" >

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
	<h3><u><?php   _e( 'Incoming Escrow', 'aistore' ); ?> </u></h3>
<?php

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s", $current_user_email_id) 
                 );

    if($results==null)
	{
	    _e( 'Escrow List Not Found', 'aistore' );
	
	}
	else{
     
  ?>
  
    <table class="table">
     
      
        <tr>
      
     <th><?php   _e( 'Id', 'aistore' ); ?></th>
        <th><?php   _e( 'Title', 'aistore' ); ?></th>
          <th><?php   _e( 'Amount', 'aistore' ); ?></th> 
		  <th><?php   _e( 'Sender', 'aistore' ); ?></th>
		  <th><?php   _e( 'Receiver', 'aistore' ); ?></th>
</tr>

    <?php 
    
     

    foreach($results as $row):
	
 
	 
	 $details_escrow_page_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $row->id,
), home_url() ) ); 

    ?> 
      <tr>
           
 
		   <td> 	<a href="<?php echo $details_escrow_page_url; ?>" >
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

function fn_upload_file() {
    if ( isset($_POST['upload_file']) ) {
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents';
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            move_uploaded_file($_FILES['file']['tmp_name'], $user_dirname .'/'. $filename);
            echo $upload_dir['baseurl'].'/documents/'.$filename;
          
        }
    }
}



// Escrow Details

public static function aistore_escrow_detail( ){
    
   $eid=$_REQUEST['eid'];
$user_id= get_current_user_id();


if ( isset($_POST['upload_file']) ) {
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents';
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            move_uploaded_file($_FILES['file']['tmp_name'], $user_dirname .'/'. $filename);
            
            $image=$upload_dir['baseurl'].'/documents/'.$filename;
            // save into database $image;
            
            global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id) VALUES ( %d,%s,%d)", array( $eid,$image,$user_id) ) );
        }
    }


    ?>
    
    
    <div>
        
        
    <form method="post" enctype="multipart/form-data">
	<label for="documents"> <?php   _e( 'Documents', 'aistore' ); ?> : </label>
     <input type="file" name="file" required />
     <input type="submit" name="upload_file" value="Upload" />
     </form>
     
     
     </div>
     
     <?php
	
$user_id= get_current_user_id();
	 
$email_id = get_the_author_meta( 'user_email',$user_id );
 

ob_start();


if(!isset($_REQUEST['eid']))
{

	 
	 $list_escrow_page_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('list_escrow_page_id'),
), home_url() ) ); 

?>
	<div><a href="<?php echo $list_escrow_page_url ; ?>" >
	    <?php   _e( 'Go To Escrow List Page', 'aistore' ); ?> 
	     </a></div>
<?php	

return ob_get_clean();  
}

 $eid=$_REQUEST['eid'];


global $wpdb;

 
if(isset($_POST['submit']) and $_POST['action']=='disputed')
{
if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
  
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'disputed' , $eid   ) );

?>
<div>
<strong> <?php  _e( 'Disputed Successfully', 'aistore' ) ?></strong></div>
<?php


}



if(isset($_POST['submit']) and $_POST['action']=='accepted')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'accepted' , $eid   ) );

$amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );


 $details= 'Accept Escrow System'.$eid;

$escrow_fee=(get_option('escrow_accept_fee')/ 100) * $amount;


$wallet = new Woo_Wallet_Wallet();


$wallet->debit($user_id,$escrow_fee,$details);
$wallet->credit(get_option('escrow_user_id'),$escrow_fee,$details);

?>
<div>
    
<strong> <?php  _e( 'Accepted Successfully', 'aistore' ) ?></strong></div>
<?php
 printf(__( "Escrow Fee %d.", 'aistore'),$escrow_fee ); 

}

if(isset($_POST['submit']) and $_POST['action']=='released')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
    
}  

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'released' , $eid   ) );
   
	$escrow_amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
	
	$escrow_reciever_email_id = $wpdb->get_var( $wpdb->prepare( "SELECT receiver_email from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );




$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID from {$wpdb->prefix}users where user_login  = %s", $escrow_reciever_email_id ) );


 $details= 'Release Escrow System'.$eid;
 
$wallet = new Woo_Wallet_Wallet();
$wallet->debit(get_option('escrow_user_id'),$escrow_amount,$details);
$wallet->credit($id,$escrow_amount,$details);

?>
<div>
<strong> <?php  _e( 'Released Successfully', 'aistore' ) ?></strong></div>
<?php
}
 




if(isset($_POST['submit']) and $_POST['action']=='cancelled')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'cancelled' , $eid   ) );

$escrow_amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 

 $details= 'Cancel Escrow System'.$eid;



$wallet = new Woo_Wallet_Wallet();


$wallet->debit(get_option('escrow_user_id'),$escrow_amount,$details);
$wallet->credit($user_id,$escrow_amount,$details);

?>
<div>
<strong><?php  _e( ' Cancelled Successfully', 'aistore' ) ?></strong></div>
<?php
}
 







////
$escrow_system = new EscrowSystem();
 if ( ! $escrow_system->aistore_isadmin()) {


  $escrow = $wpdb->get_row( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s", $eid) 
                 );
 
 }
 
 else
 {
	  
	 
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '".   $email_id."' or receiver_email = '".   $email_id."' ) and id=%s ",$eid ));

 }

     echo "<h1>#". $escrow->id ." ".$escrow->title ."</h1>";
     
     
  printf(__( "Term Condition : %s.", 'aistore' ),$escrow->term_condition."<br>");
  printf(__( "Sender:  %s.", 'aistore' ),$escrow->sender_email."<br>");
  printf(__( "Receiver : %s.", 'aistore' ),$escrow->receiver_email."<br>");
  printf(__( "Status : %s.", 'aistore' ),$escrow->status."<br>");
  


    
	   $escrow_documents = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_documents WHERE  eid=%d ",$eid ));
	   if($escrow_documents){
	   ?>
	   <div>
	       
	  <a href="<?php echo $escrow_documents->documents; ?>">
	      <?php  _e( ' Download Document', 'aistore' ) ?></a>
	   </div>
	   <?php
	   }
	   


$escrow_system->accept_escrow_btn($escrow);



$escrow_system->cancel_escrow_btn($escrow);



$escrow_system->release_escrow_btn($escrow);
 

$escrow_system->dispute_escrow_btn($escrow);


$escrow_system->escrow_discussion($escrow);
 

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
   return   _e( ' Sorry, your nonce did not verify.', 'aistore' ) ;
} 


$message=sanitize_text_field($_REQUEST['message']);
  
$wpdb->query( $wpdb->prepare( " INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array( $escrow->id, $message, $user_login ) ) );

} 
	  ob_start();
?>

     
	 

<form method="POST" action="" name="escrow_discussion" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

 
   <label for="message">   <?php  _e( 'Message', 'aistore' ) ?></label><br>



  
  <?php
  
$content   = '';
$editor_id = 'message';


   
wp_editor( $content, $editor_id);

?>

<br><br>
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
		
		if($escrow->status=="disputed"  )
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

	
		if($escrow->status=="disputed"  )
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

private function  dispute_escrow_btn($escrow)
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
		
		if($escrow->status=="disputed"  )
		return "";
		
		
	
	else 
		
		if($escrow->status=="pending"  )
		return "";

?>

 <form method="POST" action="" name="disputed" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Dispute">
  <input type="hidden" name="action" value="disputed" />
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



?>