<?php

global $wpdb;
        $page_id = get_option('details_escrow_page_id');

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system WHERE status = 'disputed'");

?>
    <h1> <?php _e('All disputed escrows', 'aistore') ?> </h1>
	
	     <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
  

    <?php
        if ($results == null)
        {

          //  _e("No Escrow Found", 'aistore');

        }
        else
        {
            ?>
              
  <table  id="example" class="display nowrap" style="width:100%">
      
        <thead>
     <tr>
         <th>Id</th>
      <th>Title</th>
     <th> Status</th>
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
  
    
     </tr>
      </thead>
<tbody>
      
<?php
            foreach ($results as $row):

                $url = admin_url('admin.php?page=disputed_escrow_details&eid=' . $row->id . '', 'https');

?>
      <tr>

		   
		   <td> 	 
		  <a href="<?php echo ($url); ?>">  <?php echo $row->id; ?></a></td>
		  
		   
		   <td> 		   <?php echo $row->title; ?> </td>
		  
		   <td> 		   <?php echo $row->status; ?> </td>
		  
		   
		   <td> 		   <?php echo $row->amount  . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo $row->sender_email; ?> </td>
		   <td> 		   <?php echo $row->receiver_email; ?> </td>
		  
              
            </tr>
            
            </tbody>
    <?php
            endforeach;
        }
?>

	

    </table>
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