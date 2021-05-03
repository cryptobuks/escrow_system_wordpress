<?php 
// redirect after escrow created 
 
 
//sendNotification($eid ,'disputed');


function sendNotificationAccepted($eid )
{
	
	$my_post = array(
  'post_title'    => __LINE__,
  'post_content'  => $eid,
  'post_status'   => 'draft' 
);
 
// Insert the post into the database
wp_insert_post( $my_post );


 
	 global $wpdb;
$headers[] = 'From: Globalescrow.uk <admin@globalescrow.uk>'."\r\n";
 
 
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s ",$eid ));

 
$user_id=get_current_user_id();
$user_email = get_the_author_meta('user_email', $user_id);

  if($user_email == $escrow->sender_email)
  {
 
  $party_email= $escrow->receiver_email ;
	
  }
else
{
	 $party_email=$escrow->sender_email;
 
}

// send email to party 
$message="Your partner ". $user_email ." has accepted the escrow ";
 $subject="Your partner ". $user_email ." has accepted the escrow ";
 
 	$my_post = array(
  'post_title'    => __LINE__,
  'post_content'  => $user_email.$party_email,
  'post_status'   => 'draft' 
);
 
// Insert the post into the database
wp_insert_post( $my_post );

 
ob_start();
 
include dirname(__FILE__) . "/notification/partner_accept_escrow.php";

$message = ob_get_clean(); 

  
  
wp_mail($party_email,$subject,$message, $headers);
	$my_post = array(
  'post_title'    => __LINE__,
  'post_content'  => $message,
  'post_status'   => 'draft' 
);
 
// Insert the post into the database
wp_insert_post( $my_post );
//send email to self



ob_start();
 
include dirname(__FILE__) . "/notification/self_accept_escrow.php";

$message = ob_get_clean(); 

$subject="You have successfully accepted the escrow ";
 
wp_mail($user_email ,$subject,$message, $headers);
  $my_post = array(
  'post_title'    => __LINE__,
  'post_content'  => $message.$user_email,
  'post_status'   => 'draft' 
);
 
// Insert the post into the database
wp_insert_post( $my_post );
   
   
}



function sendNotificationDisputed($eid  )
{
 
	 
$headers[] = 'From: Globalescrow.uk <admin@globalescrow.uk>'."\r\n";
 
 global $wpdb;
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ",$eid ));

 

$user_id=get_current_user_id();
$user_email = get_the_author_meta('user_email', $user_id);

  if($user_email == $escrow->buyer_email)
  {
 
  $party_email= $escrow->seller_email ;
	
  }
else
{
	 $party_email=$escrow->buyer_email;
 
}

// send email to party 
$message="Your partner ". $user_email ." has Disputed the escrow ";
 $subject="Your partner ". $user_email ." has Disputed the escrow ";
 
wp_mail($party_email,$subject,$message, $headers);

//send email to self

$message="You have successfully Disputed the escrow ";
 $subject="You have successfully Disputed the escrow ";
 
wp_mail($user_email ,$subject,$message, $headers);
  
   
   
}

function sendNotificationReleased($eid  )
{
 
	 
$headers[] = 'From: Globalescrow.uk <admin@globalescrow.uk>'."\r\n";
 
 global $wpdb;
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ",$eid ));

 

$user_id=get_current_user_id();
$user_email = get_the_author_meta('user_email', $user_id);

  if($user_email == $escrow->buyer_email)
  {
 
  $party_email= $escrow->seller_email ;
	
  }
else
{
	 $party_email=$escrow->buyer_email;
 
}

// send email to party 
$message="Your partner ". $user_email ." has Released the escrow ";
 $subject="Your partner ". $user_email ." has Released the escrow ";
 
wp_mail($party_email,$subject,$message, $headers);

//send email to self

$message="You have successfully Released the escrow ";
 $subject="You have successfully Released the escrow ";
 
wp_mail($user_email ,$subject,$message, $headers);
  
   
   
}

 
 
 
function sendNotificationCancelled($eid  )
{
 global $wpdb;
	 
$headers[] = 'From: Globalescrow.uk <admin@globalescrow.uk>'."\r\n";
 
 
$escrow = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}escrow_system WHERE   id=%s ",$eid ));

 echo "<pre>";
 
 $user_id=get_current_user_id();
$user_email = get_the_author_meta('user_email', $user_id);

  if($user_email == $escrow->buyer_email)
  {
 
  $party_email= $escrow->seller_email ;
	
  }
else
{
	 $party_email=$escrow->buyer_email;
 
}

// send email to party 
$message="Your partner ". $user_email ." has Cancelled the escrow ";
 $subject="Your partner ". $user_email ." has Cancelled the escrow ";
 
 
 echo $party_email;
 
wp_mail($party_email,$subject,$message, $headers);

//send email to self

$message="You have successfully Cancelled the escrow ";
 $subject="You have successfully Cancelled the escrow ";
 echo $user_email;
wp_mail($user_email ,$subject,$message, $headers);
  
   
   
}


