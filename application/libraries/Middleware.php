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
        // $this->CI->load->library('session');
    }

    // Si el usuario tiene sesión iniciada, lo deja pasar. Si no, lo redirije.
    public function only_auth() {
        if (!$this->CI->session->userdata('logged_in')) {
            $this->CI->session->set_userdata('redirect', (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
            $this->CI->session->mark_as_temp('redirect', 300);
            $this->CI->session->set_flashdata('info', 'Debes iniciar sesión antes de poder continuar.');
            redirect('auth/login');
        }
    }

    // 
    public function only_visitor($redirect_to) {
        if($this->CI->session->userdata('logged_in')) {
            redirect($redirect_to);
        }
    }

    public function only_permission($permission, $redirect_to) {
        if(!$this->CI->session->userdata('permissions') & $permission) {
            $this->CI->session->set_flashdata('error', 'No tienes permisos suficientes para acceder a este recurso.');
            redirect($redirect_to);
        } else {

        }
    }

}