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

    /**
     * Contructor
     * @return type
     */
    function __construct() {
        // Assign by reference with "&" so we don't create a copy
        $this->CI = &get_instance();
        $this->CI->load->model('User');
        $this->CI->load->model('Group');
    }

    /**
     * Makes a controller or method only accesible to logged in users. Redirects to login if not.
     * @return redirect
     */
    public function only_auth() {
        if (!$this->CI->session->userdata('logged_in')) {
            $this->CI->session->set_userdata('redirect', substr($_SERVER[REQUEST_URI], 1));
            $this->CI->session->mark_as_temp('redirect', 300);
            $this->CI->session->set_flashdata('message', 'Debes iniciar sesión antes de poder continuar a la página que deseas visitar.');
            redirect('auth/login');
        }
    }

    /**
     * Makes a controller or a method only accesible by not logged users.
     * @param type $redirect_to 
     * @return type
     */
    public function only_visitor($redirect_to) {
        if($this->CI->session->userdata('logged_in')) {
            redirect($redirect_to);
        }
    }

    /**
     * Allows access to user with proper permissions.
     * @param type $permission 
     * @param type $msg 
     * @param type|null $redirect 
     * @return type
     */
    public function only_permission($permission, $msg, $redirect = NULL ) {
        if($this->CI->session->userdata('permissions') & $permission) {
            // Pasa
        } else {
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                $response['msg'] = $msg;
                $response['type'] = 'error';
                echo json_encode($response);
                die;
            } else {
                if ($redirect) {
                    redirect($redirect);
                } else {
                    $this->CI->session->set_flashdata('error', $msg);
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
    }

    /**
     * Blocks access to users who don't have proper permissions.
     * @param int $permission 
     * @param string $msg 
     * @param string|null $redirect 
     * @return type
     */
    public function no_permission(int $permission, string $msg, string $redirect = NULL ) {
        if($this->CI->session->userdata('permissions') & $permission) {
            if ($msg) {
                if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                    $response['msg'] = $msg;
                    $response['type'] = 'error';
                    echo json_encode($response);
                    die;
                } else {
                    if ($redirect) {
                        redirect($redirect);
                    } else {
                        $this->CI->session->set_flashdata('error', $msg);
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
            }
        } else {
            // Pasa
        }
    }

    /**
     * Protects a method or a controller from being accesed if is not an ajax request.
     * @return die
     */
    public function onlyajax() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        } else {
            die('Este método sólo acepta AJAX Requests.');
        }
    }

    /**
     * Renders html according to the proper call. 
     * @param string $view 
     * @param array|null $data 
     * @param string|null $msg 
     * @param string|null $type 
     * @return type
     */
    public function renderview($view, array $data = NULL, string $msg = NULL, string $type = NULL) {
        // Check if is an ajax request or a regular one
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($msg && $type) {
                $response['msg'] = $msg;
                $response['type'] = $type;
            }
            // FIXME: Redirect to another url if needed, logout and login not working
            if ($this->CI->session->flashdata('msg') && $this->CI->session->flashdata('type')) {
                $response['msg'] = $this->CI->session->flashdata('msg');
                $response['type'] = $this->CI->session->flashdata('type');
            }
            $response['html'] = $this->CI->load->view($view, $data, TRUE);
            echo json_encode($response);
            die;
        } else {
            if ($msg && $type) {
                $this->CI->session->set_flashdata($type, $msg);
            }
            $data['content'] = $view;
            $this->CI->load->view('layouts/main', $data);
        }
    }

    /**
     * Sends a json response to an ajax request.
     * @param string $msg 
     * @param array $type 
     * @return json
     */
    public function response(string $msg, string $type, string $redirect = NULL) {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if ($redirect == 'referer') {
                $this->CI->session->set_flashdata('msg', $msg);
                $this->CI->session->set_flashdata('type', $type);
                redirect($_SERVER['HTTP_REFERER']);
            } else {
               $response['type'] = $type;
               $response['msg'] = $msg;
               if ($redirect) {
                   $response['redirect'] = $redirect;
               }
               echo json_encode($response);
               die; 
            }
        } else {
            $this->CI->session->set_flashdata($type, $msg);
            if ($redirect) {
                redirect($redirect);
            } else {
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

}