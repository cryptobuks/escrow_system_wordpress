<?php
class Aistore_Transaction_List extends WP_List_Table {

	/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Transaction', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Transaction', 'sp' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );

	}
	
	
	 
	 
	 

public function status_filter( $text, $input_id ) {


 
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }
 
        $input_id = $input_id . '-search-input';
 
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['detached'] ) ) {
            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
        }
        ?>
		 
    
<p class="search-box">
  <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
 




<select name="search_column"  >

 
  <option value="transaction_id"><?php  _e( 'Transaction Id', 'aistore' ) ?></option>
  <option value="user_email"><?php  _e( 'Email Id', 'aistore' ) ?></option>
  
    <option value="balance"><?php  _e( 'Balance', 'aistore' ) ?></option>
   <option value="currency"><?php  _e( 'Currency', 'aistore' ) ?></option> 
    <option value="type"><?php  _e( 'Type', 'aistore' ) ?></option> 
  
  
  
  
</select>

<select name="search_operator"  >

    <option value="="><?php  _e( 'Equal', 'aistore' ) ?> </option>
	
    <option value="!="><?php  _e( 'Not equal ', 'aistore' ) ?></option>
	
    <option value="LIKE"><?php  _e( 'Contains ', 'aistore' ) ?></option>
    <option value="NOT LIKE"><?php  _e( 'Not Contains', 'aistore' ) ?> </option> 
	
  <option value=">"><?php  _e( 'Greater than', 'aistore' ) ?></option>
 
  <option value=">="><?php  _e( 'Greater than or equal', 'aistore' ) ?> </option>
  <option value="<"><?php  _e( 'Less than', 'aistore' ) ?> </option>
  
   <option value="<="><?php  _e( 'Less than or equal', 'aistore' ) ?></option> 
  
  
  
  
  
</select>



     <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
        <?php submit_button( $text, '', '', false, array( 'transaction_id' => 'search-submit' ) ); ?>
</p>
        <?php
    }

public function date_filter( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }
 
        $input_id = $input_id . ' ';
 
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['detached'] ) ) {
            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
        }
        ?>
		
		
	 <input type="hidden" name="date_filter" value="1" /> 
		
	  
    <?php  _e( 'Start Date', 'aistore' ) ?><input type='date' class='dateFilter' name='fromDate' value='<?php if(isset($_POST['fromDate'])) echo $_POST['fromDate']; ?>'>
 
 <?php  _e( ' End Date', 'aistore' ) ?> <input type='date' class='dateFilter' name='endDate' value='<?php if(isset($_POST['endDate'])) echo $_POST['endDate']; ?>'>

  
     
        <?php submit_button( $text, '', '', false, array( 'transaction_id' => 'search-submit' ) ); ?>
 
        <?php
    }


public function search_box( $text, $input_id ) {
        if ( empty( $_REQUEST['s'] ) && ! $this->has_items() ) {
            return;
        }
 
        $input_id = $input_id . '-search-input';
 
        if ( ! empty( $_REQUEST['orderby'] ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['order'] ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['post_mime_type'] ) ) {
            echo '<input type="hidden" name="post_mime_type" value="' . esc_attr( $_REQUEST['post_mime_type'] ) . '" />';
        }
        if ( ! empty( $_REQUEST['detached'] ) ) {
            echo '<input type="hidden" name="detached" value="' . esc_attr( $_REQUEST['detached'] ) . '" />';
        }
        ?>
		 
    
<p class="search-box">
  <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo $text; ?>:</label>
  
	<select name="user_email" >
<option selected value="0">Selected User</option>
		  <?php
        $blogusers = get_users();

        foreach ($blogusers as $user)
        {
                echo '	<option  value="' . $user->user_email . '">' . $user->display_name . '</option>';
        } ?> 
 </select>

<select name="search_column"  >

 
  <option value="transaction_id"><?php  _e( 'Transaction Id', 'aistore' ) ?></option>
  <option value="user_email"><?php  _e( 'Email Id', 'aistore' ) ?></option>
  
    <option value="balance"><?php  _e( 'Balance', 'aistore' ) ?></option>
   <option value="currency"><?php  _e( 'Currency', 'aistore' ) ?></option> 
    <option value="type"><?php  _e( 'Type', 'aistore' ) ?></option> 
  
  
  
  
</select>


<select name="search_operator"  >

    <option value="="><?php  _e( 'Equal', 'aistore' ) ?> </option>
	
    <option value="!="><?php  _e( 'Not equal ', 'aistore' ) ?></option>
	
    <option value="LIKE"><?php  _e( 'Contains ', 'aistore' ) ?></option>
    <option value="NOT LIKE"><?php  _e( 'Not Contains', 'aistore' ) ?> </option> 
	
  <option value=">"><?php  _e( 'Greater than', 'aistore' ) ?></option>
 
  <option value=">="><?php  _e( 'Greater than or equal', 'aistore' ) ?> </option>
  <option value="<"><?php  _e( 'Less than', 'aistore' ) ?> </option>
  
   <option value="<="><?php  _e( 'Less than or equal', 'aistore' ) ?></option> 
  
  
  
  
  
</select>


     <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query(); ?>" />
        <?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
</p>
        <?php
    }

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_transactions( $per_page = 5, $page_number = 1 ) {

		global $wpdb;
		
		 

          
            if (isset($_REQUEST['id']))
        {
$id=sanitize_text_field($_REQUEST['id']);

	$sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE {$wpdb->prefix}users.ID= ".$id;
        }  
        else{
   
       	$sql = "SELECT * FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE 1=1 ";
}

	

// echo $sql;
$sql .=  Aistore_Transaction_List::prepareWhereClouse();



		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


//echo $sql;
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}

	public  static function prepareWhereClouse() {
		$sql="";
		
		
		


		if( ! empty( $_REQUEST['date_filter'] ) ){
           $fromDate = $_POST["fromDate"];
      $endDate   = $_POST["endDate"]; 

    //sql will be 
    $sql .= " and   DATE( `date`) BETWEEN '{$fromDate}' AND '{$endDate}'";
 

  }
  
  


		if( ! empty( $_REQUEST['s'] ) ){
         $search = esc_sql( $_REQUEST['s'] );
		  $search_column=esc_sql( $_REQUEST['search_column'] ); 
		  $search_operator=esc_sql( $_REQUEST['search_operator'] ); 
		 
   if($search_operator=="LIKE")
   { $sql .= " and   ". $search_column."  ".$search_operator." '%{$search}%'  "; }
else
{ $sql .= " and   ". $search_column."  ".$search_operator." '". $search ."'  ";
	
}
  }
	
	
	return   $sql ;
	
	}

	

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;
		
	$sql = "SELECT COUNT(*)  FROM {$wpdb->prefix}aistore_wallet_transactions  INNER JOIN {$wpdb->prefix}users ON  {$wpdb->prefix}aistore_wallet_transactions.user_id={$wpdb->prefix}users.ID WHERE 1=1 ";
	
	

