<?php
//   echo "hello";
  
 


add_action( 'user_register', 'my_registration', 10, 2  );

 
function my_registration( $user_id ) {
    
    echo "hello34355";
    echo $user_id;
    // get user data
    $user_info = get_userdata($user_id);
    // create md5 code to verify later
    $code = md5(time());
    echo $code;
    // make it into a code to send it to user via email
    $string = array('id'=>$user_id, 'code'=>$code);
    // create the activation code and activation status
    update_user_meta($user_id, 'account_activated', 0);
    update_user_meta($user_id, 'activation_code', $code);
    // create the url
    $url = get_site_url(). '/my-account/?act=' .base64_encode( serialize($string));
    // basically we will edit here to make this nicer
    $html = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';
    // send an email out to user
    wp_mail( $user_info->user_email, __('Email Subject','text-domain') , $html);
}

add_action( 'init', 'verify_user_code' );
function verify_user_code(){
    if(isset($_GET['act'])){
        $data = unserialize(base64_decode($_GET['act']));
        $code = get_user_meta($data['id'], 'activation_code', true);
        // verify whether the code given is the same as ours
        if($code == $data['code']){
            // update the user meta
            update_user_meta($data['id'], 'is_activated', 1);
            wc_add_notice( __( '<strong>Success:</strong> Your account has been activated! ', 'text-domain' )  );
        }
    }
}
?>