
				
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
       <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>




       <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<br>
       <div class="container">


		

       <?php  
       
       
global  $wpdb;


  


 $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}widthdrawal_requests");
     foreach($results as $row):
    
    
    ?>
 
          <div class="accordion" id="accordionExample">
              
  <div class="accordion-item">
    <h2 class="accordion-header" id="heading<?php echo $row->id ; ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo esc_attr($row->id) ; ?>" aria-expanded="true" aria-controls="collapse<?php echo esc_attr($row->id ); ?>">
        #<?php echo esc_attr($row->id) ; ?> :  <?php echo esc_attr($row->username) ; ?>
      </button>
    </h2>
    <div id="collapse<?php echo esc_attr($row->id) ; ?>" class="accordion-collapse collapse show" aria-labelledby="heading<?php echo esc_attr($row->id) ; ?>" data-bs-parent="#accordionExample">
      <div class="accordion-body">
      <table class="widefat fixed striped">
        
   
<table>
    
      <tr>
      <th scope="row"><?php _e( 'Username', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->username) ; ?></td>
      
    </tr>
    
      <tr>
      <th scope="row"><?php _e( 'Amount', 'aistore' ); ?> :    </th>
      <td>       <?php echo esc_attr($row->amount)." " ; ?></td>
      
    </tr>
    
     <tr>
      <th scope="row"><?php _e( 'Status', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->status) ; ?></td>
      
    </tr>
    
   
    
     <tr>
      <th scope="row"><?php _e( 'Date', 'aistore' ); ?> :</th>
      <td>   <?php echo esc_attr($row->created_at) ; ?></td>
      
    </tr>
    
    
    <tr>
        <td>
      
<?php
   
   $users = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}users WHERE user_email=%s ",$row->username));

$bank_account= esc_attr( get_the_author_meta( 'user_bank_detail', $users->ID ) );
$user_deposit_instructions= esc_attr( get_the_author_meta( 'user_deposit_instruction', $users->ID ) );


?>
<!-- Button trigger modal -->



<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#aistore_exampleModal" data-whatever1=" <?php echo esc_attr($row->id) ; ?>" 
data-name=" <?php echo esc_attr($row->username) ; ?>"
data-wbank=" <?php echo esc_attr($bank_account); ?>" 
data-bank_account_name=" <?php echo esc_attr($user_deposit_instructions); ?>"  
data-amount=" <?php echo esc_attr($row->amount) ; ?>"
data-status=" <?php echo esc_attr($row->status) ; ?>" 
data-status=" <?php echo esc_attr($row->status) ; ?>">
 
 <?php   _e( 'View', 'aistore' ); ?>

</button>


  </td>
  
  <td>


<?php
if(isset($_POST['submit']) and $_POST['action']=='approve_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);


$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}widthdrawal_requests
    SET status = '%s'  WHERE id = '%d'", 
   'Approved' , $withdrawal_id   ) );
   
   $widthdrawal = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}widthdrawal_requests WHERE id=%s ",$withdrawal_id));
   
   
   $to = $widthdrawal->username;
$subject ="Withdrawal Approved";




 $body="Hello, <br>
 
     <h2> withdraw approved  successfully for the withdraw ID ".$withdrawal_id." </h2>".
     
     "<br>Withdraw ID is: ".$withdrawal_id.
     "<br>Approved Withdraw system to :<br>";
    
  
  
  //$body.=__( 'Your Recevier Email'.$receiver_email, 'aistore' );
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );

 
}
else{
?>
 <form method="POST" action="" name="approve_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Approve', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="approve_withdrawal" />
</form>

<?php
}
?>

</td>

<td>

<?php
if(isset($_POST['submit']) and $_POST['action']=='reject_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);


$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}widthdrawal_requests
    SET status = '%s'  WHERE id = '%d'", 
   'Rejected' , $withdrawal_id   ) );
   
   
   
   $widthdrawal = $wpdb->get_row($wpdb->prepare( "SELECT * FROM {$wpdb->prefix}widthdrawal_requests WHERE id=%s ",$withdrawal_id));
   
   
   $to = $widthdrawal->username;
