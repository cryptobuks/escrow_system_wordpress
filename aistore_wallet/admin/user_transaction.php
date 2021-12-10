  <?php
  
  ?>
  

      
<h3><?php  _e( 'User Transactions', 'aistore' ) ?></h3>



<?php
if(isset($_POST['submit']) and $_POST['action']=='user_transaction' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify.', 'aistore' );

   exit;
}

 


   $user_id=sanitize_text_field($_POST['user_id']);
 //  echo $user_id;
   if ($user_id==0 ){
return _e( 'Please select an user.', 'aistore' );
}

 $wallet = new AistoreWallet();
        $results = $wallet->aistore_wallet_transaction_history($user_id);

?></div>

<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('Transactions List Not Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.0.1/css/buttons.dataTables.min.css">
    
  <table  id="example" class="display nowrap" style="width:100%">
         <thead>
        <tr>
      
    <th><?php _e('ID', 'aistore'); ?></th>
        <th><?php _e('Type', 'aistore'); ?></th>
         <th><?php _e('Balance', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
 
		  <th><?php _e('Currency', 'aistore'); ?></th>
		   <th><?php _e('Description', 'aistore'); ?></th> 
		    <th><?php _e('Date', 'aistore'); ?></th> 
		    

		 
</tr>
  </thead>
  <tbody>
    <?php
            foreach ($results as $row):

?>    <tr>
          
		   <td>   <?php echo $row->transaction_id; ?> </td>
  <td> 	   <?php echo $row->type; ?> </td>
    <td> 	
 
   <?php echo $row->balance ?>
		  </td>
		   
		  	   <td> 		   <?php echo $row->amount ?>  </td>
		  
		    <td> 		   <?php echo $row->currency; ?> </td>
		     <td> 		   <?php echo $row->description; ?> </td>
 <td> 		   <?php echo $row->date; ?> </td>
            </tr>
    <?php
            endforeach;

        } ?>

</tbody>
    </table>
	
	
	
	
	
<?php
 
 
}  
// else{
    
   
?>

 <form method="POST" action="" name="user_transaction" enctype="multipart/form-data"> 
    <?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
    

 <table class="form-table">
      	 <tr valign="top">  <th scope="row">
  <label><?php _e( 'Users:', 'aistore' ) ;?></label></th>
  <td>
	<select name="user_id" >
		 
		 
		  <option selected value="0">Selected User</option>
		  <?php
        $blogusers = get_users();

        foreach ($blogusers as $user)
        {
                echo '	<option  value="' . $user->ID . '">' . $user->display_name . '</option>';
        } ?> 
 
</select></td></tr><br><br>

<tr><td>
<input class="input" type="submit" name="submit" value="<?php  _e( 'Submit', 'aistore' ) ?>"/>
<input type="hidden" name="action"  value="user_transaction"/></td>
</tr>
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
