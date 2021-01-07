<?php







class AistoreEscrowSystem {
    
    

 
 
    public static function escrow_system_part2()
{
    
    
      ?>
  <div>
      
      <?php
    if(isset($_POST['submit']) and $_POST['action']=='create_escrow_page_2' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify.', 'aistore' );

   exit;
} 

    
    $eid=sanitize_text_field($_REQUEST['eid']);
    $user_id=get_current_user_id();
   // echo $eid;
    $term_condition=sanitize_text_field(htmlentities($_REQUEST['term_condition']));
    
    global $wpdb; 
    
    $wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET term_condition = '%s'  WHERE id = '%d'", 
   $term_condition , $eid   ) );
    
     
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents/'.$eid;
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']), $user_dirname .'/'. $filename);
            
            $image= $upload_dir['baseurl'].'/documents/'.$eid.'/'.$filename;
            
            // save into database  $image
            
                     

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name) VALUES ( %d,%s,%d,%s)", array( $eid,$image,$user_id,$filename) ) );
        }
        
   
	 
	 $details_escrow_page_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );

    ?>
    
   
<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($details_escrow_page_url) ; ?>" /> 

<div class="alert alert-success" role="alert">
 <?php printf(

__( 'Successfully  with Escrow id %d.', 'aistore' ),
$eid
); ?>
</div>
<br>

<?php
} 

    else{
    ?>
    
    <?php  $eid=sanitize_text_field($_REQUEST['eid']);?>
    <div class="alert alert-success" role="alert">
 <?php printf(

__( 'Successfully send payment with Escrow id %d.', 'aistore' ),
$eid
); ?>
</div>

    <form method="POST" action="" name="create_escrow_page_2" enctype="multipart/form-data"> 
    <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
    

 
   <label for="term_condition"> <?php  _e( 'Term And Condition', 'aistore' ) ?></label><br>
   
   



  
  <?php
  
$content   = '';
$editor_id = 'term_condition';

 
   $settings = array(
    'tinymce'       => array(
        'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright   ',
        'toolbar2'      => '',
        'toolbar3'      => ''
       
   
      ),   
         'textarea_rows' => 1 ,
    'teeny' => true,
    'quicktags' => false,
     'media_buttons' => false 
);



wp_editor( $content, $editor_id,$settings);

?><br>
	<label for="documents"><?php  _e( 'Documents', 'aistore' ) ?>: </label>
     <input type="file" name="file" accept="application/pdf" required /><br>
     <div><p>Note : We accept only pdf file and
	You can upload many pdf file then go to next escrow details page.</p></div>
<div><a href="<?php echo esc_html($details_escrow_page_url) ; ?>" >
<br><br>
<input class="input" type="submit" name="submit" value="Submit"/>
<input type="hidden" name="action" value="create_escrow_page_2" />
    </form>
    

    <?php
    } 
    
    
    ?>
    </div>
    <?php
    }
    
    
    

    
      // create escrow System
      
public static function aistore_escrow_system()
{ 
 
      ?>
  <div>
      
      <?php
      
if ( !is_user_logged_in() ) {
    return ;
}



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
$receiver_email=sanitize_email($_REQUEST['receiver_email']);

 $user_balance=$wallet->get_wallet_balance($user_id,'');

$sender_email = get_the_author_meta( 'user_email', get_current_user_id() );

if($user_balance<$amount){
      _e( 'Insufficent Balance', 'aistore' ); 

}
else
{
     
$escrow_fee =(get_option('escrow_create_fee') / 100) * $amount;





    
    $new_amount=$amount-$escrow_fee ;
    
    
    global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email  ) VALUES ( %s, %d, %s, %s  )", array( $title, $amount, $receiver_email,$sender_email   ) ) );



$eid = $wpdb->insert_id;


 $details="Payment transaction for the escrow ".$eid ; 
 
 
$wallet->debit($user_id,$amount,$details);

$wallet->credit(get_option('escrow_user_id'),$escrow_fee ,$details);
$txid=$wallet->credit(get_option('escrow_user_id'),$new_amount,$details);

	 $create_escrow_page_2_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('create_escrow_page_2'),
	'eid'=> $eid,
), home_url() ) );


//echo $create_escrow_page_2_url;
// email to sender 


$to = $sender_email;
$subject =$details;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );


 $body="Dear sir, <br>
     <h2>Your partner ".$receiver_email." have invited successfully for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited to : ".$receiver_email.
     
     "<br>Escrow ID is:".$eid.
     "<br>Accept Escrow system to :<br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );


// email to receiver


$to = $receiver_email;
$subject =$details;




 $body="Dear sir, <br>
     <h2>Your partner ".$sender_email." have invited you for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited by : ".$sender_email.
     
     "<br>Escrow ID is:".$eid.
     "<br>Accept Escrow system to :<br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );
//echo $create_escrow_page_2_url;
?>




<br>

<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($create_escrow_page_2_url) ; ?>" />