$subject ="Withdrawal Request Rejected";


	

 $body="Hello, <br>
 
     <h2>Your  withdrawal request is Rejected for the withdraw ID ".$withdrawal_id." </h2>".
     
     "<br>Withdraw ID is: ".$withdrawal_id.
     "<br>Rejected Withdraw system to :<br>";
    
  
  $headers = array('Content-Type: text/html; charset=UTF-8');
     wp_mail( $to, $subject, $body, $headers );
 
}
else{
?>
 <form method="POST" action="" name="reject_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Reject', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="reject_withdrawal" />
</form>

<?php
}
?>
</td>

<td>
<?php
if(isset($_POST['submit']) and $_POST['action']=='delete_withdrawal' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 




$withdrawal_id=sanitize_text_field($_REQUEST['withdrawal_id']);



$table =$wpdb->prefix.'widthdrawal_requests' ;
$wpdb->delete( $table, array( 'id' => $withdrawal_id ) );
 
}
else{
?>
 <form method="POST" action="" name="delete_withdrawal" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
  
  <input class="input" type="hidden" id="withdrawal_id" name="withdrawal_id" value="<?php echo esc_attr($row->id) ; ?> ">
<input 
 type="submit" class="btn btn-primary btn-sm" name="submit" value="<?php  _e( 'Delete', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="delete_withdrawal" />
</form>

<?php
}
?>

		   </td>
    </tr>

</table>
      </div>
    </div>
  </div>
    <?php endforeach; ?>
    
  </div>
  
  
     <!-- Modal -->
<div class="modal fade" id="aistore_exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php _e( 'Request Details #', 'aistore' );
        echo  "<span id='wid'> </span>"; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
 
       <table class="table table-sm">
 
  <tbody>
    <tr>
      <th scope="row"><?php _e( 'Username', 'aistore' ); ?></th>
      <td><?php echo "<span id='username'> </span>"; ?></td>
       
      
    </tr>
    <tr>
      <th scope="row"><?php _e( 'Amount', 'aistore' ); ?></th>
      <td><?php echo "<span id='amount'> </span>"." "; ?></td>
    
   
    </tr>
    
    <tr>
      <th scope="row"><?php _e( 'Status', 'aistore' ); ?></th>
      <td><?php echo  "<span id='status'> </span>"; ?></td>
      
    </tr>
    
  </tbody>
</table>

<hr>
<h4><?php _e( 'Bank account details', 'aistore' ); ?></h4>
 <table class="table table-sm">
 
  <tbody>
    <tr>
      <th scope="row"><?php _e( 'Bank Account Details', 'aistore' ); ?></th>
      <td><?php echo "<span id='wbank'> </span>"; ?></td>
       
      
    </tr>
    <tr>
      <th scope="row"><?php _e( 'Deposit Instructions', 'aistore' ); ?></th>
      <td><?php echo "<span id='bank_account_name'> </span>"; ?></td>
    
   
    </tr>
   
  </tbody>
</table>
      </div>
     
   
   
    </div>
  </div>
</div>



<script>


// alert("fsdf");

 $('#aistore_exampleModal').on('show.bs.modal', function (event) {
   //  alert("hello");
  var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.data('whatever1')
 // alert(recipient);
      var username = button.data('name')
      var amount=button.data('amount')
       var status=button.data('status')
            var wbank=button.data('wbank')
    var bank_account_name=button.data('bank_account_name')
  
  var modal = $(this)
  



 document.getElementById('wid').innerText=recipient;
 document.getElementById('username').innerText=username;
 document.getElementById('amount').innerText=amount;
 document.getElementById('status').innerText=status;
 document.getElementById('wbank').innerText=wbank;
 document.getElementById('bank_account_name').innerText=bank_account_name;

 
 
})
</script>



<?php
     
 


