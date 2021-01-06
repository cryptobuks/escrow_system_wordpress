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
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'aistore_page_register_setting' ) );
        
    

	
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'My Settings', 
            'administrator', 
            'my-setting-admin', 
            array( $this, 'aistore_page_setting' )
        );
        
        
        
            
        add_menu_page('Escrow System', 'Escrow System', 'administrator', 'wallet_list');
	
 
 
	
add_submenu_page( 'wallet_list', 'Escrow List', 'Escrow List',
    'administrator', 'wallet_list', array( $this,'escrow_list'));
	
	
add_submenu_page( 'wallet_list', 'Disputed Escrow List', 'Disputes',
    'administrator', 'disputed_escrow_list', array( $this,'disputed_escrow_list'));
	
	
	add_submenu_page( 'wallet_list', 'Setting', 'Setting',
    'administrator', 'aistore_page_setting', array( $this,'aistore_page_setting'));
	
	
    }

    


// escrow list

function  escrow_list()
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

function disputed_escrow_list()
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

}

 function aistore_page_setting() {
	 
	  $pages = get_pages(); 
	
	   ?>
	  <div class="wrap">
	  
	  <div class="card">
	  
<h3><?php  _e( 'Escrow Setting', 'aistore' ) ?></h3>
 
	                      
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
	 
	 
</select></td>
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
	 
	 
		 
					  
					
 
</select></td>
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

		   
		   
		   
</select></td>
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
	 
	 
		 
					  
					
 
</select></td>
        </tr>  </table>
        
        	<hr/>
        	
        	
        <table class="form-table">
        
        <h3><?php  _e( 'Admin Escrow Setting', 'aistore' ) ?></h3>
        
		 <tr valign="top">
        <th scope="row"><?php  _e( 'Admin ID', 'aistore' ) ?></th>
        <td>
		<select name="escrow_user_id" >
		 
		 
		  <?php 
		  
		   
        $blogusers = get_users( [ 'role__in' => [ 'administrator' ] ] );
// Array of WP_User objects.

                    foreach($blogusers as $user){ 
                        
					
					if($user->ID==get_option('escrow_user_id'))
					{
		 echo '	<option selected value="'.$user->ID.'">'.$user->display_name .'</option>';
		 
		  } else {
                      
   echo '	<option  value="'.$user->ID.'">'.$user->display_name .'</option>';
		 
		

		}  
	 } ?> 
 
</select>
<p>Create an admin account and set its ID this will be used to hold payments</p>
</td>
        </tr>  
        
      
  
    </table>
    
    
    
    	<hr/>
        	
        	
        <table class="form-table">
        
        <h3><?php  _e( 'Escrow Fee Setting', 'aistore' ) ?></h3>
        
	 <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Create Fee', 'aistore' ) ?></th>
        <td><input type="number" name="escrow_create_fee" value="<?php echo esc_attr( get_option('escrow_create_fee') ); ?>" /></td>
        </tr>
        
      <tr valign="top">
        <th scope="row"><?php  _e( 'Escrow Accept Fee', 'aistore' ) ?></th>
        <td><input type="number" name="escrow_accept_fee" value="<?php echo esc_attr( get_option('escrow_accept_fee') ); ?>" /></td>
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
    $my_settings_page = new AistoreSettingsPage(); 


