<?php
if (!defined('ABSPATH'))
{
    exit; // Exit if accessed directly.
    
}

class AistoreEscrowSystem
{

    // get escrow feecccc
    public function get_escrow_admin_user_id()
    {
        $escrow_admin_user_id = get_option('escrow_user_id');
        return $escrow_admin_user_id;

    }

    public function get_escrow_currency()
    {
        $aistore_escrow_currency = get_option('aistore_escrow_currency');
        return $aistore_escrow_currency;

    }

    public function create_escrow_fee($amount)
    {

        $escrow_create_fee = get_option('escrow_create_fee');

        $escrow_fee = ($escrow_create_fee / 100) * $amount;
        return $escrow_fee;

    }

    public function accept_escrow_fee($amount)
    {

        $escrow_accept_fee = get_option('escrow_accept_fee');

        $escrow_fee = ($escrow_accept_fee / 100) * $amount;
        return $escrow_fee;
    }

    //it take parameters and create escrow
    

    public function add_escrow($amount, $user_id, $receiver_email, $title, $term_condition ,$escrow_currency)
    {

        // step 1
        $ar = array();

       
        
        $object_escrow = new AistoreEscrowSystem();
        
        $aistore_escrow_currency = $object_escrow->get_escrow_currency();
        
    
    
        
        $escrow_fee = $object_escrow->create_escrow_fee($amount);  // issue here
        


         // insert currency also 
         
         

        global $wpdb;

      $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email,term_condition,escrow_fee ,currency ) VALUES ( %s, %d, %s, %s ,%s ,%s,%s)", array(
                    $title,
                    $amount,
                    $receiver_email,
                    $sender_email,
                    $term_condition,
                    $escrow_fee,
                    $escrow_currency
                )));


        $eid = $wpdb->insert_id;

      
      


        sendNotificationCreated($eid);

        $ar['Error'] = true;
        $ar['eid'] = $eid;

        $ar['Messsage'] = 'Escrow created successfully';

        return $ar;
    }

    // create escrow System
    public static function aistore_escrow_system()
    {
        
         if (!is_user_logged_in())
        {
            return "<div class='no-login'>Kindly login and then visit this page </div>";
        }
        
        
        

        $object_escrow = new AistoreEscrowSystem();
        $aistore_escrow_currency = $object_escrow->get_escrow_currency();
        
        
        $escrow_admin_user_id = $object_escrow->get_escrow_admin_user_id();   // change variable name  


       

        echo " <div>";

        $wallet = new AistoreWallet();
        
        $user_id = get_current_user_id();
        

        if (isset($_POST['submit']) and $_POST['action'] == 'escrow_system')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');
                exit;
            }

            $title = sanitize_text_field($_REQUEST['title']);
            $amount = sanitize_text_field($_REQUEST['amount']);
            
            $receiver_email = sanitize_email($_REQUEST['receiver_email']);
            
            $term_condition = sanitize_text_field(htmlentities($_REQUEST['term_condition']));
            
           
        $escrow_currency = sanitize_text_field($_REQUEST['aistore_escrow_currency']);
       
            $escrow_fee = $object_escrow->create_escrow_fee($amount);
            
            
            
            
            $sender_email = get_the_author_meta('user_email', get_current_user_id());

            

                global $wpdb;
                
                
                // add currency also
                
 

           
// check if user have uploaded file or not  then do this


                $fileType = $_FILES['file']['type'];
                
                
                if ($fileType == "application/pdf")
                {
                    
                                   
$wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_system ( title, amount, receiver_email,sender_email,term_condition,escrow_fee ,currency ) VALUES ( %s, %d, %s, %s ,%s ,%s,%s)", array(
                    $title,
                    $amount,
                    $receiver_email,
                    $sender_email,
                    $term_condition,
                    $escrow_fee,
                    $escrow_currency
                )));


             
                $eid = $wpdb->insert_id;
                    
                    $upload_dir = wp_upload_dir();
                    
                    
                    if (!empty($upload_dir['basedir']))
                    {

                        $user_dirname = $upload_dir['basedir'] . '/documents/' . $eid;
                        if (!file_exists($user_dirname))
                        {
                            wp_mkdir_p($user_dirname);
                        }

                        $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);
                        move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                        $image = $upload_dir['baseurl'] . '/documents/' . $eid . '/' . $filename;

                        // save into database  $image
                        

                        $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name) VALUES ( %d,%s,%d,%s)", array(
                            $eid,
                            $image,
                            $user_id,
                            $filename
                        )));
                        
                        
                $details_escrow_page_id_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('details_escrow_page_id') ,
                    'eid' => $eid,
                ) , home_url()));


                sendNotificationCreated($eid);
