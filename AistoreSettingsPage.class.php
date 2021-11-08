<?php




class AistoreSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'aistore_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'aistore_page_register_setting' ) );
        
    

	
    }

    /**
     * Add options page
     */
public function aistore_add_plugin_page()
{
    // This page will be under "Settings"
    add_options_page('Settings Admin', __( 'My Setting', 'aistore' ), 'administrator', 'my-setting-admin', array(
        $this,
        'aistore_page_setting'
    ));
    
    
    
    
    
    add_menu_page(__( 'Escrow System', 'aistore' ),  __('Escrow System', 'aistore' ), 'administrator', 'disputed_escrow_list');
    
    
     
    add_submenu_page('disputed_escrow_list', __('Escrow List', 'aistore' ), __('', 'aistore' ), 'administrator', 'aistore_user_escrow_list', array(
        $this,
        'aistore_user_escrow_list'
    ));
    
    
    add_submenu_page('disputed_escrow_list', __('Disputed Escrow List','aistore'), __('Disputes','aistore'), 'administrator', 'disputed_escrow_list', array(
        $this,
        'aistore_disputed_escrow_list'
    ));
    
       add_submenu_page('disputed_escrow_list', __('Disputed Escrow Details','aistore'), __('','aistore'), 'administrator', 'disputed_escrow_details', array(
        $this,
        'aistore_disputed_escrow_details'
    ));
    
    add_submenu_page('disputed_escrow_list', __('Setting','aistore'), __('Setting','aistore'), 'administrator', 'aistore_page_setting', array(
        $this,
        'aistore_page_setting'
    ));
    
       add_submenu_page('disputed_escrow_list', __('Notification Setting','aistore'), __('Notification Setting','aistore'), 'administrator', 'notification_setting', array(
        $this,
        'aistore_notification_setting'
    ));
    
    
     add_submenu_page('disputed_escrow_list', __('Email Setting','aistore'), __('Email Setting','aistore'), 'administrator', 'email_setting', array(
        $this,
        'aistore_email_setting'
    ));
    
    
    
}

function aistore_user_escrow_list(){
    
	
global  $wpdb;


  
   


$id=sanitize_text_field($_REQUEST['id']);

$user_email = get_the_author_meta( 'user_email', $id );



$page_id=get_option('details_escrow_page_id'); 

 $results = $wpdb->get_results( 
   $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE (sender_email=%s or receiver_email=%s ) order by id desc", $user_email,$user_email)
                 );
     
  ?>
  <h1> <?php  _e( 'Escrow List', 'aistore' ) ?> </h1>
  <table class="widefat fixed striped">
        
     <tr>
         <th>Id</th>
      <th>Title</th>
       <th>Status</th>
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>       <th>Date</th>
     </tr>
      

    <?php 
    
    if($results==null)
	{
	     _e( "No Escrow Found", 'aistore' );

	}
	else{
    foreach($results as $row):
      
	 $escrow_details_page_url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
    'eid' => $row->id,
), home_url() ) ); 
    ?> 
      <tr>

		   <td> 	<a href="<?php echo $escrow_details_page_url; ?>" >	
		   
		   <?php echo $row->id ; ?> </a> </td>
		  
		   
		   <td> 		   <?php echo $row->title ; ?> </td>
		  
		   <td> 		   <?php echo $row->status ; ?> </td>
		   
		   <td> 		   <?php echo $row->amount ; ?> </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
		     <td> 		   <?php echo $row->created_at ; ?> </td>
       
                
            </tr>
    <?php endforeach;
	}
	
	?>



    </table>
	<?php 


}

    //aistore_disputed_escrow_details
    
    function aistore_disputed_escrow_details(){
        
   


   $eid=sanitize_text_field($_REQUEST['eid']);
   
   
$user_id= get_current_user_id();
	 
$email_id = get_the_author_meta( 'user_email',$user_id );

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


  

 

ob_start();


if(!isset($eid))
{

	
	 $url  =  "/wp-admin/admin.php?page=disputed_escrow_list";

?>
	<div><a href="<?php echo $url ; ?>" >
	    <?php   _e( 'Go To Escrow List Page', 'aistore' ); ?> 
	     </a></div>
<?php	

return ob_get_clean();  
}



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

//sendNotificationAccepted($eid);

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

?>
<div>
<strong> <?php  _e( 'Released Successfully', 'aistore' ) ?></strong></div>
<?php
}
 



