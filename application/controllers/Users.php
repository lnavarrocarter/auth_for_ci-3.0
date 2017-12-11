<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Controlador de Usuarios
|--------------------------------------------------------------------------
| Este es el controlador de Usuarios de Ncai Auth, encargado de todas las 
| operaciones con usuarios en la aplicación.
|
*/
class Users extends CI_Controller {

    /**
     * Carga Modelos y bloquea el acceso a usuarios no logeados.
     * @return type
     */
    public function __construct() {
        parent::__construct();
        /* Cargar Modelo */
        $this->load->model('User');
        $this->load->model('Group');
        /* Middleware */
        $this->middleware->only_auth();
    }

    /**
     * Devuelve html con una lista de todos o un grupo de usuarios
     * @return string or json
     */
    public function index() {
        $data['title'] = 'Listado de Usuarios';
        $data['description'] = 'Aquí puedes ver una lista de todos los usuarios en el sistema.';
        if ($this->session->userdata('permissions') & PERM['sadmin']) {
            $data['users'] = $this->User->read('users',NULL,['groups' => 'group_id'],'users.*, groups.name as group_name',true);
        } elseif ($this->session->userdata('permissions') & PERM['admin']) {
            $id = $this->session->userdata('group_id');
            $data['users'] = $this->User->read('users', ['group_id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name', true);
        } else {
            redirect('users/show/'.$this->session->userdata('id'));
        }
        $this->middleware->renderview('users/index', $data);
    }

    /**
     * Devuelve html con detalles de un usuario específico
     * @param int $id - el id del usuario
     * @return string or json
     */
    public function show($id) {
        $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if (!$query) {
            $id = $this->session->userdata('id');
            $data['user'] = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        } else {
          $data['user'] = $query;  
        }
        $data['title'] = 'Perfil de Usuario';
        $data['description'] = 'Aquí puedes ver el perfil de Usuario';
        //$this->output->enable_profiler(TRUE);
        $this->middleware->renderview('users/show', $data);
    }

    /**
     * Devuelve html con formulario para crear nuevo usuario
     * @return string or json
     */
    public function add() {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'], 'No tienes los permisos suficientes.');
        $data['title'] = 'Nuevo Usuario';
        $data['description'] = 'Aquí puedes crear un nuevo usuario.';
        if ($this->session->userdata('permissions') & PERM['sadmin']) {
            $data['groups'] = $this->Group->read();
        } elseif ($this->session->userdata('permissions') & PERM['admin']) {
            $id = $this->session->userdata('group_id');
            $data['groups'] = $this->Group->read('groups', ['id' => $id]);
        } else {
            $data['groups'] = NULL;
        }
        $this->middleware->renderview('users/new', $data);
    }

    /**
     * Crea un nuevo usuario
     * @return string or json
     */
    public function create() {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');

        // Valido los datos
        $this->form_validation->set_rules('name1', 'nombre', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('lastname1', 'apellido', 'trim|required|min_length[1]|max_length[20]');
        if (config_item('register_with_username')) {
             $this->form_validation->set_rules('username', 'nombre de usuario', 'trim|required|min_length[5]|max_length[15]|is_unique[users.username]');
        }
        $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('group_id', 'grupo', 'trim|required|min_length[1]|max_length[2]');
        $this->form_validation->set_rules('passwd', 'contraseña', 'trim|required|min_length[5]|max_length[20]');
        if(!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->middleware->response(validation_errors(),'error');
        }
        // Ajusto el valor de los permisos
        if ($this->input->post('permissions')) {
            $permissions = 0;
            foreach ($this->input->post('permissions') as $key => $val) {
                (int)$val;
                $permissions += $val;
            }
            $data['permissions'] = $permissions;
        } else {
            $data['permissions'] = config_item('default_permissions');
        }
        if ($this->session->userdata('permissions') < $data['permissions']) {
            $this->middleware->response('¿Pasándote de listo?','error');
        }
        // Hasheo el Password
        if ($this->config->item('use_salt')) {
            $data['salt'] = uniqid(mt_rand(), true);
            $data['password'] = password_hash($data['salt'].$this->input->post('passwd'), PASSWORD_BCRYPT);
        } else {
            $data['password'] = password_hash($this->input->post('passwd'), PASSWORD_BCRYPT);
        }
        if ($this->config->item('activation_email')) {
            $data['activation_code'] = hash('ripemd160', mt_rand());
            $data['is_active'] = 0;
        } else {
            $data['is_active'] = 1;
        }
        $data['is_locked'] = 0;
        // Arreglo los datos
        $data['name1'] = $this->input->post('name1');
        $data['lastname1'] = $this->input->post('lastname1');
        $data['email'] = $this->input->post('email');
        $data['group_id'] = $this->input->post('group_id');
        // Hago la query
        $query = $this->User->create('users', $data);
        // La respuesta
        if (!$query) {
            $this->middleware->response('Imposible crear usuario. Intente más tarde.', 'error');
        } else {
            $this->middleware->response('Usuario creado correctamente', 'success', 'referer');
        }
    }

    /**
     * Devuelve html con formulario para editar un usuario
     * @param int $id 
     * @return string or json
     */
    public function edit($id) {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'], 'No tienes los permisos suficientes.');
        $data['user'] = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if ($this->session->userdata('permissions') < $data['user']->permissions) {
            $this->middleware->response('No tienes permisos suficientes.', 'error');
        }
        $data['title'] = 'Editar Usuario';
        $data['description'] = 'Aquí puedes editar un nuevo usuario.';
        if ($this->session->userdata('permissions') & PERM['sadmin']) {
            $data['groups'] = $this->Group->read();
        } elseif ($this->session->userdata('permissions') & PERM['admin']) {
            $id = $this->session->userdata('group_id');
            $data['groups'] = $this->Group->read('groups', ['id' => $id]);
        }
        $this->middleware->renderview('users/edit', $data);
    }

    /**
     * Actualiza un usuario
     * @param int $id 
     * @return string or json
     */
    public function update($id) {
        // Validaciones: AJAX, Permisos, Grupos y Formulario.
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if ($this->session->userdata('permissions') < $query->permissions) {
            $this->middleware->response('No puedes editar un usuario de más privilegios que tú.', 'error');
        } elseif ($this->session->userdata('permissions') & PERM['admin'] && $this->session->userdata('group_id') < $query->group_id) {
            $this->middleware->response('No puedes editar un usuario que no pertenece a tu grupo.', 'error');
        }
        // Valido los datos
        $this->form_validation->set_rules('name1', 'nombre', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('lastname1', 'apellido', 'trim|required|min_length[1]|max_length[20]');
        if (config_item('register_with_username')) {
            $this->form_validation->set_rules('username', 'nombre de usuario', 'trim|required|min_length[5]|max_length[15]');
            $data['username'] = $this->input->post('username');
        }
        if (config_item('user_location')) {
            $this->form_validation->set_rules('location', 'ubicación', 'trim|min_length[5]|max_length[40]');
            $data['location'] = $this->input->post('location');
        }
        if (config_item('user_birthdate')) {
            $this->form_validation->set_rules('birthdate', 'fecha de nacimiento', 'trim|min_length[5]|max_length[40]');            
            if ($this->input->post('birthdate')) {
                $data['birthdate'] = strtotime($this->input->post('birthdate'));
            }
        }
        if (config_item('user_gender')) {
            $this->form_validation->set_rules('gender', 'sexo', 'trim|min_length[1]|max_length[2]');
            $data['gender'] = $this->input->post('gender');
        }
        if (config_item('user_phone')) {
             $this->form_validation->set_rules('mobile', 'teléfono móvil', 'trim|min_length[5]|max_length[40]');
             $this->form_validation->set_rules('phone', 'otro teléfono', 'trim|min_length[5]|max_length[40]');
             $data['mobile'] = $this->input->post('mobile');
             $data['phone'] = $this->input->post('phone');
        }
        $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email');
        $this->form_validation->set_rules('group_id', 'grupo', 'trim|required|min_length[1]|max_length[2]');
        if(!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->middleware->response(validation_errors(), 'error');
        } else {
            // Ajusto el valor de los permisos
            if ($this->input->post('permissions')) {
                $permissions = 0;
                foreach ($this->input->post('permissions') as $key => $val) {
                    (int)$val;
                    $permissions += $val;
                }
                $data['permissions'] = $permissions;
            } else {
                $data['permissions'] = config_item('default_permissions');
            }
            if ($this->session->userdata('permissions') < $data['permissions']) {
                $this->middleware->response('¿Pasándote de listo?','error');
            }
            // TODO: Chequear que un usuario de menos permisos no edite uno mayor
            $data['name1'] = $this->input->post('name1');
            $data['lastname1'] = $this->input->post('lastname1');
            $data['email'] = $this->input->post('email');
            $data['group_id'] = $this->input->post('group_id');
            $query = $this->User->update('users', $data, ['id' => $id]);
            if (!$query) {
                $this->middleware->response('Imposible actualizar los datos. Intente más tarde.', 'error');
            } else {
                $this->middleware->response('Usuario actualizado correctamente.', 'success', 'referer');
            }
        }
    }

    /**
     * Destruye un usuario
     * @param int $id 
     * @return string or json
     */
    public function destroy($id) {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if ($this->session->userdata('permissions') < $query->permissions) {
            $this->middleware->response('No puedes eliminar un usuario de más privilegios que tú.', 'error');
        } elseif ($this->session->userdata('permissions') & PERM['admin'] && $this->session->userdata('group_id') < $query->group_id) {
            $this->middleware->response('No puedes eliminar un usuario que no pertenece a tu grupo.', 'error');
        }
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes eliminar tu propio usuario', 'error');
        }
        $query = $this->User->delete('users', ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible eliminar el usuario. Intente más tarde.', 'error');
        } else {
            $this->middleware->response('Usuario eliminado correctamente.', 'success', 'referer');
        }
    }

 /*
 |--------------------------------------------------------------------------
 | USUARIOS - Métodos Específicos
 |--------------------------------------------------------------------------
 | Estos métodos específicos son propios de la clase y se alejan del CRUD
 | básico. Por ejemplo, existe la opción para bloquear o desbloquear un 
 | usuario, cambiar la contraseña, o cambiar su foto de perfil de un usuario.
 |
 */

    /**
     * Bloquea un usuario
     * @param int $id 
     * @return string or json
     */
    public function lock($id) {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes bloquear tu propio usuario.', 'error');
        }
        $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if ($this->session->userdata('permissions') < $query->permissions) {
            $this->middleware->response('No puedes bloquear un usuario de más privilegios que tú.', 'error');
        } elseif ($this->session->userdata('permissions') & PERM['admin'] && $this->session->userdata('group_id') < $query->group_id) {
            $this->middleware->response('No puedes bloquear un usuario que no pertenece a tu grupo.', 'error');
        }
        $data['is_locked'] = 1;
        $query = $this->User->update('users', $data, ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible bloquear el usuario. Intente más tarde.', 'error');
        } else {
            $this->middleware->response('Usuario bloqueado correctamente.', 'success', 'referer');
        }
    }

    /**
     * Desbloquea un usuario
     * @param int $id 
     * @return string or json
     */
    public function unlock($id) {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        if ($this->session->userdata('permissions') < $query->permissions) {
            $this->middleware->response('No puedes desbloquear un usuario de más privilegios que tú.', 'error');
        } elseif ($this->session->userdata('permissions') & PERM['admin'] && $this->session->userdata('group_id') < $query->group_id) {
            $this->middleware->response('No puedes desbloquear un usuario que no pertenece a tu grupo.', 'error');
        }
        $data['is_locked'] = 0;
        $query = $this->User->update('users', $data, ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible desbloquar el usuario. Intente más tarde.', 'error');
        } else {
            $this->middleware->response('Usuario desbloqueado correctamente.', 'success', 'referer');
        }
    }

    /**
     * Resetea una contraseña por email
     * @param type $id 
     * @return string or json
     */
    public function passwd_reset($id) {
        $this->middleware->onlyajax();
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes reestablecer la contraseña de tu propio usuario.', 'error');
        }
        // Genero un token
        $token = bin2hex(random_bytes(30));
        $query = $this->User->update('users', ['forgotten_password_code' => $token], ['id' => $id]);
        $user = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
        // Si la query falla, alertamos al usuario.
        if(!$query) {
            $msg = 'Algo salió mal. Por favor, intenta nuevamente.';
            $this->middleware->response($msg, 'error');
        } else {
            // Se envía el email si todo sale bien.
            $data['url'] = base_url('auth/password_reset/'.$token);
            $data['button'] = config_item('email_passchange_button_text');
            $data['paragraphs'] = config_item('email_passchange_paragraphs');
            $data['title'] = config_item('email_passchange_title');
            $body = $this->load->view('auth/email/content', $data, true);
            $subject = config_item('email_passchange_subject');
            $this->load->library('email');
            $this->load->config('email');
            $this->email->set_newline("\r\n");
            $this->email->from($this->config->item('smtp_user'), $this->config->item('app_name'));
            $this->email->to($user->email);
            $this->email->subject($subject);
            $this->email->message($body);
            // Si el email no se envía bien, alertar al usuario.
            if (!$this->email->send()) {
                $msg = 'No hemos podido enviar un correo electrónico al usuario. Intenta nuevamente.';
                $this->middleware->response($msg, 'error');
            // Si se envía bien, terminamos el proceso con un mensaje de confirmación.
            } else {
                $msg = 'Hemos envíado un correo electrónico con un enlace al usuario seleccionado para que pueda elegir una nueva contraseña. No olvides comentarle que revise su carpeta de spam.';
                $this->middleware->response($msg, 'success');
            }
        }
    }

