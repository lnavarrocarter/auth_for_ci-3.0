<?php

/*
|--------------------------------------------------------------------------
| Ncai Auth Middlewares
|--------------------------------------------------------------------------
| En esta básica librería puedes configurar algunas funciones para que
| actúen como middlewares ente tu request y el controlador. Esto no es
| un Middleware propiamente tal, ya que se coloca dentro del controlador y
| no antes de éste. Sin embargo, cumple extactamente la misma función.
|
| Hay tres métodos básicos. El primero es para permitir el acceso sólo a los
| usuarios que tienen sesión iniciada, o usuarios autenticados. El 
| segundo es para permitir el acceso sólo a los que no tienen sesión inciada,
| o visitas. El tercero es para permitir el acceso sólo a los que tienen
| los permisos indicados.
|
| Autor: Matías Navarro Carter
| Email: mnavarrocarter@gmail.com
| Licencia: MIT
|
|
*/

class Middleware {

    private $CI;

    function __construct() {
        // Assign by reference with "&" so we don't create a copy
        $this->CI = &get_instance();
    }

    // Si el usuario tiene sesión iniciada, lo deja pasar. Si no, lo redirije.
    public function only_auth() {
        if (!$this->CI->session->userdata('logged_in')) {
            $this->CI->session->set_userdata('redirect', substr($_SERVER[REQUEST_URI], 1));
            $this->CI->session->mark_as_temp('redirect', 300);
            $this->CI->session->set_flashdata('message', 'Debes iniciar sesión antes de poder continuar a la página que deseas visitar.');
            redirect('auth/login');
        }
    }

    // 
    public function only_visitor($redirect_to) {
        if($this->CI->session->userdata('logged_in')) {
            redirect($redirect_to);
        }
    }

    // Allows access only to selected permission
    public function only_permission($permission, $redirect_to, $msg = FALSE) {
        if($this->CI->session->userdata('permissions') & $permission) {
            // Pasa
        } else {
            if ($msg) {
                $this->CI->session->set_flashdata('error', 'No tienes permisos suficientes para acceder a este recurso.');
            }
            redirect($redirect_to);
        }
    }

    // Forbids access only to selected permission
   public function no_permission($permission, $redirect_to, $msg = FALSE) {
        if($this->CI->session->userdata('permissions') & $permission) {
            if ($msg) {
                $this->CI->session->set_flashdata('error', 'No tienes permisos suficientes para acceder a este recurso.');
            }
            redirect($redirect_to);
        } else {
            // Pasa
        }
    }

}