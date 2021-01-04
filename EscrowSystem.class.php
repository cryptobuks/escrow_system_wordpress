<?php
 
add_action( 'admin_init', 'allow_subscriber_uploads' );


function allow_subscriber_uploads() {
    

     
  
    $contributor = get_role( 'customer' );
    $contributor->add_cap('upload_files');
    
}






class EscrowSystem {
    
    public static function escrow_syetem_part2()
{
    if(isset($_POST['submit']) and $_POST['action']=='escrow_system_part2' )
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
            // save into database $upload_dir['baseurl'].'/product-images/'.$filename;
            
                     

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id) VALUES ( %d,%s,%d)", array( $eid,$image,$user_id) ) );
        }
        
         $page_id=get_option('details_escrow_page_id'); 
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
	'eid'=> $eid,
), home_url() ) );

    ?>
    
    
<br>
<div><h1> <?php  _e( 'Thank You', 'aistore' ) ?></h1></div>

<div><a href="<?php echo esc_html($url) ; ?>" >
    <?php  _e( 'Go To Details Page', 'aistore' ) ?>
     </a></div>

<?php
} 

    else{
    ?>
    
    <form method="POST" action="" name="escrow_system_part2" enctype="multipart/form-data"> 
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
<input type="hidden" name="action" value="escrow_system_part2" />
    </form>
    

    <?php
    } 
    }

    
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
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$title=sanitize_text_field($_REQUEST['title']);
$amount=sanitize_text_field($_REQUEST['amount']);
$receiver_email=sanitize_text_field($_REQUEST['receiver_email']);

$email_id = get_the_author_meta( 'user_email', get_current_user_id() );
$b=$instance->get_wallet_balance($user_id,'');


if($b<$amount){
      _e( 'Insufficent Balance', 'aistore' ); 

}
else
{
    
$escrow_fee=get_option('escrow_create_fee');
$escrow_fee1=($escrow_fee / 100) * $amount;





    
    $new_amount=$amount-$escrow_fee1;
 $details="Create Escrow System";   
$instance->debit($user_id,$amount,$details);

$instance->credit(get_option('escrow_user_id'),$escrow_fee1,$details);
$txid=$instance->credit(get_option('escrow_user_id'),$new_amount,$details);

$msg1="Invited by ".$email_id;
global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email ,transaction_id,balance) VALUES ( %s, %d, %s, %s, %d ,%d)", array( $title, $amount, $receiver_email,$email_id ,$txid , $b) ) );


$this_insert = $wpdb->insert_id;
$to = $receiver_email;
$subject = 'Escrow System';
$body =     $msg1;   


$headers = array('Content-Type: text/html; charset=UTF-8');
$msg= _e( 'Successfully send payment with Escrow id ', 'aistore' ) 
 .$this_insert;
 
wp_mail( $to, $subject, $body, $headers );

   $page_id=get_option('add_escrow_page_id2'); 
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
	'eid'=> $this_insert,
), home_url() ) );

 
?>




<br>
<div><h1><?php   _e( 'Thank You', 'aistore' ); ?> </h1></div>
<div><?php echo esc_html($msg); ?></div>
<div><?php   _e( 'Escrow Fee', 'aistore' ); ?> : <?php echo esc_html($escrow_fee1); ?></div>

<div>   <?php   _e( 'Sender', 'aistore' ); ?>     :<?php echo esc_html($email_id) ; ?></div>


<div>  <?php   _e( 'Receiver', 'aistore' ); ?> :<?php echo esc_html($receiver_email) ; ?></div>

<div><a href="<?php echo esc_html($url) ; ?>" > <?php   _e( 'Go To Next Step', 'aistore' ); ?> </a></div>

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
    
 
    



 $b=$instance->get_wallet_balance ($user_id);
 
  echo $b;



  ?>
 
  <br>
  <?php   _e( 'Escrow Fee', 'aistore' ); ?>
  :  <b id="demo"></b>/-  (<?php echo get_option('escrow_create_fee');?> %)<br>
     <?php   _e( 'Amount', 'aistore' ); ?> :  <b id="demo1"></b>/-<br>
     
   <?php   _e( 'Total  Amount', 'aistore' ); ?> :   
  :  <b id="demo2"></b>/-<br>
  
  
  
<label for="address"><?php   _e( 'Receiver Email', 'aistore' ); ?>:</label><br>
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

	 
$email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE sender_email=%s", $email_id) 
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
	<h3><u><?php   _e( 'Incoming Escrow', 'aistore' ); ?> </u></h3>
<?php

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s", $email_id) 
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
            // save into database $upload_dir['baseurl'].'/product-images/'.$filename;
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
            //echo $upload_dir['baseurl'].'/documents/'.$filename;
            $image=$upload_dir['baseurl'].'/documents/'.$filename;
            // save into database $upload_dir['baseurl'].'/product-images/'.$filename;
            
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
 
	$page_id=get_option('list_escrow_page_id'); 
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
), home_url() ) ); 

?>
	<div><a href="<?php echo $url ; ?>" >
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
 
//echo $amount;

 $details= 'Accept Escrow System';
$escrow_fee=get_option('escrow_accept_fee');
$escrow_fee1=($escrow_fee / 100) * $amount;


$instance = new Woo_Wallet_Wallet();


$instance->debit($user_id,$escrow_fee1,$details);
$instance->credit(get_option('escrow_user_id'),$escrow_fee1,$details);

?>
<div>
    
<strong> <?php  _e( 'Accepted Successfully', 'aistore' ) ?></strong></div>
<?php
 _e( "<p>Escrow Fee :: ". "<b>".$escrow_fee1."</b></p> ", 'aistore' ); 

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

$amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 
//echo $amount;

 $details= 'Cancel Escrow System';



$instance = new Woo_Wallet_Wallet();


$instance->debit(get_option('escrow_user_id'),$amount,$details);
$instance->credit($user_id,$amount,$details);

?>
<div>
<strong><?php  _e( ' Cancelled Successfully', 'aistore' ) ?></strong></div>
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
     
     
  _e( "Term Condition:  ", 'aistore' );
   echo "<b>".$escrow->term_condition."</b><br>";
   
 _e( "Sender:   ", 'aistore' );  
 echo "<b>".$escrow->sender_email."</b> <br>";
 
 _e( "Receiver:" , 'aistore' ); 
 echo "<b>".$escrow->receiver_email."</b><br>";
 
 
 _e( "Status: " , 'aistore' ); 
 echo "<b>".$escrow->status."</b><br>";

    
	   $escrow_documents = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_documents WHERE  eid=%d ",$eid ));
	   if($escrow_documents){
	   ?>
	   <div>
	       
	  <a href="<?php echo $escrow_documents->documents; ?>">
	      <?php  _e( ' Download Document', 'aistore' ) ?></a>
	   </div>
	   <?php
	   }
	   
//include "buttons/accept_button.php";

$instance1->accept_escrow_btn($escrow);


//include "buttons/cancel_button.php";
$instance1->cancel_escrow_btn($escrow);


//include "buttons/release_button.php";
$instance1->release_escrow_btn($escrow);
 
//include "buttons/dispute_button.php";
$instance1->dispute_escrow_btn($escrow);

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