?>
<br>

<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($details_escrow_page_id_url); ?>" />
<?php
                    }
}
                    else
                    {
?>
            <p> <?php _e('Note : We accept only pdf file', 'aistore') ?></p><?php
                    }

                

              
          
          

?>









<?php 

        }
        else
        {
?>
    
    <form method="POST" action="" name="escrow_system" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>

                
                 
<label for="title"><?php _e('Title', 'aistore'); ?></label><br>
  <input class="input" type="text" id="title" name="title" required><br>

  <label for="title"><?php _e('Currency', 'aistore'); ?></label><br>
  <?php 
   global $wpdb;
 $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_currency  order by id desc"
);
?>
       <select name="aistore_escrow_currency" id="aistore_escrow_currency" >
                <?php

        foreach ($results as $c)
        {

           
                echo '	<option  value="' . $c->symbol . '">' . $c->currency . '</option>';


            

        }
?>
           
  
</select><br>
  <?php


?>

  <label for="amount"><?php _e('Amount', 'aistore'); ?></label><br>
  <input class="input" type="number" id="amount" name="amount" min="1"  required><br>
 
   <input class="input" type="hidden" id="escrow_create_fee" name="escrow_create_fee" value= "<?php echo get_option('escrow_create_fee'); ?>">
    <div class="feeblock hide" >
  <?php
  
  

?>
<br>
      <?php _e('Amount', 'aistore'); ?> :
      <b id="escrow_amount"></b>/- <span id="escrow_currency"></span><br>
 
  <?php _e('Escrow Fee', 'aistore'); ?>
  
  :  <b id="escrow_fee" ></b>/- <span id="escrow_currency"></span> (<?php echo get_option('escrow_create_fee'); ?> %)<br>
  
    
     
   <?php _e('Total Escrow Amount', 'aistore'); ?> :   <b id="total"></b>/- <span id="escrow_currency"></span>
   
   
   
  
  
  </div>
  <br>
  
<label for="receiver_email"><?php _e('Receiver Email', 'aistore'); ?>:</label><br>
  <input class="input" type="email" id="receiver_email" name="receiver_email" required><br>
  
   <label for="term_condition"> <?php _e('Term And Condition', 'aistore') ?></label><br>
   
   



  
  <?php
            $content = '';
            $editor_id = 'term_condition';

            $settings = array(
                'tinymce' => array(
                    'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright   ',
                    'toolbar2' => '',
                    'toolbar3' => ''

                ) ,
                'textarea_rows' => 1,
                'teeny' => true,
                'quicktags' => false,
                'media_buttons' => false
            );

            wp_editor($content, $editor_id, $settings);
?>
  



<br><br>

	<label for="documents"><?php _e('Documents', 'aistore') ?>: </label>
     <input type="file" name="file" accept="application/pdf"   /><br>
     <div><p> <?php _e('Note : We accept only pdf file and
	You can upload many pdf file then go to next escrow details page.', 'aistore') ?></p></div>
<input 
 type="submit" class="btn" name="submit" value="<?php _e('Create Escrow', 'aistore') ?>"/>
<input type="hidden" name="action" value="escrow_system" />
</form> 
<?php
        }

?>
</div>
<?php

    }

    // Escrow List
    public static function aistore_escrow_list()
    {
        if (!is_user_logged_in())
        {
            return "<div class='loginerror'>Kindly login and then visit this page</div>";
        }

        $object_escrow_currency = new AistoreEscrowSystem();
        $aistore_escrow_currency = $object_escrow_currency->get_escrow_currency();

        $current_user_email_id = get_the_author_meta('user_email', get_current_user_id());

        global $wpdb;

        $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email=%s or sender_email=%s order by id desc ", $current_user_email_id, $current_user_email_id)
);

