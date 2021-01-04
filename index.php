<?php
/*
  Plugin Name: Saksh WP SMTP
  Version: 4.1.1
  Plugin URI: #
  Author: susheelhbti
  Author URI: http://www.aistore2030.com/
  Description: Integrate wordpress to your mandrill , sendgrid , getresponse, email-marketing247 SMTP Server, Amazon SES or any SMTP Server.
 */


function my_plugin_load_plugin_textdomain() {
  
    
    load_plugin_textdomain( 'aistore', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'my_plugin_load_plugin_textdomain' );


function my_scripts_method() {
  
    wp_enqueue_script( 'aistore', plugins_url( '/js/custom.js' , __FILE__ ), array( 'jquery' ) );
}


add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

  include_once dirname( __FILE__) . '/setting.php';

 
 
 	if ( ! class_exists( 'EscrowSystem' ) ) {
 	    include_once dirname( __FILE__) . '/EscrowSystem.class.php';
 	}


 


add_shortcode( 'aistore_escrow_system', array( 'EscrowSystem', 'aistore_escrow_system' ) );

add_shortcode( 'aistore_escrow_list', array( 'EscrowSystem', 'aistore_escrow_list' ) );

add_shortcode( 'aistore_escrow_detail', array( 'EscrowSystem', 'aistore_escrow_detail' ) );

 
add_shortcode( 'escrow_syetem_part2', array( 'EscrowSystem', 'escrow_syetem_part2' ) );


