<?php


function aistore_all_notification( $contactmethods ) {
    $contactmethods['notification_list'] = 'Notification';
    return $contactmethods;
}
add_filter( 'user_contactmethods', 'aistore_all_notification', 10, 1 );
 
 function aistore_user_notification( $column ) {

       
    $column['notification_list'] = 'Notification';
    return $column;
}

add_filter( 'manage_users_columns', 'aistore_user_notification' );




function aistore_user_notification_list( $val, $column_name, $user_id ) {



    switch ($column_name) {

 
        case 'notification_list':
 
         $link= '<a href="/wp-admin/admin.php?page=all_notification&id='.$user_id.'">Notification</a>';

   
       
 return $link;

   
        default:
    }


    return $val;

}


add_filter( 'manage_users_custom_column', 'aistore_user_notification_list', 10, 3 );
add_filter( 'manage_users_custom_column', 'aistore_user_notification_list', 10, 3 );


?>