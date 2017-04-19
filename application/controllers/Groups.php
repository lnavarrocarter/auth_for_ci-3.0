<?php

/*
|--------------------------------------------------------------------------
| Controlador de Grupos
|--------------------------------------------------------------------------
|
| Grupos es un grupo de usuarios que pertenecen a una organización, grupo
| de trabajo o asociación.
|
*/

class Groups extends CI_Controller {

    ###############################
    # CONSTRUCTOR DEL CONTROLADOR #
    ###############################

    public function __construct() {
        parent::__construct();
        // Cargar Modelo
        $this->load->model('Group');
        //Middleware
        $this->middleware->only_auth();
    }
    
    ###################################
    # MÉTODOS BÁSICOS DEL CONTROLADOR #
    ###################################

    // Llama una vista que muestra todos los recursos
    public function index() {
        $this->middleware->only_permission(PERM['sadmin'], 'groups/show/'.$this->session->userdata('group_id'));
        $query = $this->Group->read();
        $data['groups'] = $query;
        $data['title'] = 'Listado de Grupos';
        $data['content'] = 'groups/index';
        $this->load->view('layouts/main', $data);
    }

    // Llama una vista que muestra un recurso por id
    public function show($id) {
        $this->middleware->no_permission(PERM['user'], 'users/show/'.$this->session->userdata('id'));
        // TODO: Test the if statement of the method show, to see if it works with bitwise and another condition
        if ($this->session->userdata('permissions') & PERM['admin'] && $this->session->userdata('group_id') != $id) {
            redirect('groups/show/'.$id);
        }
        $this->load->model('User');
        $query = $this->Group->read('groups', ['id' => $id]);
        $data['group'] = $query;
        $data['users'] = $this->Users->read('users', ['group_id' => $id]);
        $data['title'] = $data['group']['name'];
        $data['content'] = 'groups/show';
        $this->load->view('layouts/main', $data);
    }

    // Llama una vista con un formulario para crear un recurso nuevo
    public function new() {
        $this->load->view('groups/new');
    }

    // Ejecuta el proceso para crear un nuevo recurso
    public function create() {
        // Valido los datos TODO: Do the validation in the create method
        $this->form_validation->set_rules('name', 'nombre', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email|is_unique[groups.email]');
        $this->form_validation->set_rules('token', 'token de acceso', 'trim|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('max_members', 'número de miembros', 'required|trim|min_length[1]|max_length[5]');
        if(!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters("\n", '');
            if ($this->config->item('use_ajax')) {
                $response = [
                    'type' => 'error',
                    'msg' => validation_errors(),
                    ];
                echo json_encode($response);
                die;
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect('groups/index');
            }
        }

        // Arreglo los datos
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');
        $data['token'] = $this->input->post('token');
        $data['max_members'] = $this->input->post('max_members');
        $data['is_active'] = 1;

        // Hago la query
        $query = $this->Group->create('groups', $data);

        // La respuesta
        if ($query) {
            $data['groups'] = $this->Group->read();
            $this->load->view('groups/index', $data);
        } else {
            $response = [
                'type'  => 'error',
                'msg'   => 'Imposible crear el grupo. Intente más tarde.'
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    // Llama a una vista con un formulario para editar un recurso existente
    public function edit($id) {
        $query = $this->Group->read('groups', ['id' => $id]);
        $data['group'] = $query;
        $this->load->view('groups/edit', $data);
    }

    // Ejecuta el proceso para editar un recurso existente
    public function update($id) {
        $this->form_validation->set_rules('name', 'nombre', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email');
        $this->form_validation->set_rules('token', 'token de acceso', 'trim|min_length[5]|max_length[50]');
        $this->form_validation->set_rules('max_members', 'número de miembros', 'required|trim|min_length[1]|max_length[5]');
        if(!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters("\n", '');
            if ($this->config->item('use_ajax')) {
                $response = [
                    'type' => 'error',
                    'msg' => validation_errors(),
                    ];
                echo json_encode($response);
                die;
            } else {
                $this->session->set_flashdata('error', validation_errors());
                redirect('groups/index');
            }
        }
        // Arreglo los datos
        $data['name'] = $this->input->post('name');
        $data['email'] = $this->input->post('email');
        $data['token'] = $this->input->post('token');
        $data['max_members'] = $this->input->post('max_members');
        // Hago la query
        $query = $this->Group->update('groups', $data, ['id' => $id]);
        if ($query) {
            $data['groups'] = $this->Group->read();
            $this->load->view('groups/index', $data);
        } else {
            $response = [
                'type'  => 'error',
                'msg'   => 'Imposible actualizar los datos. Intente más tarde.'
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    // Ejecuta el proceso para borrar un recurso existente
    public function destroy($id) {
        $query = $this->Group->delete('groups', ['id' => $id]);
        if ($query) {
            $data['groups'] = $this->Group->read();
            $this->load->view('groups/index', $data);
        } else {
            $response = [
                'type'  => 'error',
                'msg'   => 'Imposible eliminar este grupo. Intente más tarde.'
            ];
            echo json_encode($response, JSON_PRETTY_PRINT);
        }
    }

    #######################################
    # MÉTODOS ESPECÍFICOS DEL CONTROLADOR #
    #######################################

}