?>
<h3><u><?php _e('Top  Escrow', 'aistore'); ?></u> </h3>
<?php

        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('No Escrow Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table">
     
        <tr>
      
    <th><?php _e('Id', 'aistore'); ?></th>
        <th><?php _e('Title', 'aistore'); ?></th>
         <th><?php _e('Role', 'aistore'); ?></th>
          <th><?php _e('Amount', 'aistore'); ?></th> 
		  <th><?php _e('Sender', 'aistore'); ?></th>
		  <th><?php _e('Receiver', 'aistore'); ?></th>
		    <th><?php _e('Payment Status', 'aistore'); ?></th>
		 	    <th><?php _e('Status', 'aistore'); ?></th>
</tr>

    <?php

            foreach ($results as $row):

                $details_escrow_page_id_url = esc_url(add_query_arg(array(
                    'page_id' => get_option('details_escrow_page_id') ,
                    'eid' => $row->id,
                ) , home_url()));

?>
 
 
      
    
      <tr>
           
		 
		   
		   
		   <td> 	<a href="<?php echo $details_escrow_page_id_url; ?>" >

		   <?php echo $row->id; ?> </a> </td>
  <td> 		   <?php echo $row->title; ?> </td>
    <td> 	

  <?php

                if ($row->sender_email == $current_user_email_id)
                {
                    $role = "Sender";
                    $email = $row->receiver_email;
                }
                else
                {
                    $role = "Receiver";
                    $email = $row->sender_email;
                }
                echo $role;

?>

		  </td>
		   
		  	   <td> 		   <?php echo $row->amount . " " . $row->currency ?> </td>
		   <td> 		   <?php echo $row->sender_email; ?> </td>
		   <td> 		   <?php echo $row->receiver_email; ?> </td>
		    <td> 		   <?php echo $row->payment_status; ?> </td>
   <td> 		   <?php echo $row->status; ?> </td>
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
	
	
	
	
	

    <?php
        return ob_get_clean();

    }

    // Escrow Details
    public static function aistore_escrow_detail()
    {
        if (!is_user_logged_in())
        {
            return;
        }
        
        
        
        if (!sanitize_text_field($_REQUEST['eid']))
        {

            $add_escrow_page_url = esc_url(add_query_arg(array(
                'page_id' => get_option('add_escrow_page_id') ,
            ) , home_url()));

?>
    
   
<meta http-equiv="refresh" content="0; URL=<?php echo esc_html($add_escrow_page_url); ?>" /> 
  
 <?php
        }

        $eid = sanitize_text_field($_REQUEST['eid']);




  if (isset($_POST['submit']) and $_POST['action'] == 'disputed')
        {
            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }
            
            
            
            
         if($escrow->payment_status <> "paid")  return "";  
         
          if ($escrow->status == "closed") return "";

        else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";

        else

        if ($escrow->status == "disputed") return "";

        else

        if ($escrow->status == "pending") return "";
        
        
         
                

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE id = '%d' and payment_status='paid'", 'disputed', $eid));

?>
<div>
<strong> <?php _e('Disputed Successfully', 'aistore') ?></strong></div>
<?php
            sendNotificationDisputed($eid);

        }
        
        
        
        
        
        
        
        
        
        

    $object_escrow = new AistoreEscrowSystem();
        
      
        
        $escrow_admin_user_id  = $object_escrow->get_escrow_admin_user_id();

        $user_id = get_current_user_id();
        
        
        $email_id = get_the_author_meta('user_email', $user_id);
        
        
        
               global $wpdb;

     

        ob_start();

        if (isset($_POST['submit']) and $_POST['action'] == 'accepted')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }
            
            
            




 $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE receiver_email = %s  and id=%s ", $email_id, $eid));

        $aistore_escrow_currency = $escrow->currency;
        $user_email = get_the_author_meta('user_email', get_current_user_id());
        
     
        
    if($escrow->payment_status <> "paid")  return "";  

   if ($escrow->sender_email == $user_email) return "";
   
   
 
        if ($escrow->status == "closed") return "";

        else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";

        else

        if ($escrow->status == "disputed") return "";

        else

        if ($escrow->status == "accepted") return "";
      
 




            $amount = $escrow->amount;
            
            

            $escrow_fee = $object_escrow->accept_escrow_fee($amount);




