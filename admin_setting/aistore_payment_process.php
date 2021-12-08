 <div id="row ">
<div id="col-md-6"   >
    
    <?php
                if (isset($_POST['submit']) and $_POST['action'] == 'escrow_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }
                    
                    
                    

                    $eid =   sanitize_text_field($_REQUEST['ecsrow_id']);
                    
                    
                     $object_escrow = new AistoreEscrowSystem();
                     
                     
                       $escrow_admin_user_id = $object_escrow->get_escrow_admin_user_id();
                    
                      $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE id=%s ", $eid));
                      
                      
                       $aistore_escrow_currency = $escrow->currency;
                      $escrow_amount = $escrow->amount;
                      $escrow_fee = $escrow->escrow_fee;
                       $sender_email = $escrow->sender_email;
            $user = get_user_by('email', $sender_email);
            $sender_id = $user->ID;
                      $escrow_details = 'Admin Send Payment To User Account';
                      
                       $escrow_wallet = new AistoreWallet();
                       
                    $new_amount = $escrow_fee+$escrow_amount;
                    
            $escrow_wallet->aistore_debit($escrow_admin_user_id, $new_amount, $aistore_escrow_currency, $escrow_details);
            

            $escrow_wallet->aistore_credit($sender_id, $new_amount, $aistore_escrow_currency, $escrow_details); 
                    
                    
                    
                     $escrow_details = 'User Send Payment to Admin';
                    
                        $escrow_wallet->aistore_debit($sender_id, $new_amount, $aistore_escrow_currency, $escrow_details);

            $escrow_wallet->aistore_credit($escrow_admin_user_id, $new_amount, $aistore_escrow_currency, $escrow_details); 
                    
                    
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'paid'  WHERE id = '%d' ", $eid));
    
    
    
    

                }
                
                
                
                      if (isset($_POST['submit']) and $_POST['action'] == 'reject_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                           $eid =   sanitize_text_field($_REQUEST['reject_ecsrow_id']);
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'Rejected'  WHERE id = '%d' ", $eid));

                    
                }
                
                if (isset($_POST['submit']) and $_POST['action'] == 'remove_escrow_payment')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                           $eid =   sanitize_text_field($_REQUEST['ecsrow_id']);
                    $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'Pending'  WHERE id = '%d' ", $eid));

                    

                }
             
?>


<?php
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_system  order by id desc");

?>


  <h1> <?php _e('Escrow Payment', 'aistore') ?> </h1>
  
  <br>
  <p> <?php _e('<strong>Note : </strong>  To approve  or reject an escrow payment , in the escrow payment to click Approve button or Reject button', 'aistore') ?></p><br>
     
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    
  <table  id="example" class="display nowrap" style="width:100%">
         <thead>
     <tr>
         <th>Id</th>
      <th>Title</th>
    
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
        <th>Payment Status</th>
       <th>Date</th>
       <th>Action</th>
     </tr>
      </thead>
<tbody>
    <?php

        if ($results == null)
        {
            _e("No Escrow Found", 'aistore');

        }
        else
        {
            foreach ($results as $row):

?> 
      <tr>

		   <td> 	
		   
		   <?php echo esc_attr($row->id); ?></td>
		  
		   
		   <td> 		   <?php echo esc_attr($row->title); ?> </td>
		  
		   <!--<td> 		   <?php echo esc_attr($row->status); ?> </td>-->
		   
		   <td> 		   <?php echo esc_attr($row->amount) . " " . $row->currency; ?> </td>
		   <td> 		   <?php echo esc_attr($row->sender_email); ?> </td>
		   <td> 		   <?php echo esc_attr($row->receiver_email); ?> </td>
		      <td> 		   <?php echo esc_attr($row->payment_status); ?> </td>
		     <td> 		   <?php echo esc_attr($row->created_at); ?> </td>
       
                <td>

<?php

echo $row->payment_status;
if($row->payment_status=='process'){
    ?>
    <form method="POST" action="" name="escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
	<input type="hidden" name="ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Approve Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="escrow_payment" />
                </form>
             
                
                 <form method="POST" action="" name="reject_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="reject_ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Reject Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="reject_payment" />
                </form>
                   <?php
                   
    
} 
                   
                   if($row->payment_status=='paid'){
    ?>
                   
                 <form method="POST" action="" name="remove_escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
		<input type="hidden" name="ecsrow_id" value="<?php echo $row->id; ?>" />
<input 
 type="submit" name="submit" value="<?php _e('Remove Payment', 'aistore') ?>"/>
<input type="hidden" name="action" value="remove_escrow_payment" />
                </form>
              
           
    <?php
            
        } 
        
        ?>  
        
        </td>
        </tr>
        
        
        <?php 
endforeach;
?>


</tbody>
    <tfoot>
             <tr>
         <th>Id</th>
      <th>Title</th>
    
        <th>Amount</th>
      <th>Sender </th>
       <th>Receiver</th>  
        <th>Payment Status</th>
       <th>Date</th>
       <th>Action</th>
     </tr>
        </tfoot>
    </table>
    
    </div>
  
    </div>
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
	<?php
    }


