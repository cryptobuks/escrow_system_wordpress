<?php 
  $wallet = new AistoreWallet();
 $user_id = get_current_user_id();
                     
 $escrow_user_id = get_option( 'escrow_user_id');
 
 
     $currencies = $wallet->aistore_wallet_currency();
    
            foreach ($currencies as $c)
            {
     $currency=$c->currency;
$balance = $wallet->aistore_balance($user_id, $currency);
$escrow_user_id_balance = $wallet->aistore_balance($escrow_user_id, $currency);

 ?>
 
 <!--<h3>Balance : <?php echo $balance." ".$currency; ?>  </h3>-->
 <!-- <h3>Escrow Admin Balance : <?php echo $escrow_user_id_balance." ".$currency; ?> </h3><br>-->
 <?php

        
            }        

            


            ?>
            
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    
         
   
<?php
$users = get_users( );

//print_r($users);
        if ($users === null)
        {
           // _e("No User Found", 'aistore');

        }
        else
        {
            
            
?>
    <h1> <?php _e('User List', 'aistore') ?> </h1>
<table id="example5" class="display nowrap" style="width:100%">
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
    
    
   
     <?php
      
 global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system order by id desc limit 5");
      if ($results == null)
        {
            // _e("No Escrow Found", 'aistore');

        }
        else
        {
    ?>
      <h1> <?php _e('Recent 5 Escrow', 'aistore') ?> </h1>
    <table id="example1" class="display nowrap" style="width:100%">
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

?>
            <tr>
            	   <td> 	 
		  <a href="<?php echo esc_html($url); ?>">
		   
		   <?php echo esc_attr($row->id); ?> </a> </td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->title); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->status); ?> </td>
		   
		   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo esc_attr($row->sender_email); ?> </td>
		   <td> 		   <?php echo esc_attr($row->receiver_email); ?> </td>
		     <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
		   </tr>
		   <?php
            endforeach;
        ?>
    
        </tbody>
        
        <?php ?>
        
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
    
    <br><br>
    
   
     <?php
      
	global $wpdb;
           	
           	        $escrow_admin_user_id = get_option('escrow_user_id');
           	        
     	$sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE {$wpdb->prefix}users.ID= ".$escrow_admin_user_id." order by {$wpdb->prefix}aistore_wallet_transactions.transaction_id desc limit 15" ;
     	
     //	echo $sql;
     	
     	 $results = $wpdb->get_results($sql);
      if ($results == null)
        {
            // _e("No transactions Found", 'aistore');

        }
        else
        {
    ?>
    <h1> <?php _e('Admin Top 15 Transaction', 'aistore') ?> </h1>
<table id="example2" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                   <th><?php _e('ID', 'aistore'); ?></th>
      
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
		  
	    <th><?php _e('Amount', 'aistore'); ?></th> 
	    <th><?php _e('Type', 'aistore'); ?></th> 
	    <th><?php _e('Description', 'aistore'); ?></th> 
	      <th><?php _e('Date', 'aistore'); ?></th> 
            </tr>
            
        </thead>
        
        <tbody>
            <?php
            
  
       
             foreach ($results as $row):
             
?>
            <tr>
            	   <td>  <?php echo esc_attr($row->transaction_id); ?></td>
	
		  
		   
		   <td> 		   <?php echo esc_attr($row->user_email); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->balance); ?> </td>
		   
		   <td> 		   <?php echo esc_attr($row->amount." ".$row->currency); ?> </td>
		   
		  <td> 		   <?php echo esc_attr($row->type); ?> </td>
		  
		   <td> 		   <?php echo esc_attr($row->description); ?> </td>
		   
		   	   <td> 		   <?php echo esc_attr($row->date); ?> </td>
		   </tr>
		   <?php
            endforeach;
        ?>
    
        </tbody>
     
        
        <tfoot>
            <tr>
         <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Username', 'aistore'); ?></th>
		    <th><?php _e('Email', 'aistore'); ?></th>
          <th><?php _e('Balance', 'aistore'); ?></th> 
          	  
	    <th><?php _e('Amount', 'aistore'); ?></th> 
	    <th><?php _e('Type', 'aistore'); ?></th> 
	    <th><?php _e('Description', 'aistore'); ?></th> 
            </tr>
        </tfoot>
    </table>
    
    <?php } ?>
    
      
    <br><br>
    
  
     <?php
      
	global $wpdb;
           	
 $escrow_admin_user_id = get_option('escrow_user_id');
   $user_email = get_the_author_meta('user_email', $escrow_admin_user_id);
   
 $sql = "SELECT * FROM {$wpdb->prefix}escrow_notification  limit 15";
     	
     	 $results = $wpdb->get_results($sql);
     

 	  if ($results == null)
        {
            // _e("No Notification Found", 'aistore');

        }
        ?>
         <h1> <?php _e('Top 15 Notification', 'aistore') ?> </h1>  <br>
          <table  id="example6" class="display nowrap" style="width:100%">
      
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
           <td> <?php echo $row->user_email; ?></td>
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
    $('#example5').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

  $(document).ready(function() {
    $('#example1').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );


  $(document).ready(function() {
    $('#example2').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
 $(document).ready(function() {
    $('#example6').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>