// fee will be debited from both party once user accept the escrow


            $escrow_details = 'Payment transaction for the accept escrow with escrow id # ' . $eid;
            

            $escrow_wallet = new AistoreWallet();
            
            $escrow_wallet->aistore_debit($user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details);
            
            $escrow_wallet->aistore_credit($escrow_admin_user_id, $escrow_fee, $aistore_escrow_currency, $escrow_details);  // change variable name 
            


            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = '%s'  WHERE  payment_status='paid' and  receiver_email = %s  and id = '%d'", 'accepted', $email_id, $eid));




?>
<div>
    
<strong> <?php _e('Accepted Successfully', 'aistore') ?></strong></div>
<?php
            
            
            
            sendNotificationAccepted($eid);
            
            
        }

        if (isset($_POST['submit']) and $_POST['action'] == 'released')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

 
    
    
         

 $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system WHERE payment_status='paid' and sender_email = %s  and id=%s ", $email_id, $eid));

      
         
         $aistore_escrow_currency = $escrow->currency;
            $escrow_amount = $escrow->amount;

            $escrow_reciever_email_id = $escrow->receiver_email;
            
            $escrow_user = get_user_by('email', $escrow_reciever_email_id);
            
            $escrow_user_id= $escrow_user->ID;  // change varibale name 
            

            $escrow_details = 'Payment transaction for the release escrow with escrow id # ' . $eid;


            $escrow_wallet = new AistoreWallet();
            
            
            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);
            
            
            $escrow_wallet->aistore_credit($escrow_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);
            
            
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = 'released'  WHERE  payment_status='paid' and  sender_email =%s and id = %d ", $email_id, $eid));
    
    




?>
<div>
<strong> <?php _e('Released Successfully', 'aistore') ?></strong></div>
<?php
            sendNotificationReleased($eid);
        }
        
        
        
        
        

        // Sender Create escrow  to excute cancel button
        // Receiver  accept or cancel escrow
        if (isset($_POST['submit']) and $_POST['action'] == 'cancelled')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify', 'aistore');

            }

            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}escrow_system
    SET status = 'cancelled'  WHERE (  receiver_email = %s   or  sender_email = %s    )  and  id =  %d " , $email_id, $email_id, $eid));
    
    
     


 $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system  WHERE (  receiver_email = %s   or  sender_email = %s    )  and  id =  %d " , $email_id, $email_id, $eid));

      
      
      
        
    
    if($escrow->payment_status=="paid")
    {
        
            $escrow_amount = $escrow->amount;

            $sender_escrow_fee = $escrow->escrow_fee;
            
            $sender_email = $escrow->sender_email;
            
            $user = get_user_by('email', $sender_email);
            
            $sender_id = $user->ID;
            
               $aistore_escrow_currency = $escrow->currency;
        $escrow_details = 'Payment transaction for the cancelled escrow with escrow id # ' . $eid;

            $escrow_wallet = new AistoreWallet();
            
            $escrow_wallet->aistore_debit($escrow_admin_user_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);
            
            $escrow_wallet->aistore_credit($sender_id, $escrow_amount, $aistore_escrow_currency, $escrow_details);


            $cancel_escrow_fee = get_option('cancel_escrow_fee');

            if ($cancel_escrow_fee == 'yes')
            {
                $escrow_wallet->aistore_debit($escrow_admin_user_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details);
                
                
                $escrow_wallet->aistore_credit($sender_id, $sender_escrow_fee, $aistore_escrow_currency, $escrow_details);

            }
            
    }       
    
         
         
         


   
?>
<div>
<strong><?php _e('Cancelled Successfully', 'aistore') ?></strong></div>

