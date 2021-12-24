<?php

  function sendNotificationPaymentRefund($eid){
      $headers = array('Content-Type: text/html; charset=UTF-8');

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;

     

    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));
    
    $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 


    
    include_once dirname(__FILE__) . '/notification.php';

    $sender_email= $escrow->sender_email;

 
    $subject = $Seller_Deposit;

    $n = array();
    $n['message'] = $Seller_Deposit;
     $n['reference_id'] = $eid;
    $n['type'] = "success";
    
    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $user_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);
    
    
      $subject = $Seller_Deposit;

    $n = array();
    $n['message'] = $Buyer_Deposit;
    $n['reference_id'] = $eid;
    $n['type'] = "success";
    
    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $sender_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);

  }
  
  
 function sendNotificationPaymentAccepted($eid)
{

$headers = array('Content-Type: text/html; charset=UTF-8');

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;

     

    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));
    
    $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 


    
    include_once dirname(__FILE__) . '/notification.php';

    $sender_email= $escrow->sender_email;

 
    $subject = $Seller_Deposit;

    $n = array();
    $n['message'] = $Seller_Deposit;

    $n['type'] = "success";
      $n['reference_id'] = $eid;
    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $user_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);
    
    
      $subject = $Seller_Deposit;

    $n = array();
    $n['message'] = $Buyer_Deposit;

    $n['type'] = "success";
      $n['reference_id'] = $eid;
    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $sender_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);

    // ob_start();

    // include dirname(__FILE__) . "/notification/partner_created_escrow.php";

    // $message = ob_get_clean();
    // wp_mail($party_email, $subject, $message ,$headers  );

    // //send email to self
    // $message = "You have successfully created the escrow #" . $eid;
    // $subject = $created_escrow;



    // wp_mail($user_email, $subject, $message,$headers );

    
      
    
    // $n = array();
    // $n['message'] = $created_escrow;

    // $n['type'] = "success";
    // $n['url'] = $details_escrow_page_id_url ;

    // $n['user_email'] = $user_email;
    // aistore_notification_new($n);
 
}


function sendNotificationCreated($eid)
{

$headers = array('Content-Type: text/html; charset=UTF-8');

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;

     

    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));
    
    $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 


    
    include_once dirname(__FILE__) . '/notification.php';

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    // send email to party
  

    
    $subject = $partner_created_escrow;

    $n = array();
    $n['message'] = $partner_created_escrow;

    $n['type'] = "success";
    
    
    
	   $n['reference_id'] = $eid;

    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $party_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);

    ob_start();

    include dirname(__FILE__) . "/notification/partner_created_escrow.php";

    $message = ob_get_clean();
    wp_mail($party_email, $subject, $message ,$headers  );

    //send email to self
    $message = "You have successfully created the escrow #" . $eid;
    $subject = $created_escrow;



    wp_mail($user_email, $subject, $message,$headers );

    
      
    
    $n = array();
    $n['message'] = $created_escrow;
  $n['reference_id'] = $eid;
    $n['type'] = "success";
    $n['url'] = $details_escrow_page_id_url ;

    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);
 
}