<div><h1><?php   _e( 'Thank You', 'aistore' ); ?> </h1></div>




<div><?php  
printf(__( 'Escrow Fee %s.', 'aistore' ),
$escrow_fee
); ?> </div>

<div>   <?php   printf(__( 'Sender %s.', 'aistore'),$sender_email ); ?>   </div>


<div>  <?php   printf(__( 'Receiver %s.', 'aistore'),$receiver_email ); ?> </div>



<?php

}

}
else{
?>
    
    <form method="POST" action="" name="escrow_system" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

                
                 
<label for="title"><?php   _e('Title', 'aistore' ); ?></label><br>
  <input class="input" type="text" id="title" name="title" required><br>
  <?php  $user_balance=$wallet->get_wallet_balance($user_id,''); ?>

  <label for="amount"><?php   _e( 'Amount', 'aistore' ); ?></label><br>
  <input class="input" type="number" id="amount" name="amount" min="1" max="<?php echo $user_balance ?>" required><br>
 
   <input class="input" type="hidden" id="escrow_create_fee" name="escrow_create_fee" value= "<?php echo get_option('escrow_create_fee');?>">
  <?php
   $balance=$wallet->get_wallet_balance ($user_id);

   printf(__('Available balance is %s', 'aistore'), $balance) ;
 
  ?>
 <div class="feeblock hide" >
      <?php   _e( 'Amount', 'aistore' ); ?> :
      <b id="escrow_amount"></b>/-  &#8377;<br>
 
  <?php   _e('Escrow Fee', 'aistore' ); ?>
  
  :  <b id="escrow_fee" ></b>/-  &#8377;  (<?php echo get_option('escrow_create_fee');?> %)<br>
  
    
     
   <?php   _e('Total  Amount', 'aistore' ); ?> :   
  :  <b id="total"></b>/-  &#8377;
  
  
  </div>
  <br>
  
<label for="receiver_email"><?php   _e('Receiver Email', 'aistore' ); ?>:</label><br>
  <input class="input" type="email" id="receiver_email" name="receiver_email" required><br>
  
  



<br><br>
<input 
 type="submit" class="btn" name="submit" value="Submit"/>
<input type="hidden" name="action" value="escrow_system" />
</form> 
<?php
}

?>
</div>
<?php


}







// Escrow List

 public static function aistore_escrow_list(){
if ( !is_user_logged_in() ) {
    return ;
}

   
	 
$current_user_email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE sender_email=%s order by id desc limit 100", $current_user_email_id) 
                 );

?>
<h3><u><?php   _e( 'Top 100 Out Going Escrow', 'aistore' ); ?></u> </h3>
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
		    <th><?php   _e( 'Status', 'aistore' ); ?></th>
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
		  	   <td> 		   <?php echo $row->amount ; ?>&#8377; </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
		    <td> 		   <?php echo $row->status ; ?> </td>
           
            </tr>
    <?php endforeach;
	
	}?>

    </table>
	
	
	
	
	
	<br><br>
	<h3><u><?php   _e( 'Top 100 Incoming Escrow', 'aistore' ); ?> </u></h3>
<?php

 $results = $wpdb->get_results( 
                    $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s order by id desc limit 100", $current_user_email_id) 
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
		   <th><?php   _e( 'Status', 'aistore' ); ?></th>
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
		   <td> 		   <?php echo $row->amount ; ?>&#8377; </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
		     <td> 		   <?php echo $row->status ; ?> </td>
		  
    </tr>
    <?php endforeach;
	
	}?>



    </table>
    <?php 
 return ob_get_clean();   


}




// Escrow Details

public static function aistore_escrow_detail( ){
    if ( !is_user_logged_in() ) {
    return ;
}

   $eid=sanitize_text_field($_REQUEST['eid']);
   
   
$user_id= get_current_user_id();


if ( isset($_POST['upload_file']) ) {
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents/'.$eid;
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            
            move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']), $user_dirname .'/'. $filename);
            
            $image=$upload_dir['baseurl'].'/documents/'.$eid.'/'.$filename;
            // save into database $image;
      
            global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name) VALUES ( %d,%s,%d,%s)", array( $eid,$image,$user_id,$filename) ) );
        }
    }


  
$user_id= get_current_user_id();
	 
$email_id = get_the_author_meta( 'user_email',$user_id );
 

ob_start();