<?php
            sendNotificationCancelled($eid);
        }

       
       
       
 $escrow = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_system  WHERE (  receiver_email = %s   or  sender_email = %s    )  and  id =  %d " , $email_id, $email_id, $eid));





?>
	  <div>
	      <div class="alert alert-success" role="alert">
 <strong>  Status   <?php echo $escrow->status; ?></strong>
  </div>
	  
	  
	    <div class="alert alert-success" role="alert">
 <strong>Payment Status   <?php echo $escrow->payment_status; ?></strong>
  </div>
  
	      <?php
       
  if($escrow->payment_status=="Pending")
  {
      ?>
<div>
  <p>Don't ship the product.</p>
  </div><br>
  
  
  <?php 
  	$user_email = get_the_author_meta( 'user_email', get_current_user_id() );
  	 if($escrow->sender_email == $user_email)
  {
      ?>
  <table>
    <tr><td>Bank Account Name :</td><td><?php echo esc_attr( get_option('bank_account_name') ); ?></td></tr>
    <tr><td>Bank Account Number :</td><td><?php echo esc_attr( get_option('bank_account') ); ?></td></tr>
    <tr><td>Name Of Bank :</td><td><?php echo esc_attr( get_option('name_of_bank') ); ?></td></tr>
    <tr><td>IFSC Code :</td><td><?php echo esc_attr( get_option('ifsc_code') ); ?></td></tr>


  <tr><td colspan="2">
      <?php
       global $wpdb;   
  
 
$user_id=get_current_user_id();

if(isset($_POST['submit']) and $_POST['action']=='escrow_payment' )
{

if ( ! isset( $_POST['aistore_nonce'] ) 
    || ! wp_verify_nonce( $_POST['aistore_nonce'], 'aistore_nonce_action' ) 
) {
   return  _e( 'Sorry, your nonce did not verify', 'aistore' );
   exit;
} 

$eid=  $escrow->id;
$wpdb->query( $wpdb->prepare( "UPDATE {$wpdb->prefix}escrow_system
    SET payment_status = 'process'  WHERE id = '%d' ",  $eid   ) );
    
    echo $escrow->payment_status;

}
else{
        ?>
    <form method="POST" action="" name="escrow_payment" enctype="multipart/form-data"> 

<?php wp_nonce_field( 'aistore_nonce_action', 'aistore_nonce' ); ?>
	
<input 
 type="submit" name="submit" value="<?php  _e( 'Make Payment', 'aistore' ) ?>"/>
<input type="hidden" name="action" value="escrow_payment" />
                </form><?php }?>
  </td></tr>
</table><br>


  <?php 
  }
  
  
  }
  
        
  echo "<h1>#" . $escrow->id . " " . $escrow->title . "</h1><br>";
        printf(__("Term Condition : %s", 'aistore') , html_entity_decode($escrow->term_condition) . "<br>");
        printf(__("Sender :  %s", 'aistore') , $escrow->sender_email . "<br>");
        printf(__("Receiver : %s", 'aistore') , $escrow->receiver_email . "<br>");
        printf(__("Status : %s", 'aistore') , $escrow->status . "<br><br>");

        $object = new AistoreEscrowSystem();

        $object->accept_escrow_btn($escrow);

        $object->cancel_escrow_btn($escrow);

        $object->release_escrow_btn($escrow);

        $object->dispute_escrow_btn($escrow);

        $object->escrow_file_uploads($escrow);

        $object->escrow_discussion($escrow);

?>
</div>

<?php
        return ob_get_clean();
    }

    // Escrow  file uploads
    private function escrow_file_uploads($escrow)
    {

        $eid = $escrow->id;

        global $wpdb;

        $escrow_documents = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_documents WHERE eid=%d", $eid));

?> 
  
    <table class="table">
    <?php
        foreach ($escrow_documents as $row):

?> 
	
	<div class="document_list">
   


  <p><a href="<?php echo $row->documents; ?>" target="_blank">
	       <b><?php echo $row->documents_name; ?></b></a></p>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>

<hr>
    
    <?php
        endforeach;

?>
    </table>
<br>
	   <div>  
    


	<label for="documents"> <?php _e('Documents', 'aistore'); ?> : </label>
<form  method="post"  action="<?php echo admin_url('admin-ajax.php') . '?action=custom_action&eid=' . $eid; ?>" class="dropzone" id="dropzone">
    <?php
        wp_nonce_field('aistore_nonce_action', 'aistore_nonce');
?>
  <div class="fallback">
    <input id="file" name="file" type="file"  accept="application/pdf" multiple   />
    <input type="hidden" name="action" value="custom_action" type="submit"  />
  </div>

</form>
<p> We accept only PDF files.</p>

       
     
     </div>
     <br>
     
     <?php

    }

    // Escrow Discussion
    

    private function escrow_discussion($escrow)
    {
        $message_page_url = get_option('escrow_message_page');

        if ($message_page_url == 'no')
        {
            return "";

        }

        $user_login = get_the_author_meta('user_login', get_current_user_id());

?>

     
	 
<div>
    <br>
<form class="wordpress-ajax-form" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>"  >
<?php
        wp_nonce_field('aistore_nonce_action', 'aistore_nonce');
?>
   <label for="message">   <?php _e('Message', 'aistore') ?></label><br>



  
  <?php
        $content = '';
        $editor_id = 'message';

        $settings = array(
            'tinymce' => array(
                'toolbar1' => 'bold,italic,underline,separator,alignleft,aligncenter,alignright, link,unlink,  ',
                'toolbar2' => '',
                'toolbar3' => ''

            ) ,
            'textarea_rows' => 1,
            'teeny' => true,
            'quicktags' => false,
            'media_buttons' => false
        );

        wp_editor($content, $editor_id, $settings);

?>
<input type="hidden" name="action" value="custom_action" />
 <input type="hidden" name="escrow_id"  id="escrow_id" value="<?php echo $escrow->id; ?>" />
<input class="input btn btn-small" type="submit" name="submit" value="<?php _e('Submit Message', 'aistore') ?>"/>
</form> 
</div>

<div id="feedback"></div>
 
	
	<?php
    }

    // Accept Button
    function accept_escrow_btn($escrow)
    {
       
        $user_email = get_the_author_meta('user_email', get_current_user_id());
        
     
        
    if($escrow->payment_status <> "paid")  return "";  

   if ($escrow->sender_email == $user_email) return "";
   
   
 
        if ($escrow->status == "closed") return "";

        else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";

        else

        if ($escrow->status == "disputed") return "";

        else

        if ($escrow->status == "accepted") return "";

        

?>

 <form method="POST" action="" name="accepted" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input type="submit"  name="submit" value="<?php _e('Accept', 'aistore') ?>">
  <input type="hidden" name="action" value="accepted" />
</form> <?php
    }

    // cancel button
    function cancel_escrow_btn($escrow)
    {
         
 
 

        if ($escrow->status == "closed") return "";

        else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";

      
      
       $user_email = get_the_author_meta('user_email', get_current_user_id());




         
       if ($escrow->sender_email == $user_email)
       
       {
             if($escrow->payment_status == "paid")  return "";  

       

       }

           
 
 
       
       
       
      

       
      global $wpdb;  
$user_email = get_the_author_meta('user_email', get_current_user_id());
?>

 <form method="POST" action="" name="cancelled" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input type="submit"  name="submit" value="<?php _e('Cancel Escrow', 'aistore') ?>">
  <input type="hidden" name="action" value="cancelled" />
</form> <?php

    }

    // release button
    function release_escrow_btn($escrow)
    {
        
        
        
  $user_email = get_the_author_meta('user_email', get_current_user_id());


        
        
         if($escrow->payment_status <> "paid")  return "";  
         
         
         
       if ($escrow->sender_email <> $user_email) return "";
           
           
           
       
  
        if ($escrow->status == "closed") return "";

         else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";
        else

        if ($escrow->status == "pending") return "";

             
             
    
        
?>

  
 <form method="POST" action="" name="released" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input type="submit"  name="submit" value="<?php _e('Release', 'aistore') ?>">
  <input type="hidden" name="action" value="released" />
</form> <?php

    }

    // dispute button
    function dispute_escrow_btn($escrow)
    {
  

         if($escrow->payment_status <> "paid")  return "";  
         
         


        if ($escrow->status == "closed") return "";

        else

        if ($escrow->status == "released") return "";

        else

        if ($escrow->status == "cancelled") return "";

        else

        if ($escrow->status == "disputed") return "";

        else

        if ($escrow->status == "pending") return "";

?>

 <form method="POST" action="" name="disputed" enctype="multipart/form-data"> 
 
<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
  <input type="submit"  name="submit" value="<?php _e('Dispute', 'aistore') ?>">
  <input type="hidden" name="action" value="disputed" />
</form> <?php
    }

}

