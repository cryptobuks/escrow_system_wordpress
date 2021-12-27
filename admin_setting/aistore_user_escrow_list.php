 <?php
 
 global $wpdb;
              $page_id = get_option('details_escrow_page_id');
            if (isset($_REQUEST['id']))
        {
$id=sanitize_text_field($_REQUEST['id']);

//echo $id;
      //  $id = sanitize_text_field($_REQUEST['id']);

        $user_email = get_the_author_meta('user_email', $id);

      

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE (sender_email=%s or receiver_email=%s ) order by id desc", $user_email, $user_email));
        }  
        else{
   
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system");
}

?>
  <h1> <?php _e('Escrow List', 'aistore') ?> </h1>
   
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    

            <?php
            
  
        if ($results == null)
        {
           // _e("No Escrow Found", 'aistore');

        }
        else
        {
            
            ?>
<table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('Id', 'aistore'); ?></th>
        <th><?php _e('Title', 'aistore'); ?></th>
		    <th><?php _e('Status', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
		  <th><?php _e('Sender', 'aistore'); ?></th>
		  <th><?php _e('Receiver', 'aistore'); ?></th>
		  	  <th><?php _e('Date', 'aistore'); ?></th>
	  
            </tr>
            
        </thead>
        
        <tbody>
            
            <?php
             foreach ($results as $row):
                 
   $url = admin_url('admin.php?page=disputed_escrow_details&eid=' . $row->id . '', 'https');
   
  
    $user = get_user_by('email', $row->sender_email);
    
  //  print_r($user);
            $sender_id = $user->ID;
           // echo $sender_id;
    $user = get_user_by('email', $row->receiver_email);
            $receiver_id = $user->ID;
            
   $urlbyid = admin_url('admin.php?page=aistore_user_escrow_list&id=' . $sender_id . '', 'https');
   
    $urlbyidreciver = admin_url('admin.php?page=aistore_user_escrow_list&id=' . $receiver_id . '', 'https');
   
        
// https://www.blogentry.in/est/wp-admin/admin.php?page=aistore_user_escrow_list
?>
            <tr>
            	   <td> 	 
		  <a href="<?php echo esc_html($url); ?>">
		   
		   <?php echo esc_attr($row->id); ?> </a> </td>
		  
		   
		    <td> 	 
		  <a href="<?php echo esc_html($url); ?>">
		   
		   <?php echo esc_attr($row->title); ?> </a> </td>

		  
		   <td> 		   <?php echo esc_attr($row->status); ?> </td>
		   
		   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency; ?> </td>
		   
		  <td> <a href="<?php echo esc_html($urlbyid); ?>">
		   
		   <?php echo esc_attr($row->sender_email); ?> </a> </td>
		   
		   	  <td> <a href="<?php echo esc_html($urlbyidreciver); ?>">
		   
		   <?php echo esc_attr($row->receiver_email); ?> </a> </td>

		  
		     <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
		   </tr>
		   <?php
        
            endforeach;
        ?>
    
        </tbody>
        
    
        
        <tfoot>
            <tr>
                   <th><?php _e('Id', 'aistore'); ?></th>
        <th><?php _e('Title', 'aistore'); ?></th>
		    <th><?php _e('Status', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
		  <th><?php _e('Sender', 'aistore'); ?></th>
		  <th><?php _e('Receiver', 'aistore'); ?></th>
		  	  <th><?php _e('Date', 'aistore'); ?></th>
            </tr>
        </tfoot>
    </table>
        <?php } ?>
      <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
    
    <script>
    
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>
    