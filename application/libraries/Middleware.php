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

    // Forbids access only to selected permission
    public function no_permission($permission, $msg, $redirect = NULL ) {
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

    public function onlyajax() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        } else {
            die('Este método sólo acepta AJAX Requests.');
        }
    }

    // Delivers a response according to use_ajax function
    public function response(string $msg, string $type, string $redirect = NULL, array $data = NULL) {
        if (config_item('use_ajax')) {
            $response['type'] = $type;
            $response['msg'] = $msg;
            if ($redirect) {
                $response['redirect'] = $redirect;
            }
            if ($data) {
                $this->CI->load->view($redirect, $data);
            } else {
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

    // Renders a view according to use_ajax function
    public function renderview(string $view, array $data) {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            if (!config_item('use_ajax')) {
                die('AJAX Requests are not allowed!');
            } else {
                $this->CI->load->view($view, $data);
            }
        // If is not an AJAX Request
        } else {
            if (config_item('use_ajax')) {
                $data['script'] = $this->CI->uri->segment(1).'.js';
            }
            $data['content'] = $view;
            $this->CI->load->view('layouts/main', $data);
        }
    }

}