function sendNotificationPaymentDepositSuccess($eid)
{

$headers = array('Content-Type: text/html; charset=UTF-8');

    $user_email = "";
    global $wpdb;
   
    $q = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%d ", $eid);

    $escrow = $wpdb->get_row($q);

    include_once dirname(__FILE__) . '/notification.php';
 
 
 
    // send email to party
    $message = $Buyer_Deposit;

    $subject = $Buyer_Deposit;

    ob_start();

    include dirname(__FILE__) . "/notification/buyer_admin_accept_payment.php";

    $message = ob_get_clean();

    wp_mail($escrow->sender_email, $subject, $message ,$headers);

    //aistore_notification($subject ,"success",$escrow->sender_email);
    $n = array();
    $n['message'] = $Buyer_Deposit;
  $n['reference_id'] = $eid;
    $n['type'] = "success";
 
 
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    $n['user_email'] = $escrow->sender_email;
    aistore_notification_new($n);
    aistore_send_email($n);
    //send email to seller
    

    // send email to seller
    $subject = $Seller_Deposit;

    // $escrow->sender_email  ." has deposited the payment ". $escrow->total_buyer_fee  ." into  the escrow for  the transaction   #".$eid;
    

    $message = $escrow->sender_email . " has deposited the payment " . $escrow->total_buyer . " into  the escrow for  the transaction   #" . $eid;

    //aistore_notification($message);
    $seller_name = getFullName($escrow->receiver_email);
    $sender_email = $escrow->sender_email;

    $a = $escrow->amount + $escrow->total_fee;

    $deposit_amount = number_format($a) . " " . $escrow->currency_amount; // need to review as total fee to be set here
    

    ob_start();

    include dirname(__FILE__) . "/notification/seller_admin_accept_payment.php";

    $message = ob_get_clean();

 



    wp_mail($escrow->receiver_email, $subject, $message ,$headers);

    $n = array();
    $n['message'] = $Seller_Deposit;
  $n['reference_id'] = $eid;
    $n['type'] = "success";


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    $n['user_email'] = $escrow->receiver_email;
    aistore_notification_new($n);
    aistore_send_email($n);
   
}

function sendNotificationMarkPaid($eid)
{   
$headers = array('Content-Type: text/html; charset=UTF-8');


    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);
 

    // ''include_once dirname(__FILE__) . '/email/notification.php';
    

    $n = array();
    $n['message'] = "You have marked escrow #" . $eid . " as payed";
  $n['reference_id'] = $eid;
    $n['type'] = "success";
   
   
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}
function sendNotificationAccepted($eid)
{
    $user_email = "";

    //$attachments = getInvoiceLocation($eid);
    // get invoice attachment
    global $wpdb;
   
    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));
    include_once dirname(__FILE__) . '/notification.php';

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    $n = array();
    $n['message'] = $partner_accept_escrow;
      $n['reference_id'] = $eid;
    $n['type'] = "success";
 
 
 
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $party_email;
    aistore_notification_new($n);
    aistore_send_email($n);

    $subject = $partner_accept_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/partner_accept_escrow.php";

    $message = ob_get_clean();


$headers = array('Content-Type: text/html; charset=UTF-8');


    wp_mail($party_email, $subject, $message,$headers );

 
 

    //send email to self
    

    ob_start();

    include dirname(__FILE__) . "/notification/self_accept_escrow.php";

    $message = ob_get_clean();

    $subject = "You have successfully accepted the escrow ";

    wp_mail($user_email, $subject, $message,$headers );

     

    $n = array();
    $n['message'] = $accept_escrow;

    $n['type'] = "success";
  
  
  
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 


  $n['reference_id'] = $eid;
    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}

function sendNotificationDisputed($eid)
{
   
$headers = array('Content-Type: text/html; charset=UTF-8');


    $user_email = "";
    $partner_dispute_escrow = "";
    $dispute_escrow = "";
    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;
    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));

    include_once dirname(__FILE__) . '/notification.php';

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    // send email to party
    $message = "Your partner " . $user_email . " has disputed the escrow #";
    $subject = $partner_dispute_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/partner_dispute_escrow.php";

    $message = ob_get_clean();

    wp_mail($party_email, $subject, $message ,$headers);

    $n = array();
    $n['message'] = $partner_dispute_escrow;

    $n['type'] = "warning";
    

  $n['reference_id'] = $eid;
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $party_email;
    aistore_notification_new($n);
    aistore_send_email($n);

    //send email to self
    $message = $dispute_escrow;
    $subject = $dispute_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/dispute_escrow.php";

    $message = ob_get_clean();

    wp_mail($user_email, $subject, $message,$headers );

    $n = array();
    $n['message'] = $dispute_escrow;

    $n['type'] = "success";
  $n['reference_id'] = $eid;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}

