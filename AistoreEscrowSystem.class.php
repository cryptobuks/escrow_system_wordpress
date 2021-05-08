<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}





class AistoreEscrowSystem {
    
    
// get escrow feecccc


    public  function get_escow_fee($amount )
{
    
     
return (get_option('escrow_create_fee') / 100) * $amount;



    
}

 //it take parameters and create escrow
 
 
    public  function add_esrow($amount,$user_id,$receiver_email,$title,$term_condition)
{
    
  
// step 1

$ar=array();


 $user_balance=$wallet->get_wallet_balance($user_id,'');

$sender_email = get_the_author_meta( 'user_email', $user_id );

if($user_balance<$amount){
     
      
 $ar['Error']=true;
 
 $ar['Messsage']= 'Insufficent Balance';
 
 return $ar;

}
 
 
     
$escrow_fee =(get_option('escrow_create_fee') / 100) * $amount;





    
    $new_amount=double($amount-$escrow_fee) ;
    
    
    global $wpdb;   

$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email,term_condition,escrow_fee  ) VALUES ( %s, %s, %s, %s ,%s,%s )", array( $title, $new_amount, $receiver_email,$sender_email  ,$term_condition ,$escrow_fee) ) );



$eid = $wpdb->insert_id;

$Payment_details = __( 'Payment transaction for the escrow id', 'aistore' );

 $details=$Payment_details.$eid ; 
 
 
$wallet->debit($user_id,$amount,$details);

$wallet->credit(get_option('escrow_user_id'),$escrow_fee ,$details);

$txid=$wallet->credit(get_option('escrow_user_id'),$new_amount,$details);





// email to sender 


$to = $sender_email;
$subject =$details;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );


 $body="Hello, <br>
 
     <h2>Your partner ".$receiver_email." have invited successfully for the escrow ID ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited to: ".$receiver_email.
     
     "<br>Escrow ID is: ".$eid.
     "<br>Accept Escrow system to :<br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  //$body.=__( 'Your Recevier Email'.$receiver_email, 'aistore' );
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );


// email to receiver


$to = $receiver_email;
$subject =$details;




 $body="Hello, <br>
     <h2>Your partner ".$sender_email." have invited you for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited by : ".$sender_email.
     
     "<br>Escrow ID is:".$eid.
     "<br>Accept Escrow system to :<br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );
  
    



 $ar['Error']=true;
  $ar['eid']=$eid;
  
  
 $ar['Messsage']= 'Escrow created successfully';
 
 return $ar;
}
 
 
    

    
      // create escrow System
      
public static function aistore_escrow_system()
{ 
   
 

      
if ( !is_user_logged_in() ) {
    return "<div class='no-login'>Kindly login and then visit this page </div>";
}

  
 echo " <div>";
      
  

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
$term_condition=sanitize_text_field(htmlentities($_REQUEST['term_condition']));
 
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


$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email,term_condition,escrow_fee  ) VALUES ( %s, %d, %s, %s ,%s ,%s)", array( $title, $new_amount, $receiver_email,$sender_email  ,$term_condition ,$escrow_fee) ) );

$eid = $wpdb->insert_id;

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
        




$Payment_details = __( 'Payment transaction for the escrow id', 'aistore' );

 $details=$Payment_details.$eid ; 
 
 
$wallet->debit($user_id,$amount,$details);

$wallet->credit(get_option('escrow_user_id'),$escrow_fee ,$details);
$txid=$wallet->credit(get_option('escrow_user_id'),$new_amount,$details);

	 $create_escrow_page_2_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('create_escrow_page_2'),
	'eid'=> $eid,
), home_url() ) );


// email to sender 


$to = $sender_email;
$subject =$details;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );


 $body="Hello, <br>
 
     <h2>Your partner ".$receiver_email." have invited successfully for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited to : ".$receiver_email.
     
     "<br>Escrow ID is:".$eid.
     "<br>Accept Escrow system to :<br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> If you are not registered in the portal kindly visit my account page and register.";
  
  //$body.=__( 'Your Recevier Email'.$receiver_email, 'aistore' );
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );


// email to receiver