$sql .=  Aistore_Transaction_List::prepareWhereClouse();

		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No Transactions  avaliable.', 'sp' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			
			case 'transaction_id':
			case 'user_email':
			case 'balance':	
			case 'currency':
			case 'amount':
			case 'type':	
			case 'description':
			case 'created_by':
			case 'date':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['transaction_id']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'transaction_id' => __( 'Transaction id', 'sp' ),
			'user_email'    => __( 'Email', 'sp' ),
			'balance'    => __( 'Balance', 'sp' ),
			'currency'    => __( 'Currency', 'sp' ),
			'amount'    => __( 'Amount', 'sp' ),
			'type'    => __( 'Type', 'sp' ),
			'description' => __( 'Description ', 'sp' ),
			'created_by' => __( 'Created By ', 'sp' ),
			'date'    => __( 'Date', 'sp' )
		];

		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'transaction_id' => array( 'transaction_id', true ),
			'user_email' => array( 'user_email', false ),
			'balance' => array( 'balance', false ),
			'currency' => array( 'currency', false ),
			'amount' => array( 'amount', false ),
			'type' => array( 'type', false ),
		   'description' => array( 'description', false ),
		   'created_by' => array( 'created_by', false ),
		   
			'date' => array( 'date', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	// public function get_bulk_actions() {
	// 	$actions = [
	// 		'bulk-delete' => 'Delete'
	// 	];

	// 	return $actions;
	// }

function form(){
 
		$from = ( isset( $_GET['mishaDateFrom'] ) && $_GET['mishaDateFrom'] ) ? $_GET['mishaDateFrom'] : '';
		$to = ( isset( $_GET['mishaDateTo'] ) && $_GET['mishaDateTo'] ) ? $_GET['mishaDateTo'] : '';
 
		echo ' 
 
		<input type="date" name="mishaDateFrom" placeholder="Date From" value="' . esc_attr( $from ) . '" />
		<input type="date" name="mishaDateTo" placeholder="Date To" value="' . esc_attr( $to ) . '" />
 
		 ';
 
	}
 
 
 
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {

		$this->_column_headers = $this->get_column_info();

		/** Process bulk action */
		$this->process_bulk_action();

		$per_page     = $this->get_items_per_page( 'customers_per_page', 20 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_transactions( $per_page, $current_page );
	}

	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
				die( 'Go get a life script kiddies' );
			}
			else {
				self::delete_transaction( absint( $_GET['transaction'] ) );

		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		                wp_redirect( esc_url_raw(add_query_arg()) );
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_transaction( $id );

			}

			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg()) );
			exit;
		}
	}

}


class Aistore_ST_TransactionListPlugin {

	// class instance
	static $instance;

	// customer WP_List_Table object
	public $transaction_obj;

	// class constructor
	public function __construct() {
		add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
		add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}

	public function plugin_menu() {

		$hook = add_menu_page(
			'All Transaction List',
			'All Transaction List',
			'manage_options',
			'all_wallet_transaction',
			[ $this, 'plugin_settings_page' ]
		);

		add_action( "load-$hook", [ $this, 'screen_option' ] );

	}


	/**
	 * Plugin settings page
	 */
	public function plugin_settings_page() {
		?>
		<div class="wrap">
			<h2><?php _e( 'All Transaction List', 'aistore' ) ;?></h2>

			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$this->transaction_obj->prepare_items();
		

 
	
	   $this->transaction_obj->status();
	   
	   
	   $this->transaction_obj->date_filter('Search', 'search');
	   
	   
	
	   $this->transaction_obj->search_box('Search', 'search');
	$this->transaction_obj->display(); 
								
								 ?>
									
									 
								
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
			</div>
		</div>
	<?php
	}

	/**
	 * Screen options
	 */
	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'wallet_transaction',
			'default' => 5,
			'option'  => 'wallet_transaction'
		];

		add_screen_option( $option, $args );

		$this->transaction_obj = new Aistore_Transaction_List();
	}


	/** Singleton instance */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}


add_action( 'plugins_loaded', function () {
	Aistore_ST_TransactionListPlugin::get_instance();
} );
