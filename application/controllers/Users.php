<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Controlador de Usuarios
|--------------------------------------------------------------------------
|
| Este es el controlador de Usuarios de Ncai Auth.
|
|
*/

class Users extends CI_Controller {

    ###############################
    # CONSTRUCTOR DEL CONTROLADOR #
    ###############################

    public function __construct() {
        parent::__construct();
        // Cargar Modelo
        $this->load->model('User');
        //Middleware
        $this->middleware->only_auth();
    }
    
    ###################################
    # MÉTODOS BÁSICOS DEL CONTROLADOR #
    ###################################

    // Llama una vista que muestra todos los recursos
    public function index() {
        $this->middleware->only_permission(PERM['sadmin'],'No tienes los permisos suficientes para realizar esta acción.' ,'users/show/'.$this->session->userdata('id'));
        $data['users'] = $query = $this->User->read();
        $data['title'] = 'Listado de Usuarios';
        $data['description'] = 'Aquí puedes ver una lista de todos los usuarios.';
        $this->middleware->renderview('users/index', $data);
    }

    // Llama una vista que muestra un recurso por id
    public function show($id) {
        $query = $this->User->read('users', ['id' => $id]);
        $data['user'] = $query;
        $data['title'] = 'Perfil de Usuario';
        $data['description'] = 'Aquí puedes ver el perfil de Usuario';
        $this->middleware->renderview('users/show', $data);
    }

    // Llama una vista con un formulario para crear un recurso nuevo
    public function new() {
        $data['title'] = 'Nuevo Usuario';
        $data['description'] = 'Aquí puedes crear un nuevo usuario.';
        $this->middleware->renderview('users/new', $data);
    }

    // Ejecuta el proceso para crear un nuevo recurso
    public function create() {
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        if ($this->session->userdata('permissions') & PERM['admin']) {
            // TODO: Crear de su propia empresa
        }
        // Valido los datos
        $this->form_validation->set_rules('name1', 'nombre', 'trim|required|min_length[1]|max_length[20]');
        $this->form_validation->set_rules('lastname1', 'apellido', 'trim|required|min_length[1]|max_length[20]');
        if (config_item('register_with_username')) {
             $this->form_validation->set_rules('username', 'nombre de usuario', 'trim|required|min_length[5]|max_length[15]|is_unique[users.username]');
        }
        $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email|is_unique[users.email]');
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
        // TODO: Chequear que un usuario de menos permisos no cree uno mayor
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
        // Hago la query
        $query = $this->User->create('users', $data);
        // La respuesta
        if (!$query) {
            $this->middleware->response('Imposible crear usuario. Intente más tarde.', 'error');
        } else {
            $data['users'] = $this->User->read();
            $this->middleware->response('Usuario creado correctamente', 'success', 'users/index', $data);
        }
    }

    // Llama a una vista con un formulario para editar un recurso existente
    public function edit($id) {
        $data['user'] = $this->User->read('users', ['id' => $id]);
        $data['title'] = 'Editar Usuario';
        $data['description'] = 'Aquí puedes editar un nuevo usuario.';
        $this->middleware->renderview('users/edit', $data);
    }

    // Ejecuta el proceso para editar un recurso existente
    public function update($id) {
        // TODO: Agregar los nuevos campos del edit al formulario
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
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
        if(!$this->form_validation->run()) {
            $this->form_validation->set_error_delimiters('', '');
            $this->middleware->response(validation_errors(), 'error');
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
        // TODO: Chequear que un usuario de menos permisos no edite uno mayor
        $data['name1'] = $this->input->post('name1');
        $data['lastname1'] = $this->input->post('lastname1');
        $data['email'] = $this->input->post('email');
        $query = $this->User->update('users', $data, ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible actualizar los datos. Intente más tarde.', 'error');
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                $data['user'] = $this->User->read('users', ['id' => $id]);
                $this->middleware->response('Usuario actualizado correctamente', 'success', 'users/show', $data); 
            } else {
                $data['users'] = $this->User->read();
                $this->middleware->response('Usuario actualizado correctamente', 'success', 'users/index', $data);
            }
        }
    }