$to = $receiver_email;
$subject =$details;




 $body="Hello, <br>
     <h2>Your partner ".$sender_email." have invited you for the escrow ".$eid." </h2><br> Below is complete detail of the escrow <br/>
     Invited by : ".$sender_email.
     
     "<br>Escrow ID is : ".$eid.
     "<br>Accept Escrow system to : <br>".
         $details_escrow_page_id_url ;
    
  $body  .="<br /> <strong>If you are not registered in the portal kindly visit my account page and register.</strong>";
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

?>




<br>

<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($details_escrow_page_id_url) ; ?>" />

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
 
 
 $deposit_link= get_home_url()."/my-account/woo-wallet/";
 
 
 // issue 3
 
 
  ?><p>  <?php   _e('In case you have low balance please deposit balance ', 'aistore' );
  ?>
  
  <a href="<?php echo $deposit_link; ?>" >  
  <?php   _e('Deposit Fund', 'aistore' ); ?>
    </a> </p>
 <div class="feeblock hide" >
      <?php   _e( 'Amount', 'aistore' ); ?> :
      <b id="escrow_amount"></b>/-  <?php echo get_woocommerce_currency_symbol(); ?><br>
 
  <?php   _e('Escrow Fee', 'aistore' ); ?>
  
  :  <b id="escrow_fee" ></b>/- <?php echo get_woocommerce_currency_symbol(); ?>  (<?php echo get_option('escrow_create_fee');?> %)<br>
  
    
     
   <?php   _e('Total Escrow Amount', 'aistore' ); ?> :   <b id="total"></b>/- <?php echo get_woocommerce_currency_symbol(); ?>
  
  
  </div>
  <br>
  
<label for="receiver_email"><?php   _e('Receiver Email', 'aistore' ); ?>:</label><br>
  <input class="input" type="email" id="receiver_email" name="receiver_email" required><br>
  
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
?>
  



<br><br>

	<label for="documents"><?php  _e( 'Documents', 'aistore' ) ?>: </label>
     <input type="file" name="file" accept="application/pdf" required /><br>
     <div><p> <?php  _e( 'Note : We accept only pdf file and
	You can upload many pdf file then go to next escrow details page.', 'aistore' ) ?></p></div>
<input 
 type="submit" class="btn" name="submit" value="<?php  _e( 'Create Escrow', 'aistore' ) ?>"/>
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
   return "<div class='loginerror'>Kindly login and then visit this page</div>";
}

   
	 
$current_user_email_id = get_the_author_meta( 'user_email', get_current_user_id() );

global $wpdb;

  $results = $wpdb->get_results( 
                     $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s or sender_email=%s order by id desc limit 100", $current_user_email_id,$current_user_email_id) 

                 );

?>
<h3><u><?php   _e( 'Top 100  Escrow', 'aistore' ); ?></u> </h3>
<?php 

 
 if($results==null)
	{
	      echo "<div class='no-result'>";
	      
	     _e( 'Escrow List Not Found', 'aistore' ); 
	  echo "</div>";
	}
	else{
   
  ob_start();
     
  ?>
  
    <table class="table">
     
        <tr>
      
    <th><?php   _e( 'Id', 'aistore' ); ?></th>
        <th><?php   _e( 'Title', 'aistore' ); ?></th>
         <th><?php   _e( 'Role', 'aistore' ); ?></th>
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
    <td> 	

  <?php 
			 
if($row->sender_email ==$current_user_email_id)
{
     $role="Sender";
	$email=$row->receiver_email; 
}
  else
  {
 	 $role="Receiver";
	 $email=$row->sender_email;
  }
echo $role;
?>

		  </td>
		   
		  	   <td> 		   <?php echo $row->amount .  get_woocommerce_currency_symbol();?>  </td>
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
   if(!sanitize_text_field($_REQUEST['eid'])){
    
    	 $add_escrow_page_url  =  esc_url( add_query_arg( array(
    'page_id' => get_option('add_escrow_page_id') ,
	'eid'=> $eid,
), home_url() ) );

    ?>
    
   
<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($add_escrow_page_url) ; ?>" /> 
  
 <?php   }
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
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action') 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
  
    
} 

