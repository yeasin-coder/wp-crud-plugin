<?php

//the file will load automatically the admin click on the 'delete' button in wordpress admin page


//if wordpress does not access the uninstall.php itself then exit() the execution
if( ! defined(WP_UNINSTALL_PLUGIN) ){
    exit();
}

$role = get_role('administrator');

if( ! empty($role) ){
    //remove the newly created capability if user delete the plugin
    $role->remove_cap('crud_manage');
}

//unregister the settings that created with the plugin activation
unregister_setting('crud_group_options', 'crud_options');

//delete the option that created with the plugin activation
delete_option('crud_options');

// //delete the options set by the plugin if the plugin delete


// // delete_option($option);

// //delete all database tables that created with the plugin
// global $wpdb;

// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}mytable");