


<?php


 add_action('wp_dashboard_setup', array('My_Dashboard_Widget','aistore_escrow_widget'));
 
 class My_Dashboard_Widget {
  /**
     * The id of this widget.
     */
    const wid = 'my_escrow_list';
    
    /**
     * Hook to wp_dashboard_setup to add the widget.
     */

public static function aistore_escrow_widget() {

global $wp_meta_boxes;
 

wp_add_dashboard_widget(
            self::wid,                                  //A unique slug/ID
            __( 'Escrow List', 'nouveau' ),//Visible name for the widget
            array('My_Dashboard_Widget','aistore_escrow_page')      //Callback for the main widget content
            
        );

}

public static function aistore_escrow_page() {
	
global  $wpdb;

$page_id=get_option('details_escrow_page_id'); 

 $results = $wpdb->get_results( 
   $wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system  WHERE status <> %s order by id desc limit 5 ",  'closed' ) 
                 );

 
 
    
    if($results==null)
	{
	    
	      _e( "<br><center><h3>No escrow to show</h3></center>", 'aistore' );
	
	}
	else{
    foreach($results as $row):
      
	 $url  =  esc_url( add_query_arg( array(
    'page_id' => $page_id,
    'eid' => $row->id,
), home_url() ) ); 
    ?> 
      
		  	<a href="<?php echo $url ; ?>" >	
		   
		   <?php echo $row->id ; ?> </a> 
		  
		   
		   		 <b>  <?php echo  ( $row->title) ; ?> </b><br>
		  
		 	   <?php echo $row->status ; ?> <br>
		   
			  Amount:  <?php echo $row->amount ; ?>  <?php echo esc_attr( get_option('coin_name') ); ?> <br>
		  
		   		   <?php echo $row->receiver_email ; ?> <br>
		  
                
               <hr/>
                
                
               
            
    <?php endforeach;
	}
	 
	

}

}



?>

