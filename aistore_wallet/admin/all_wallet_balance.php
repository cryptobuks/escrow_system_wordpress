 <?php
 

?>

   
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    

            <?php
            
  $users = get_users( );
  $wallet = new AistoreWallet();
//print_r($users);
        if ($users === null)
        {
           // _e("No User Found", 'aistore');

        }
        else
        {
            
            
?>
    <h1> <?php _e('User List', 'aistore') ?> </h1>
<table id="example" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
		  
	  
            </tr>
            
        </thead>
        
        <tbody>
            <?php
            
  
             foreach ($users as $row):
               //  print_r($users);
               
               $currencies = $wallet->aistore_wallet_currency();
    
            foreach ($currencies as $c)
            {
     $currency=$c->currency;
 
    
            
  $balance = $wallet->aistore_balance($row->ID, $currency);
  
  if($balance>0){
?>
            <tr>
            	   <td>  <?php echo esc_attr($row->ID); ?></td>
		  		   <td> 		   <?php echo esc_attr($row->user_login); ?> </td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->user_email); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($balance) ." ".$currency; ?> </td>
		   
		 
		   </tr>
		   <?php
  }
  }
  
        
            endforeach;
        ?>
    
        </tbody>
        
      
        
        <tfoot>
            <tr>
         <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
            </tr>
        </tfoot>
    </table>
      <?php } ?>
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
    