if(isset($_POST['submit']) and $_POST['action']=='chat_custom_action')
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' ) ;
    
}

$message=sanitize_text_field(htmlentities($_POST['message']));
  $escrow_id=sanitize_text_field($_POST['escrow_id']);
    $user_login = get_the_author_meta('user_login', get_current_user_id());

//issue 1

   $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array($escrow_id, $message, $user_login)));

    //$url  = "/wp-admin/admin.php?page=disputed_escrow_details";
   
  


  
  wp_die();


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


       
    
    
     <!--<div id="feedback"></div>-->
     
     </div>
     <br>
     
     <?php 
      
          $message_page_url  =    get_option('escrow_message_page');
    
   if($message_page_url=='no'){
      return "";
       
  }
   
    
	$user_login = get_the_author_meta( 'user_login', get_current_user_id() );


?>

     
	 
<div>
    <br>
<form method="POST" action="" name="chat_custom_action" enctype="multipart/form-data"  >
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

 
 <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
 
 <input type="hidden" name="action" value="chat_custom_action" />
 <input type="hidden" name="escrow_id"  id="escrow_id" value="<?php echo $escrow->id; ?>" />
 
  <input type="submit"  name="submit" value="<?php  _e( 'Submit Message', 'aistore' ) ?>">

</form> 
</div>

<!--<div id="feedback"></div>-->

 <br>
 <div class="card">
	
	<?php
        
      global  $wpdb;
//$id=sanitize_text_field($_REQUEST['eid']);

//$user_email = get_the_author_meta( 'user_email', get_current_user_id() );

  $discussions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion  WHERE eid=%d order by id desc", $eid));
      	

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
        
    


 
    
    ?>
</div>
</div>
<?php

}

 
	




 

// disputed escrow list

function aistore_disputed_escrow_list()
{
	

global  $wpdb;
$page_id=get_option('details_escrow_page_id'); 


 $results = $wpdb->get_results( 
   $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE status = %s",  'disputed') 
                 );
 

    
     
  ?>
    <h1> <?php  _e( 'All disputed escrows', 'aistore' ) ?> </h1>
	
	
  <table class="widefat fixed striped">
     
      

    <?php 
    
    if($results==null)
	{
	    
	     _e( "No Escrow Found", 'aistore' );
	
	}
	else{
		
    foreach($results as $row):
     

 $link= '<a href="/wp-admin/admin.php?page=disputed_escrow_details&eid='.$row->id.'">'.$row->id.'</a>';
    ?> 
      <tr>

		   
		   <td> 	 
		   <?php echo $link ; ?>  </td>
		  
		   
		   <td> 		   <?php echo $row->title ; ?> </td>
		  
		   <td> 		   <?php echo $row->status ; ?> </td>
		  
		   
		   <td> 		   <?php echo $row->amount ; ?> </td>
		   <td> 		   <?php echo $row->sender_email ; ?> </td>
		   <td> 		   <?php echo $row->receiver_email ; ?> </td>
		  
              
            </tr>
    <?php endforeach;
	}
	?>

	

    </table>
	<?php 
	
}


// page Setting

function aistore_page_register_setting() {
	//register our settings
	register_setting( 'aistore_page', 'add_escrow_page_id' );
	register_setting( 'aistore_page', 'create_escrow_page_2' );
	register_setting( 'aistore_page', 'list_escrow_page_id' );
	register_setting( 'aistore_page', 'details_escrow_page_id' );

	register_setting( 'aistore_page', 'escrow_user_id' );
	
	register_setting( 'aistore_page', 'escrow_create_fee' );
	register_setting( 'aistore_page', 'escrow_accept_fee' );
	register_setting( 'aistore_page', 'escrow_message_page' );
	register_setting( 'aistore_page', 'cancel_escrow_fee' );
    register_setting( 'aistore_page', 'currency' );
}