function sendNotificationReleased($eid)
{
$headers = array('Content-Type: text/html; charset=UTF-8');


    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

  
    global $wpdb;
    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));

    require_once dirname(__FILE__) . '/notification.php';

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    // send email to party
    $message = $partner_release_escrow;
    $subject = $partner_release_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/partner_release_escrow.php";

    $message = ob_get_clean();

    wp_mail($party_email, $subject, $message,$headers  );

    $n = array();
    $n['message'] = $partner_release_escrow;

    $n['type'] = "success";
  $n['reference_id'] = $eid;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    
    $n['user_email'] = $party_email;
    
    
    aistore_notification_new($n);
    aistore_send_email($n);

    //send email to self
    $message = $release_escrow;
    $subject = $release_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/release_escrow.php";

    $message = ob_get_clean();

    wp_mail($user_email, $subject, $message ,$headers );

    $n = array();
    $n['message'] = $release_escrow;

    $n['type'] = "success";
   $n['reference_id'] = $eid;
 
 
	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}

function sendNotificationCancelled($eid)
{$headers = array('Content-Type: text/html; charset=UTF-8');


    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;

    
    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));
   
   
    include_once dirname(__FILE__) . '/notification.php';

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    // send email to party
    $message = "Your partner " . $user_email . " has cancelled the escrow " . $eid;
    
    
    
    $subject = $partner_cancel_escrow;

    $n = array();
    $n['message'] = $partner_cancel_escrow;

    $n['type'] = "warning";
  $n['reference_id'] = $eid;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $party_email;
    aistore_notification_new($n);
    aistore_send_email($n);

   /* ob_start();

    include dirname(__FILE__) . "/notification/partner_cancel_escrow.php";

    $message = ob_get_clean();
    
    */
       $message = $partner_cancel_escrow;
    
    wp_mail($party_email, $subject, $message ,$headers);

    //send email to self
    $message = "You have successfully cancelled the escrow #" . $eid;
    $subject = $cancel_escrow;

$headers = array('Content-Type: text/html; charset=UTF-8');




    wp_mail($user_email, $subject, $message,$headers  );

    $n = array();
    $n['message'] = $cancel_escrow;

    $n['type'] = "success";
  $n['reference_id'] = $eid;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}

function sendNotificationShippingDetailsUpdated($eid)
{
    $headers = array('Content-Type: text/html; charset=UTF-8');


     

    $user_id = get_current_user_id();
    $user_email = get_the_author_meta('user_email', $user_id);

    global $wpdb;

    $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ", $eid));

    include_once dirname(__FILE__) . '/notification.php';

    if ($user_email == $escrow->sender_email)
    {

        $party_email = $escrow->receiver_email;

    }
    else
    {
        $party_email = $escrow->sender_email;

    }

    // send email to party
    $message = "Your partner " . $user_email . " has updated the shipping details for the escrow " . $eid;
    $subject = $partner_shipping_escrow;

    $n = array();
    $n['message'] = $partner_shipping_escrow;

    $n['type'] = "success";
  $n['reference_id'] = $eid;


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $party_email;
    aistore_notification_new($n);
    aistore_send_email($n);

    ob_start();

    include dirname(__FILE__) . "/notification/partner_shipping_escrow.php";

    $message = ob_get_clean();

    wp_mail($party_email, $subject, $message,$headers  );

    //send email to self
    $message = "You have updated the shipping details for the escrow #" . $eid;
    $subject = $shipping_escrow;

    ob_start();

    include dirname(__FILE__) . "/notification/shipping_escrow.php";

 
    $message = ob_get_clean();
    wp_mail($user_email, $subject, $message ,$headers );

    $n = array();
    $n['message'] = $shipping_escrow;
  $n['reference_id'] = $eid;
    $n['type'] = "success";


	 $details_escrow_page_id_url =  esc_url( add_query_arg( array(
    'page_id' => get_option('details_escrow_page_id'),
    'eid' => $eid,
), home_url() ) ); 



    $n['url'] = $details_escrow_page_id_url ;
    
    
    
    $n['user_email'] = $user_email;
    aistore_notification_new($n);
    aistore_send_email($n);

}

