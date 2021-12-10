<?php


function aistore_transaction( $contactmethods ) {
    $contactmethods['transaction'] = 'Transaction';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'aistore_transaction', 10, 1 );
 
 function aistore_user_transaction( $column ) {

       
    $column['transaction'] = 'Transaction';
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_user_transaction' );




function aistore_user_transaction_list( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'transaction':
 
         $link= '<a href="/wp-admin/admin.php?page=all_wallet_transaction&id='.$user_id.'">Transaction List</a>';

   
       
 return $link;

   
        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_user_transaction_list', 10, 3 );
add_filter( 'manage_users_custom_column', 'aistore_user_transaction_list', 10, 3 );


?>