function aistore_notification_register_setting() {	
	register_setting( 'aistore_notification_page', 'created_escrow' );
	register_setting( 'aistore_notification_page', 'partner_created_escrow' );
	register_setting( 'aistore_notification_page', 'accept_escrow' );
	register_setting( 'aistore_notification_page', 'partner_accept_escrow' );
	
	register_setting( 'aistore_notification_page', 'dispute_escrow' );
	register_setting( 'aistore_notification_page', 'partner_dispute_escrow' );
	register_setting( 'aistore_notification_page', 'release_escrow' );
	register_setting( 'aistore_notification_page', 'partner_release_escrow' );
	
	
	register_setting( 'aistore_notification_page', 'cancel_escrow' );
	register_setting( 'aistore_notification_page', 'partner_cancel_escrow' );
	register_setting( 'aistore_notification_page', 'shipping_escrow' );
	register_setting( 'aistore_notification_page', 'partner_shipping_escrow' );
	
	register_setting( 'aistore_notification_page', 'buyer_deposit' );
	register_setting( 'aistore_notification_page', 'seller_deposit' );
	register_setting( 'aistore_notification_page', 'Buyer_Mark_Paid' );

}
//email
function aistore_email_register_setting() {
register_setting( 'aistore_email_page', 'email_created_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_created_escrow' );
	register_setting( 'aistore_email_page', 'email_accept_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_accept_escrow' );
	
	register_setting( 'aistore_email_page', 'email_dispute_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_dispute_escrow' );
	register_setting( 'aistore_email_page', 'email_release_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_release_escrow' );
	
	
	register_setting( 'aistore_email_page', 'email_cancel_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_cancel_escrow' );
	register_setting( 'aistore_email_page', 'email_shipping_escrow' );
	register_setting( 'aistore_email_page', 'email_partner_shipping_escrow' );
	
	register_setting( 'aistore_email_page', 'email_buyer_deposit' );
	register_setting( 'aistore_email_page', 'email_seller_deposit' );
	register_setting( 'aistore_email_page', 'email_Buyer_Mark_Paid' );
}


 function aistore_page_setting() {
	 
	  $pages = get_pages(); 
	
	   ?>
	  <div class="wrap">
	  
	  <div class="card">
	  
<h3><?php  _e( 'Escrow Setting', 'aistore' ) ?></h3>
 
	                     
<p><?php  _e( 'Step 1', 'aistore' ) ?></p>


<p><?php  _e( 'Install required plugin WooCommerce link https://wordpress.org/plugins/woocommerce/ and tera-wallet link https://wordpress.org/plugins/woo-wallet/ and activate as per their setup process. ', 'aistore' ) ?></p>

<hr />

  
<p><?php  _e( 'Step 2', 'aistore' ) ?></p>

<?php
if(isset($_POST['submit']) and $_POST['action']=='create_all_pages' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify.', 'aistore' );

   exit;
}
$escrow_user_id=sanitize_text_field($_REQUEST['escrow_user_id']);

 $my_post = array(
   'post_title'     => 'Create Escrow',
    'post_type'     => 'page',
   'post_content'  => '[aistore_escrow_system]',
   'post_status'   => 'publish',
   'post_author'   => 1
 );

 // Insert the post into the database
$add_escrow_page_id=wp_insert_post( $my_post );


update_option( 'add_escrow_page_id', $add_escrow_page_id);




 $my_post = array(
   'post_title'     => 'Escrow List',
    'post_type'     => 'page',
   'post_content'  => '[aistore_escrow_list] ',
   'post_status'   => 'publish',
   'post_author'   => 1
 );

 // Insert the post into the database
$list_escrow_page_id=wp_insert_post( $my_post );


update_option( 'list_escrow_page_id', $list_escrow_page_id);

 $my_post = array(
   'post_type'     => 'page',
   'post_title'    => 'Escrow Detail',
   'post_content'  => '[aistore_escrow_detail]',
   'post_status'   => 'publish',
   'post_author'   => 1
 );

 // Insert the post into the database
$details_escrow_page_id=wp_insert_post( $my_post );

update_option( 'details_escrow_page_id', $details_escrow_page_id);

   $user_id = username_exists( $escrow_user_id );
   
if ( ! $user_id ) {
$user_id = wp_insert_user( array(
  'user_login' => $escrow_user_id,
  'user_pass' => $escrow_user_id,
  'user_email' => $escrow_user_id,
  'first_name' => $escrow_user_id,
  'last_name' => $escrow_user_id,
  'display_name' => $escrow_user_id,
  'role' => 'administrator'
));
update_option( 'escrow_user_id', $user_id);
update_option( 'escrow_user_name', $escrow_user_id);
}
 

 $pages = get_pages(); 
}
else{
    
     $pages = get_pages(); 
?>
 <form method="POST" action="" name="create_all_pages" enctype="multipart/form-data"> 
    <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
    
<p><?php  _e( 'Create all pages with short codes automatically to ', 'aistore' ) ?>
<br><br>
<?php  _e( 'Escrow Admin Email ID: ', 'aistore' ) ?>
<input type="email" name="escrow_user_id" value="<?php echo esc_attr( get_option('escrow_user_name') ); ?>" />

<input class="input" type="submit" name="submit" value="<?php  _e( 'Click here', 'aistore' ) ?>"/>
<input type="hidden" name="action"  value="create_all_pages"/>
    </form>
    
<?php
}
?>
<p><?php  _e( 'Create 4 pages with short codes and select here  ', 'aistore' ) ?></p>