if(!isset($eid))
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

 $eid=sanitize_text_field($_REQUEST['eid']);


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


  $details= "Payment transaction for the accept escrow with escrow id ".$eid;

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
   'released' , $eid) );
   
	$escrow_amount = $wpdb->get_var( $wpdb->prepare( "SELECT amount from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
	
	$escrow_reciever_email_id = $wpdb->get_var( $wpdb->prepare( "SELECT receiver_email from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );




$id = $wpdb->get_var( $wpdb->prepare( "SELECT ID from {$wpdb->prefix}users where user_email  = %s", $escrow_reciever_email_id ) );

//echo $id;

 $details= "Payment transaction for the release escrow with escrow id ".$eid;
 
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
 

  $details= "Payment transaction for the cancel escrow with escrow id ".$eid;



$wallet = new Woo_Wallet_Wallet();


$wallet->debit(get_option('escrow_user_id'),$escrow_amount,$details);
$wallet->credit($user_id,$escrow_amount,$details);

?>
<div>
<strong><?php  _e( ' Cancelled Successfully', 'aistore' ) ?></strong></div>
<?php
}
 







////
 
 
  if (  aistore_isadmin()) {
  
  $escrow = $wpdb->get_row( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s", $eid) 
                 );
 
 }
 
 else
 {
 
	 
	  
	 
	 
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '".   $email_id."' or receiver_email = '".   $email_id."' ) and id=%s ",$eid ));
  
 }
 
 
 
 
 ?>
	  <div>
	      <?php
     echo "<h1>#". $escrow->id ." ".$escrow->title ."</h1><br>";
     
     
  printf(__( "Term Condition : %s", 'aistore' ),html_entity_decode($escrow->term_condition)."<br>");
  printf(__( "Sender:  %s", 'aistore' ),$escrow->sender_email."<br>");
  printf(__( "Receiver : %s", 'aistore' ),$escrow->receiver_email."<br>");
  printf(__( "Status : %s", 'aistore' ),$escrow->status."<br><br>");
  
  $object=new AistoreEscrowSystem();

$object->accept_escrow_btn($escrow);



$object->cancel_escrow_btn($escrow);



$object->release_escrow_btn($escrow);
 

$object->dispute_escrow_btn($escrow);

$object->escrow_file_uploads($escrow);
$object->escrow_discussion($escrow);
 
 

 
 return ob_get_clean();  
 
    
    ?>
</div>
<?php
    
}



// Escrow  file uploads

private	function escrow_file_uploads($escrow){
      
$eid=  $escrow->id;
 
  global $wpdb;
   $escrow_documents = $wpdb->get_results( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_documents WHERE eid=%d", $eid) 
                 );
 
  
?> 
  
    <table class="table">
    <?php
    foreach($escrow_documents as $row):
     
    ?> 
	
	<div >
   


  <p><a href="<?php echo $row->documents; ?>" target="_blank">
	       <b><?php echo $row->documents_name ; ?></b></a></p>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>

<hr>
    
    <?php endforeach;?>
    </table>
<br>
	   <div>  
        
    <form method="post" enctype="multipart/form-data">
	<label for="documents"> <?php   _e( 'Documents', 'aistore' ); ?> : </label>
     <input type="file" name="file" accept="application/pdf" required />
     <input type="submit" name="upload_file" value="Upload" />
     </form>
    
     
     
     </div>
     <br>
     
     <?php 
      
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


$message=sanitize_text_field(htmlentities($_REQUEST['message']));
  
$wpdb->query( $wpdb->prepare( " INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array( $escrow->id, $message, $user_login ) ) );

} 
	  ob_start();
?>

     
	 
<div>
    <br>
<form method="POST" action="" name="escrow_discussion" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>

 
   <label for="message">   <?php  _e( 'Message', 'aistore' ) ?></label><br>



  
  <?php
  
$content   = '';
$editor_id = 'message';


   $settings = array(
    'tinymce'       => array(
        'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
        'toolbar2'      => '', 
        'toolbar3'      => ''  
       
           ),   
         'textarea_rows' => 1 ,
    'teeny' => true,
    'quicktags' => false,
     'media_buttons' => false 
);

   
wp_editor( $content, $editor_id,   $settings);
 
?>

 
<input class="input btn btn-small" type="submit" name="submit" value="Submit Message"/>
<input type="hidden" name="action" value="escrow_discussion" />
</form> 
</div>
<?php

  $discussions = $wpdb->get_results( 
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion WHERE eid=%s order by id desc", $escrow->id) 
                 );

?> 
  
    <table class="table">
    <?php
    foreach($discussions as $row):
     
    ?> 
	
	<div >
   
  <p><?php echo html_entity_decode($row->message ); ?></p>
  
  <br /><br />
  <b><?php echo $row->user_login ; ?> </b>
  <h6 > <?php echo $row->created_at ; ?></h6>
</div>
 
<hr>
    
    <?php endforeach;?>
    </table>
	
	<?php
	
	}
	

 





 // Accept Button
 
  function  accept_escrow_btn($escrow)
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

  function cancel_escrow_btn($escrow)
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
	
 
 if ( ! aistore_isadmin()) {
 

	
		if($escrow->status=="disputed"  )
		return "";

 }

?>

 <form method="POST" action="" name="cancelled" enctype="multipart/form-data"> 
 
<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  <input type="submit"  name="submit" value="Cancel Escrow">
  <input type="hidden" name="action" value="cancelled" />
</form> <?php  

}





// release button

  function release_escrow_btn($escrow)
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
	


 
 if ( ! aistore_isadmin()) {



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


// dispute button 

  function  dispute_escrow_btn($escrow)
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









}




?>