$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d'", 
   'disputed' , $eid   ) );
sendNotificationDisputed($eid);
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

$Payment_details = __( 'Payment transaction for the accept escrow with escrow id', 'aistore' );

 $details=$Payment_details.$eid ; 
 
 

$escrow_fee=(get_option('escrow_accept_fee')/ 100) * $amount;


$wallet = new Woo_Wallet_Wallet();

$user_id=get_current_user_id();

$wallet->debit($user_id,$escrow_fee,$details);
$wallet->credit(get_option('escrow_user_id'),$escrow_fee,$details);

sendNotificationAccepted($eid);

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

$Payment_details = __( 'Payment transaction for the release escrow with escrow id', 'aistore' );

 $details=$Payment_details.$eid ; 


 
$wallet = new Woo_Wallet_Wallet();
$wallet->debit(get_option('escrow_user_id'),$escrow_amount,$details);
$wallet->credit($id,$escrow_amount,$details);
sendNotificationReleased($eid  );
?>
<div>
<strong> <?php  _e( 'Released Successfully', 'aistore' ) ?></strong></div>
<?php
}
 





// Sender Create escrow  to excute cancel button 
// Receiver  accept or cancel escrow

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

$sender_escrow_fee = $wpdb->get_var( $wpdb->prepare( "SELECT escrow_fee from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );
 
$sender_email = $wpdb->get_var( $wpdb->prepare( "SELECT sender_email from {$wpdb->prefix}escrow_system where id  = %d", $eid ) );

$sender_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID from {$wpdb->prefix}users where user_email  = %s", $sender_email ) );


$Payment_details = __( 'Payment transaction for the cancel escrow with escrow id', 'aistore' );

 $details=$Payment_details.$eid ; 


$wallet = new Woo_Wallet_Wallet();


$wallet->debit(get_option('escrow_user_id'),$escrow_amount,$details);
$wallet->credit($sender_id,$escrow_amount,$details);

  $cancel_escrow_fee  = get_option('cancel_escrow_fee');
    
   if($cancel_escrow_fee=='yes'){
    $wallet->debit(get_option('escrow_user_id'),$sender_escrow_fee,$details);
    $wallet->credit($sender_id,$sender_escrow_fee,$details);
 sendNotificationCancelled($eid  );
       
  }
?>
<div>
<strong><?php  _e( 'Cancelled Successfully', 'aistore' ) ?></strong></div>
<?php
}
 

 
 
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
	      <div class="alert alert-success" role="alert">
 <strong>Escrow Status   <?php echo $escrow->status;?></strong>
  </div>
	  
	      <?php
     echo "<h1>#". $escrow->id ." ".$escrow->title ."</h1><br>";
     
     
  printf(__( "Term Condition : %s", 'aistore' ),html_entity_decode($escrow->term_condition)."<br>");
  printf(__( "Sender :  %s", 'aistore' ),$escrow->sender_email."<br>");
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
$wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_documents WHERE eid=%d", $eid)  );
 
  
?> 
  
    <table class="table">
    <?php
    foreach($escrow_documents as $row):
     
    ?> 
	
	<div class="document_list">
   


  <p><a href="<?php echo $row->documents; ?>" target="_blank">
	       <b><?php echo $row->documents_name ; ?></b></a></p>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>

<hr>
    
    <?php endforeach;
    
    
    ?>
    </table>
<br>
	   <div>  
    

  <link  rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">


  <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

	<label for="documents"> <?php   _e( 'Documents', 'aistore' ); ?> : </label>
<form  method="post"  action="<?php echo admin_url('admin-ajax.php').'?action=custom_action&eid='.$eid; ?>" class="dropzone" id="dropzone">
    <?php 
wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' );
?>
  <div class="fallback">
    <input id="file" name="file" type="file" multiple  />
    <input type="hidden" name="action" value="custom_action" type="submit"  />
  </div>

</form>


       
     
     </div>
     <br>
     
     <?php 
      
}


// Escrow Discussion
 
	


