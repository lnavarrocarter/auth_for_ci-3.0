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

// This gets the lower ones.
function get_lower_permissions($number) {
    $user_permissions = [];
    foreach (PERM as $role) {
        if ($number >= $role) {
            $user_permissions[$role] = PERMT[$role];
        }
    }
    return $user_permissions;
}

// Get the gender name
function get_gender($number) {
    if ($number == 1) {
        echo 'Masculino';
    } elseif ($number == 2) {
        echo 'Femenino';
    } else {
        echo 'No definido';
    }
}

function get_status($number){
    switch ($number) {
        case '1':
            return 'Habilitado';
            break;
        case '2':
            return 'Deshabilitado';
            break;
        case '3':
            return 'Suspendido';
            break;
        case '4':
            return 'Eliminado';
            break;
        case '5':
            return 'Destruido';
            break;
        default:
            return 'error';
            break;
    }
}