<?php 

// This function is a "dump and die" for debugging.
function dd($data) {
    echo '<pre>';
    var_dump($data);
    die;
}

// This function gets user permissions
function get_permissions($number) {
    $user_permissions = [];
    foreach (PERM as $permission) {
        if ($number & $permission) {
            $user_permissions[$permission] = PERMT[$permission];
        }
    }
    return $user_permissions;
}