private	function escrow_discussion($escrow){
     $message_page_url  =    get_option('escrow_message_page');
    
   if($message_page_url=='no'){
      return "";
       
  }
   
    
	$user_login = get_the_author_meta( 'user_login', get_current_user_id() );


?>

     
	 
<div>
    <br>
<form class="wordpress-ajax-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>"  >
<?php 
wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' );
?>
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
<input type="hidden" name="action" value="custom_action" />
 <input type="hidden" name="escrow_id"  id="escrow_id" value="<?php echo $escrow->id; ?>" />
<input class="input btn btn-small" type="submit" name="submit" value="<?php _e('Submit Message', 'aistore') ?>"/>
</form> 
</div>

<div id="feedback"></div>
 
	
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
  <input type="submit"  name="submit" value="<?php  _e( 'Accept', 'aistore' ) ?>">
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
  <input type="submit"  name="submit" value="<?php  _e( 'Cancel Escrow', 'aistore' ) ?>">
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
  <input type="submit"  name="submit" value="<?php  _e( 'Release', 'aistore' ) ?>">
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
  <input type="submit"  name="submit" value="<?php  _e( 'Dispute', 'aistore' ) ?>">
  <input type="hidden" name="action" value="disputed" />
</form> <?php  }

}



 add_action( 'wp_ajax_custom_action', 'aistore_upload_file' );


function aistore_upload_file() {
    
  

	 global $wpdb;



  $eid=sanitize_text_field($_REQUEST['eid']);

$user_id= get_current_user_id();

$email_id = get_the_author_meta( 'user_email', get_current_user_id() );
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT count(id) as count FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '".   $email_id."' or receiver_email = '".   $email_id."' ) and id=%s ",$eid ));

  $c=(int)$escrow->count;
 if($c>0){
     
    
 if ( isset($_POST['aistore_nonce']) ) {
        $upload_dir = wp_upload_dir();
 
        if ( ! empty( $upload_dir['basedir'] ) ) {
            $user_dirname = $upload_dir['basedir'].'/documents/'.$eid;
            if ( ! file_exists( $user_dirname ) ) {
                wp_mkdir_p( $user_dirname );
            }
 
            $filename = wp_unique_filename( $user_dirname, $_FILES['file']['name'] );
            
            echo "filename".$filename;
            
            move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']), $user_dirname .'/'. $filename);
            
            
            $image=$upload_dir['baseurl'].'/documents/'.$eid.'/'.$filename;
//             // save into database $image;
      


$wpdb->query( $wpdb->prepare( "INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name) VALUES ( %d,%s,%d,%s)", array( $eid,$image,$user_id,$filename) ) );
        }
    }

  wp_die();
}
else{
   echo "Unauthorized user"; 
}


}

 add_action( 'wp_ajax_custom_action', 'aistore_chat_box' );


function aistore_chat_box() {
    
  

	 global $wpdb;

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
  return   _e( 'Sorry, your nonce did not verify.', 'aistore' ) ;
} 


$message=sanitize_text_field(htmlentities($_POST['message']));
  $escrow_id=sanitize_text_field($_POST['escrow_id']);
  

    $user_login = get_the_author_meta('user_login', get_current_user_id());
        



//issue 1


   $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array($escrow_id, $message, $user_login)));

   

  wp_die();



}



 add_action( 'wp_ajax_escrow_discussion', 'aistore_escrow_discussion' );

   function aistore_escrow_discussion( ) {
        
    
   global  $wpdb;
$id=sanitize_text_field($_REQUEST['id']);

$user_email = get_the_author_meta( 'user_email', get_current_user_id() );

      $discussions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion ed , {$wpdb->prefix}escrow_system es WHERE ed.eid= es.id and ed.eid=%s and (es.sender_email=%s or es.receiver_email=%s ) order by ed.id desc", $id,$user_email,$user_email));
      

        foreach ($discussions as $row):
            
?> 
	
	<div class="discussionmsg">
   
  <p><?php echo html_entity_decode($row->message); ?></p>
  
  <br /><br />
  <b><?php echo $row->user_login; ?> </b>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>
 
<hr>
    
    <?php
        endforeach;   
        
        wp_die();
    }
    
    



?>