add_action('wp_ajax_custom_action', 'aistore_upload_file');

function aistore_upload_file()
{

    global $wpdb;
    $eid = sanitize_text_field($_REQUEST['eid']);

    $user_id = get_current_user_id();
    
     
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
        
      

    $email_id = get_the_author_meta('user_email', get_current_user_id());
    $escrow = $wpdb->get_row($wpdb->prepare("SELECT count(id) as count FROM {$wpdb->prefix}escrow_system WHERE ( sender_email = '" . $email_id . "' or receiver_email = '" . $email_id . "' ) and id=%s ", $eid));

    $c = (int)$escrow->count;
    if ($c > 0)
    {

        if (isset($_POST['aistore_nonce']))
        {
            $upload_dir = wp_upload_dir();

            if (!empty($upload_dir['basedir']))
            {
                $user_dirname = $upload_dir['basedir'] . '/documents/' . $eid;
                if (!file_exists($user_dirname))
                {
                    wp_mkdir_p($user_dirname);
                }
                $fileType = $_FILES['file']['type'];
                if ($fileType == "application/pdf")
                {
                    $filename = wp_unique_filename($user_dirname, $_FILES['file']['name']);

                    echo "filename" . $filename;

                    move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']) , $user_dirname . '/' . $filename);

                    $image = $upload_dir['baseurl'] . '/documents/' . $eid . '/' . $filename;
                    //             // save into database $image;
                    

                    $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_documents ( eid, documents,user_id,documents_name,ipaddress) VALUES ( %d,%s,%d,%s,%s)", array(
                        $eid,
                        $image,
                        $user_id,
                        $filename,
                        $ipaddress
                    )));
                }

                else
                {
                    echo "We accept only pdf file";
                }
            }
        }

        wp_die();
    }
    else
    {
        echo "Unauthorized user";
    }

}