<form method="post" action="options.php">
    <?php settings_fields( 'aistore_page' ); ?>
    <?php do_settings_sections( 'aistore_page' ); ?>
	
    <table class="form-table">
	
	 <tr valign="top">
        <th scope="row"><?php  _e( 'Create Escrow form', 'aistore' ) ?></th>
        <td>
		<select name="add_escrow_page_id"  >
		 
		 
     <?php 

                    foreach($pages as $page){ 
					
					if($page->ID==get_option('add_escrow_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
</select>


<p>Create a page add this shortcode <strong> [aistore_escrow_system] </strong> and then select that page here. </p>

</td>
        </tr>  
        
        	
	
	
		
		  <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow List page', 'aistore' ) ?></th>
        <td>
		<select name="list_escrow_page_id">
		  
		   <?php 
                    foreach($pages as $page){ 
					
					if($page->ID==get_option('list_escrow_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option  value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 

		   
		   
		   
</select>



<p>Create a page add this shortcode <strong> [aistore_escrow_list] </strong> and then select that page here. </p>


</td>
        </tr>  
		
		
		 <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Details Page', 'aistore' ) ?></th>
        <td>
		<select name="details_escrow_page_id" >
		 
		 
		  <?php 
                    foreach($pages as $page){ 
                        
				

					if($page->ID==get_option('details_escrow_page_id'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option  value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
		 
					  
					
 
</select>



<p>Create a page add this shortcode <strong> [aistore_escrow_detail] </strong> and then select that page here. </p>




</td>
        </tr>  </table>
        
        	<hr/>
        	
<p><?php  _e( 'Step 3', 'aistore' ) ?></p>


<p><?php  _e( 'Create an admin account and set its ID this will be used to hold payments ', 'aistore' ) ?></p>

        	
        <table class="form-table">
        
        <h3><?php  _e( 'Admin Escrow Setting', 'aistore' ) ?></h3>
        
		 <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Admin ID ', 'aistore' ) ?></th>
        <td>
		<select name="escrow_user_id" >
		 
		 
		  <?php 
		  
		   
        $blogusers = get_users( [ 'role__in' => [ 'administrator' ] ] );


                    foreach($blogusers as $user){ 
                        
					
					if($user->ID==get_option('escrow_user_id'))
					{
		 echo '	<option selected value="'.$user->ID.'">'.$user->display_name .'</option>';
		 
		  } else {
                      
   echo '	<option  value="'.$user->ID.'">'.$user->display_name .'</option>';
		 
		

		}  
	 } ?> 
  </tr>  
</select>

<p><?php  _e( 'Add an user with admin roll and then select its ID here', 'aistore' ) ?></p>
 <tr valign="top">
 <th scope="row"><?php  _e( 'Chat system public people show or not', 'aistore' ) ?></th>
        <td>
            <?php $msg_value=get_option('escrow_message_page');?>
            
            <select name="escrow_message_page" id="escrow_message_page">
               
            <option selected value="yes" <?php selected(
                $msg_value,
                'yes'
            ); ?>>Yes</option>
            <option value="no" <?php selected(
                $msg_value,
                'no'
            ); ?>>No</option>
  
</select>
	
</td>
        </tr>  
        
        
        
             
         <tr valign="top">
        <th scope="row"><?php  _e( 'Currency', 'aistore' ) ?></th>
        <td>
            
       <?php $currency=get_option('currency');?>
                
            <select name="currency" id="currency">
               
            <option selected value="INR" <?php selected(
                $currency,
                'INR'
            ); ?>>INR</option>
            <option value="EUR" <?php selected(
                $currency,
                'EUR'
            ); ?>>EUR</option>
                <option selected value="USD" <?php selected(
                $currency,
                'USD'
            ); ?>>USD</option>
            <option value="GDP" <?php selected(
                $currency,
                'GDP'
            ); ?>>GDP</option>
  
</select>
            
            
</td>
        </tr> 
        
        

 <tr valign="top">
 <th scope="row"><?php  _e( 'Cancel Escrow fee refund or not ', 'aistore' ) ?></th>
        <td>
            <?php $msg_value=get_option('cancel_escrow_fee');?>
            
            <select name="cancel_escrow_fee" id="cancel_escrow_fee">
               
            <option selected value="yes" <?php selected(
                $msg_value,
                'yes'
            ); ?>>Yes</option>
            <option value="no" <?php selected(
                $msg_value,
                'no'
            ); ?>>No</option>
  
</select>
	
</td>
        </tr>  
              
  
    </table>
    
    
    [ Admin who will manage escrow fee/disputes etc ]

    	<hr/>
        	 
        	
<p><?php  _e( 'Step 4', 'aistore' ) ?></p>


<p><?php  _e( 'Set fee here for the profits percentage ', 'aistore' ) ?></p>


        <table class="form-table">
        
        <h3><?php  _e( 'Escrow Fee Setting', 'aistore' ) ?></h3>
        
	 <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Create Fee', 'aistore' ) ?></th>
        <td><input type="number" name="escrow_create_fee" value="<?php echo esc_attr( get_option('escrow_create_fee') ); ?>" />%</td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Accept Fee', 'aistore' ) ?></th>
        <td><input type="number" name="escrow_accept_fee" value="<?php echo esc_attr( get_option('escrow_accept_fee') ); ?>" />%</td>
        </tr>
        
       
  
  
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
</div>
 <?php
	 
 }


function aistore_notification_setting(){
    ?>
      <h3><?php  _e( 'Notification Setting', 'aistore' ) ?></h3>
      
<form method="post" action="options.php">
    <?php settings_fields( 'aistore_notification_page' ); ?>
    <?php do_settings_sections( 'aistore_notification_page' ); ?>
    
       <table class="form-table">
        
     
        
	 <tr valign="top">
        <th scope="row"><?php  _e( 'Created Escrow', 'aistore' ) ?></th>
        <td>
            <textarea id="created_escrow" name="created_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('created_escrow') ); ?>
</textarea>
          </td>
        </tr>
        
         <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Created Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="partner_created_escrow" name="partner_created_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_created_escrow') ); ?>
</textarea>
           </td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php  _e( 'Accept Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="accept_escrow" name="accept_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('accept_escrow') ); ?>
</textarea>
            </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Accept Escrow', 'aistore' ) ?></th>
        <td>
              <textarea id="partner_accept_escrow" name="partner_accept_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_accept_escrow') ); ?>
</textarea>
           </td>
        </tr>
  
  
      
      <tr valign="top">
        <th scope="row"><?php  _e( 'Dispute Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="dispute_escrow" name="dispute_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('dispute_escrow') ); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Dispute Escrow', 'aistore' ) ?></th>
        <td>
              <textarea id="partner_dispute_escrow" name="partner_dispute_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_dispute_escrow') ); ?>
</textarea>
          </td>
        </tr>
  
  
  
     
      <tr valign="top">
        <th scope="row"><?php  _e( 'Release Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="release_escrow" name="release_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('release_escrow') ); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Release Escrow', 'aistore' ) ?></th>
        <td>
              <textarea id="partner_release_escrow" name="partner_release_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_release_escrow') ); ?>
</textarea>
          </td>
        </tr>
        
        
             <tr valign="top">
        <th scope="row"><?php  _e( 'Cancel Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="cancel_escrow" name="cancel_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('cancel_escrow') ); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Cancel Escrow', 'aistore' ) ?></th>
        <td>
              <textarea id="partner_cancel_escrow" name="partner_cancel_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_cancel_escrow') ); ?>
</textarea>
          </td>
        </tr>
        
        
        
             <tr valign="top">
        <th scope="row"><?php  _e( 'Buyer Deposit', 'aistore' ) ?></th>
        <td>
             <textarea id="buyer_deposit" name="buyer_deposit" rows="2" cols="50">
<?php echo esc_attr( get_option('buyer_deposit') ); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Seller Deposit', 'aistore' ) ?></th>
        <td>
              <textarea id="seller_deposit" name="seller_deposit" rows="2" cols="50">
<?php echo esc_attr( get_option('seller_deposit') ); ?>
</textarea>
          </td>
        </tr>
        
        
        
        
           <tr valign="top">
        <th scope="row"><?php  _e( 'Shipping Escrow', 'aistore' ) ?></th>
        <td>
             <textarea id="shipping_escrow" name="shipping_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('shipping_escrow') ); ?>
</textarea>
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Shipping Escrow', 'aistore' ) ?></th>
        <td>
              <textarea id="partner_shipping_escrow" name="partner_shipping_escrow" rows="2" cols="50">
<?php echo esc_attr( get_option('partner_shipping_escrow') ); ?>
</textarea>
          </td>
        </tr>
        
                  <tr valign="top">
        <th scope="row"><?php  _e( 'Buyer Mark Paid', 'aistore' ) ?></th>
        <td>
              <textarea id="Buyer_Mark_Paid" name="Buyer_Mark_Paid" rows="2" cols="50">
<?php echo esc_attr( get_option('Buyer_Mark_Paid') ); ?>
</textarea>
          </td>
        </tr>
  
    </table>
       <?php submit_button(); ?>

</form>
      <?php
}











function aistore_email_setting(){
    
    ?>
      <h3><?php  _e( 'Email Setting', 'aistore' ) ?></h3>
        
  <?php
  
  $editor=array(
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

?>
<form method="post" action="options.php">
    <?php settings_fields( 'aistore_email_page' ); ?>
    <?php do_settings_sections( 'aistore_email_page' ); ?>
    
       <table class="form-table">
        
     
        
	 <tr valign="top">
        <th scope="row"><?php  _e( 'Created Escrow', 'aistore' ) ?></th>
        <td>
              <?php
  
$content   =esc_attr( get_option('email_created_escrow') );;
$editor_id = 'email_created_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
          </td>
        </tr>
        
         <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Created Escrow', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_partner_created_escrow') );;
$editor_id = 'email_partner_created_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
           </td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php  _e( 'Accept Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_accept_escrow') );;
$editor_id = 'email_accept_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
            </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Accept Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_partner_accept_escrow') );;
$editor_id = 'email_partner_accept_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
           </td>
        </tr>
  
  
      
      <tr valign="top">
        <th scope="row"><?php  _e( 'Dispute Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_dispute_escrow') );;
$editor_id = 'email_dispute_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Dispute Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_partner_dispute_escrow') );;
$editor_id = 'email_partner_dispute_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
           
          </td>
        </tr>
  
  
  
     
      <tr valign="top">
        <th scope="row"><?php  _e( 'Release Escrow', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_release_escrow') );;
$editor_id = 'email_release_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Release Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_partner_release_escrow') );;
$editor_id = 'email_partner_release_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
          </td>
        </tr>
        
        
             <tr valign="top">
        <th scope="row"><?php  _e( 'Cancel Escrow', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_cancel_escrow') );;
$editor_id = 'email_cancel_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
         
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Cancel Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_partner_cancel_escrow') );;
$editor_id = 'email_partner_cancel_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
          </td>
        </tr>
        
        
        
             <tr valign="top">
        <th scope="row"><?php  _e( 'Buyer Deposit', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_buyer_deposit') );;
$editor_id = 'email_buyer_deposit';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Seller Deposit', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_seller_deposit') );;
$editor_id = 'email_seller_deposit';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          </td>
        </tr>
        
        
        
        
           <tr valign="top">
        <th scope="row"><?php  _e( 'Shipping Escrow', 'aistore' ) ?></th>
        <td>
            <?php
  
$content   =esc_attr( get_option('email_shipping_escrow') );;
$editor_id = 'email_shipping_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
        </td>
        </tr>
        
          <tr valign="top">
        <th scope="row"><?php  _e( 'Partner Shipping Escrow', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_partner_shipping_escrow') );;
$editor_id = 'email_partner_shipping_escrow';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
            
          </td>
        </tr>
        
                  <tr valign="top">
        <th scope="row"><?php  _e( 'Buyer Mark Paid', 'aistore' ) ?></th>
        <td>
            
            <?php
  
$content   =esc_attr( get_option('email_Buyer_Mark_Paid') );;
$editor_id = 'email_Buyer_Mark_Paid';

 
   
wp_editor( $content, $editor_id,   $editor);
 
?>
          
          </td>
        </tr>
  
    </table>
       <?php submit_button(); ?>

</form>
      <?php
}

}


    


if( is_admin() )
    $AistoreSettingsPage = new AistoreSettingsPage(); 


