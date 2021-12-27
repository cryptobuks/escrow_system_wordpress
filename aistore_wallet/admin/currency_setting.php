<?php


        if (isset($_POST['submit']) and $_POST['action'] == 'create_currency')
        {

            if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
            {
                return _e('Sorry, your nonce did not verify.', 'aistore');

                exit;
            }

            $aistore_escrow_currency = sanitize_text_field($_REQUEST['aistore_escrow_currency']);
            echo $aistore_escrow_currency;

            global $wpdb;

            // add currency also
            $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}escrow_currency ( currency, symbol  ) VALUES ( %s ,%s)", array(
                $aistore_escrow_currency,
                $aistore_escrow_currency
            )));

            $eid = $wpdb->insert_id;
        }
        else
        {

?>
<table class=" table form-table">
         <form method="POST" action="" name="create_currency" enctype="multipart/form-data"> 
    <?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
    
         <h3><?php _e('Currency Setting', 'aistore') ?></h3>
         <br>
           <tr valign="top">
        <th scope="row"><?php _e('Currency', 'aistore') ?></th>

        <td>
            	
<?php



            $plugin_data = get_plugin_data(__FILE__, 3);
            
           // print_r($plugin_data);
            $plugin_name = $plugin_data['TextDomain'];
            // $dir = '/wp-content/plugins/saksh-escrow-system/Common-Currency.json';
           // echo $dir;
       //  $file_path =  include_once dirname(__FILE__) . '/Common-Currency.json';
            $file_path =   '/wp-content/plugins/escrow_system_wordpress-master/aistore_wallet/admin/Common-Currency.json';
       
           $filedata = file_get_contents($file_path);
       
       
       $currency= json_decode($filedata);
        // $url = get_site_url(null, $dir, 'https');
        //     $currency = json_decode(file_get_contents($url));
?>


       <?php $escrow_currency = get_option('aistore_escrow_currency'); ?>
       
       
                
            <select name="aistore_escrow_currency">
                <?php
           
            $a = array();
            foreach ($currency as $c)
            {

                echo '	<option  value="' . $c->code . '">' . $c->name . '</option>';

            }

?>
        
      
        <input class="input" type="submit" name="submit" value="<?php _e(' Submit', 'aistore') ?>"/>
<input type="hidden" name="action"  value="create_currency"/></td></tr>
    </form></table>
<?php
        }

        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}escrow_currency  order by id desc");

?>
<h3><u><?php _e('Currency ', 'aistore'); ?></u> </h3>
<?php
        if ($results == null)
        {
            echo "<div class='no-result'>";

            _e('No Currency Found', 'aistore');
            echo "</div>";
        }
        else
        {

            ob_start();

?>
  
    <table class="table  ">
     
        <tr>
      
   
        <th><?php _e('Currency', 'aistore'); ?></th>
          <th><?php _e('Action', 'aistore'); ?></th>
        
</tr>

    <?php
            foreach ($results as $row):
?>
 
 
      
    
      <tr>


  <td> 		   <?php echo esc_attr($row->currency); ?> </td>
   <td><?php
                if (isset($_POST['submit']) and $_POST['action'] == 'escrow_currency')
                {

                    if (!isset($_POST['aistore_nonce']) || !wp_verify_nonce($_POST['aistore_nonce'], 'aistore_nonce_action'))
                    {
                        return _e('Sorry, your nonce did not verify', 'aistore');
                        exit;
                    }

                    $currency_id = sanitize_text_field($_REQUEST['escrow_currency_id']);
                    $table = $wpdb->prefix . 'escrow_currency';
                    $wpdb->delete($table, array(
                        'id' => $currency_id
                    ));

                }
                else
                {
?>
    <form method="POST" action="" name="escrow_currency" enctype="multipart/form-data"> 

<?php wp_nonce_field('aistore_nonce_action', 'aistore_nonce'); ?>
	<input 
 type="hidden" name="escrow_currency_id" value="<?php echo esc_attr($row->id); ?>"/>
<input 
 type="submit" name="submit" value="<?php _e('Delete', 'aistore') ?>"/>
<input type="hidden" name="action" value="escrow_currency" />
                </form><?php
                } ?></td>
            </tr>
    <?php
            endforeach;

        } ?>

    </table>
    
     
	
<?php
    
