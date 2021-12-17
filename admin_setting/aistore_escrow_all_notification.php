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
  
	     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
  
   <h1> <?php _e('All Notification', 'aistore') ?> </h1>  <br>
     <?php
      
	global $wpdb;
           	

     	 $results = $wpdb->get_results($sql);
     	  if ($results == null)
        {
            _e("No Notification Found", 'aistore');

        }
        ?>
          <table  id="example" class="display nowrap" style="width:100%">
      
        <thead>
     <tr>
         <th>Id</th>
      <th>Email</th>
     <th> Message</th>
        <th>Date</th>
   
    
     </tr>
      </thead>
<tbody>
     <?php
 	foreach ($results as $row):
            
?> 
  
    <tr>
        <td> 	 
		   <?php echo $row->id; ?></td>
           <td> 	 
		   <?php echo $row->user_email; ?></td>
		   <td> <?php echo html_entity_decode($row->message); ?></td>
		     <td><?php echo $row->created_at; ?></td>

    </tr>
            
            </tbody>
    <?php
        endforeach;
    ?>
    
      <tfoot>
            <tr>
       <th>Id</th>
      <th>Email</th>
     <th> Message</th>
        <th>Date</th>
            </tr>
        </tfoot>
        </table>
     <br><br>
     
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