    // Ejecuta el proceso para borrar un recurso existente
    public function destroy($id) {
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes eliminar tu propio usuario', 'error');
        }
        $query = $this->User->delete('users', ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible eliminar el usuario. Intente más tarde.', 'error');
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                $data['user'] = $this->User->read('users', ['id' => $id]);
                $this->middleware->response('Usuario eliminado correctamente', 'success', 'users/show', $data); 
            } else {
                $data['users'] = $this->User->read();
                $this->middleware->response('Usuario eliminado correctamente', 'success', 'users/index', $data);
            }
        }
    }

    #######################################
    # MÉTODOS ESPECÍFICOS DEL CONTROLADOR #
    #######################################

    // Bloquea un usuario
    public function lock($id) {
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes bloquear tu propio usuario.', 'error');
        }
        $data['is_locked'] = 1;
        $query = $this->User->update('users', $data, ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible bloquear el usuario. Intente más tarde.', 'error');
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                $data['user'] = $this->User->read('users', ['id' => $id]);
                $this->middleware->response('Usuario bloqueado correctamente', 'success', 'users/show', $data); 
            } else {
                $data['users'] = $this->User->read();
                $this->middleware->response('Usuario bloqueado correctamente', 'success', 'users/index', $data);
            }
        }
    }

    // Desbloquea un usuario
    public function unlock($id) {
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        $data['is_locked'] = 0;
        $query = $this->User->update('users', $data, ['id' => $id]);
        if (!$query) {
            $this->middleware->response('Imposible desbloquar el usuario. Intente más tarde.', 'error');
        } else {
            if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                $data['user'] = $this->User->read('users', ['id' => $id]);
                $this->middleware->response('Usuario desbloqueado correctamente', 'success', 'users/show', $data);
            } else {
                $data['users'] = $this->User->read();
                $this->middleware->response('Usuario desbloqueado correctamente', 'success', 'users/index', $data);
            }
        }
    }

    // Reset Password
    public function passwd_reset($id) {
        $this->middleware->only_permission(PERM['sadmin']|PERM['admin'],'No tienes los permisos suficientes para realizar esta acción.');
        // Que no se bloquee a sí mismo
        if ($this->session->userdata('id') == $id) {
            $this->middleware->response('No puedes reestablecer la contraseña de tu propio usuario.', 'error');
        }
        // Genero un token
        $token = bin2hex(random_bytes(30));
        $query = $this->User->update('users', ['forgotten_password_code' => $token], ['id' => $id]);
        $user = $this->User->read('users', ['id' => $id]);
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
                $msg = 'Hemos envíado un correo electrónico con un enlace al usuario seleccionado para que pueda elegir una nueva conraseña. No olvides comentarle que revise su carpeta de spam.';
                if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                    $data['user'] = $this->User->read('users', ['id' => $id]);
                    $this->middleware->response($msg, 'success', 'users/show', $data); 
                } else {
                    $data['users'] = $this->User->read();
                    $this->middleware->response($msg, 'success', 'users/index', $data);
                }
            }
        }
    }

    // Password Change
    public function passwd_change($id) {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            // Cargar la vista
            $data['user'] = $this->User->read('users', ['id' => $id]);
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
            $query = $this->User->read('users', ['id' => $id]);
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
                    if (strpos($_SERVER['HTTP_REFERER'], 'show') !== false) {
                        $data['user'] = $this->User->read('users', ['id' => $id]);
                        $this->middleware->response('Contraseña cambiada exitosamente.', 'success', 'users/show', $data); 
                    } else {
                        $data['users'] = $this->User->read();
                        $this->middleware->response('Contraseña cambiada exitosamente.', 'success', 'users/index', $data);
                    }
                }
            }
        } else {
            die('Inavlid Request');
        }
    }

    // Cambia la imagen de perfil de un usuario
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