<?php


function aistore_users( $contactmethods ) {
    $contactmethods['escrow_list'] = 'Escrow';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'aistore_users', 10, 1 );
 
 function aistore_user_escrow( $column ) {

       
    $column['escrow_list'] = 'Escrow';
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_user_escrow' );




function aistore_user_escrow_list( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'escrow_list':
 
         $link= '<a href="/wp-admin/admin.php?page=wp_list_table_class&id='.$user_id.'">Escrow List</a>';

   
       
 return $link;

   
        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_user_escrow_list', 10, 3 );
add_filter( 'manage_users_custom_column', 'aistore_user_escrow_list', 10, 3 );


?>