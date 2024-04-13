<?php 

// namespace Crud;

class Activate{
    public static function activate(){
        //get the role of the current logged in user
        $role = get_role('administrator');

        if( ! empty($role) ){
            //set a custom user capability if the logged in user is administrator
            $role->add_cap('crud_manage');
        }
    }
}