<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware->only_auth();
    }

    public function index() {
        echo '<h1>Esta es la página de inicio de tu APP</h1>';
        echo 'Mensaje de Exito: '.$this->session->flashdata('success');
        echo '<br><br>';
        echo 'Mensaje de Error: '.$this->session->flashdata('error');
    }

    public function profile() {
        echo '<h1>Este es tu perfil.</h1>';
    }

    public function admin_area() {
        $this->middleware->only_permission(ADMIN, 'app');
        echo '<h1>Esta es el area de administradores.</h1>';
    }
    
}