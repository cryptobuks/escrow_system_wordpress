<?php

global $wpdb;
        
            if (isset($_REQUEST['id']))
        {
$id=sanitize_text_field($_REQUEST['id']);




        $user_email = get_the_author_meta('user_email', $id);
        
         $sql = "SELECT * FROM {$wpdb->prefix}escrow_notification   where user_email ='".$user_email."' order by id desc";
         
        //echo $sql;
        }  
        else{
   
    $sql = "SELECT * FROM {$wpdb->prefix}escrow_notification  order by id desc";

}

?>

  <br>
  
   <h1> <?php _e('All Notification', 'aistore') ?> </h1>  <br>
     <?php
      
	global $wpdb;
           	

     	 $results = $wpdb->get_results($sql);
     	  if ($results == null)
        {
            _e("No Notification Found", 'aistore');

        }
     
 	foreach ($results as $row):
            
?> 
  
  <div class="discussionmsg">
   
 <!--<a href="<?php echo $row->url; ?>">  </a> </p>-->
    <p> <?php echo ($row->user_email); ?></p>
  <p> <?php echo html_entity_decode($row->message); ?></p>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>
 
<hr>
    
    <?php
        endforeach;
    ?>
     <br><br>