    /**
     * Cambia la contraseña de un usuario
     * @param int $id 
     * @return html o json
     */
    public function passwd_change($id) {
        $this->middleware->onlyajax();
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            // Cargar la vista
            $data['user'] = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
            $data['title'] = 'Cambiar Contraseña';
            $data['description'] = 'Aquí puedes cambiar tu contraseña.';
            $this->middleware->renderview('users/passwd_change', $data);
        } elseif ($this->input->server('REQUEST_METHOD') == 'POST') {
            if ($this->session->userdata('id') != $id) {
                $this->middleware->response('No puedes cambiar la contraseña de otro usuario.', 'error');
            }
            $this->form_validation->set_rules('passwd_old', 'contraseña antigua', 'trim|required|min_length[5]|max_length[30]');
            $this->form_validation->set_rules('passwd', 'nueva contraseña', 'trim|required|min_length[5]|max_length[30]');
            $this->form_validation->set_rules('passwd2', 'confirmación nueva contraseña', 'trim|required|min_length[5]|max_length[30]|matches[passwd]');
            if(!$this->form_validation->run()) {
                $this->form_validation->set_error_delimiters('', '');
                $this->middleware->response(validation_errors(), 'error');
            }
            $query = $this->User->read('users', ['users.id' => $id],['groups' => 'group_id'],'users.*, groups.name as group_name');
            if(!$query) {
                $msg = 'Hubo un problema.';
                $this->middleware->response($msg, 'error');
            // Chequeo el password
            } else {
                if ($this->config->item('use_salt')) {
                    $pass = $query->salt.$this->input->post('passwd_old');
                } else {
                    $pass = $this->input->post('passwd_old');
                }
            }
            if (!password_verify($pass, $query->password)) {
                $msg = 'La contraseña antigua es incorrecta.';
                $this->middleware->response($msg, 'error');
            } else {
                if ($this->config->item('use_salt')) {
                    $data['salt'] = uniqid(mt_rand(), true);
                    $data['password'] = password_hash($data['salt'].$this->input->post('passwd'), PASSWORD_BCRYPT);
                } else {
                    $data['password'] = password_hash($this->input->post('passwd'), PASSWORD_BCRYPT);
                }
                $query = $this->User->update('users', $data, ['id' => $id]);
                if (!$query) {
                    $this->middleware->response('Imposible cambiar la contraseña. Intente más tarde.', 'error');
                } else {
                    $this->middleware->response('Contraseña cambiada exitosamente.', 'success', 'referer');
                }
            }
        } else {
            die('Inavlid Request');
        }
    }

    /**
     * Cambia la imagen de perfil de un usuario
     * @param type $id 
     * @return type
     */
    public function change_profile_img($id) {
        // TODO: Mejorar respuesta del método de cambio de imagen.
        $this->middleware->onlyajax();
        $data = $this->input->post('image');
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        $imageName = sha1($id).'.png';
        $this->User->update('users', ['avatar_url' => $imageName], ['id' => $id]);
        if ($id == $this->session->userdata('id')) {
            $this->session->set_userdata(['avatar' => $imageName]);
        }
        file_put_contents('assets/img/profile/'.$imageName, $data);
    }

}