add_action('wp_ajax_custom_action', 'aistore_chat_box');

function aistore_chat_box()
{

    global $wpdb;

    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
    {
        return _e('Sorry, your nonce did not verify.', 'aistore');
    }

    $message = sanitize_text_field(htmlentities($_POST['message']));
    $escrow_id = sanitize_text_field($_POST['escrow_id']);

    $user_login = get_the_author_meta('user_login', get_current_user_id());

    //issue 1
    

    $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_discussion ( eid, message, user_login ) VALUES ( %d, %s, %s ) ", array(
        $escrow_id,
        $message,
        $user_login
    )));

    wp_die();

}

add_action('wp_ajax_escrow_discussion', 'aistore_escrow_discussion');

function aistore_escrow_discussion()
{

    global $wpdb;
    $id = sanitize_text_field($_REQUEST['id']);

    $user_email = get_the_author_meta('user_email', get_current_user_id());

    $discussions = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}escrow_discussion ed , {$wpdb->prefix}escrow_system es WHERE ed.eid= es.id and ed.eid=%s and (es.sender_email=%s or es.receiver_email=%s ) order by ed.id desc", $id, $user_email, $user_email));

    foreach ($discussions as $row):

?> 
	
	<div class="discussionmsg">
   
  <p><?php echo html_entity_decode($row->message); ?></p>
  
  <br /><br />
  <b><?php echo $row->user_login; ?> </b>
  <h6 > <?php echo $row->created_at; ?></h6>
</div>
 
<hr>
    
    <?php
    endforeach;

    wp_die();
}

?>
