<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Sistema de Login de Ncai SpA
|--------------------------------------------------------------------------
|
| Este es el controlador para el sistema de Login desarrollado por Ncai SpA.
| No tiene la estructura propia de un controlador RESTful, porque este 
| controlador no representa un recurso, sino un conjunto de métodos y acciones
| que validan usuarios tanto para iniciar sesión como para cerrarla, recuperar
| contraseña y todo lo demás.
|
| Idealmente, no deberías tocar ninguno de estos métodos, salvo que necesites
| agregar algunos campos personalizados debido a la naturaleza propia de tu
| modelo de datos de usuario. Sin embargo, siempre recomiendo agregar datos
| extra en una tabla aparte. Aunque, si te sientes capaz de no romper nada,
| tienes libertad para modificar lo que quieras.
|
| Autor: Matías Navarro Carter
| Email: mnavarrocarter@gmail.com
| Licencia: MIT
|
|
*/

class Auth extends CI_Controller {

    ###############################
    # CONSTRUCTOR DEL CONTROLADOR #
    ###############################

    public function __construct() {
        parent::__construct();
        // Chequeo de Requerimientos
        $errormsg = NULL;
        // Compara las versiones de PHP
        if (version_compare(PHP_VERSION, '7.0') == -1) {
            $errormsg .= '<p>Necesitas PHP 7.0 o superior para utilizar Ncai Auth for CI.</p>';
        }
        // Chequear mod_rewrite
        if (!in_array('mod_rewrite', apache_get_modules())) {
            $errormsg .= '<p>Necesitas activar mod_rewrite en Apache 2. <code>sudo a2enmod rewrite</code>.</p>';
        }
        // Chequear mod_env
        if (!in_array('mod_env', apache_get_modules())) {
            $errormsg .= '<p>Necesitas activar mod_env en Apache 2. <code>sudo a2enmod env</code>.</p>';
        }
        if ($errormsg) {
            $this->session->set_flashdata('error', $errormsg);
        }
        // Cargar Modelos
        $this->load->model('User');
        $this->load->model('Group');
        $this->load->model('LoginAttempt');
        // Autoinstalar la base de
        if ($this->config->item('auto_install_db') && !$this->db->table_exists('users')) {
            $this->load->library("migration");
            $this->migration->version(1);
        }
    }

    public function index() {
        $data['title'] = 'Inicio';
        $data['content'] = 'auth/home';
        $this->load->view('auth/layouts/main', $data);
    }

/*
|--------------------------------------------------------------------------
| Método de Inicio de Sesión
|--------------------------------------------------------------------------
| Este método ejecuta todo el proceso de inicio de sesión. Sólo pueden
| acceder a este método los usuarios que no tienen iniciada sesión. Cuando
| el tipo de request es un GET, y viene con un campo de activate, activa
| el usuario con un código de activación. Si no trae activate, llama a la
| vista de inicio. Si el tipo de request es POST, entonces inicia el proceso
| de inicio de sesión (validando, realizando queries, y ordenando el array
| de los datos de usuario).
|
*/
    public function login() {
        // Si el usuario tiene sesión iniciada, no queremos que use este método. Lo pasamos al controlador post login.
        if ($this->session->userdata('logged_in')) {
            redirect($this->config->item('logged_in_controller'));
        } else {
            // Si es un GET request, se pasa a la vista de inicio de sesión
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                // Si trae un activation code, se manda a validarlo.
                if ($this->input->get('activate')) {
                    // Se pregunta a la db por un usuario con el codigo de activación enviado
                    $query = $this->User->read('users', ['users.activation_code' => $this->input->get('activate')]);
                    // Si la query falla, se manda un mensaje de error
                    if (!$query) {
                        $this->session->set_flashdata('error', 'Código de activación inválido o inexistente.');
                        //$this->output->enable_profiler(TRUE);
                        //redirect('auth/login');
                    // Si la query es exitosa, entonces se elimina el codigo de activación y se activa el usuario.
                    } else {
                        if (!$this->activate_user($query->id)) {
                            $this->session->set_flashdata('error', 'Hubo un problema intentando activar tu cuenta. Por favor intenta más tarde.');
                            redirect('auth/login');
                        } elseif ($this->config->item('send_welcome_email')) {
                            // Se envía el email si todo sale bien.
                            $data['url'] = base_url();
                            $data['button'] = config_item('email_welcome_button_text');
                            $data['paragraphs'] = config_item('email_welcome_paragraphs');
                            $data['title'] = config_item('email_welcome_title');
                            $body = $this->load->view('auth/email/content', $data, true);
                            $subject = $this->config->item('email_welcome_subject');
                            // Mandar el correo con el link de activación.
                            $this->send_email($query->email, $subject, $body);
                        }
                        $this->session->set_flashdata('success', 'Tu cuenta ha sido activada exitosamente. Ahora puedes iniciar sesión.');
                        redirect('auth/login');
                    }
                // Si no viene ningún parámetro GET, se manda a la vista.
                } else {
                    $csrf = bin2hex(random_bytes(32));
                    $this->session->set_userdata('csrf', $csrf);
                    $data['title'] = 'Inciar Sesión';
                    $data['content'] = 'auth/login';
                    $data['csrf'] = $csrf;
                    $this->load->view('auth/layouts/main', $data); 
                }
            // Si es un POST request, se activa el proceso de iniciar sesión
            } elseif ($this->input->server('REQUEST_METHOD') == 'POST') {
                // Chequea si el formulario no viene de un Cross Reference
                if ($this->config->item('csrf_protection')) {
                     if (hash_equals($this->session->userdata('csrf'), $this->input->post('csrf'))) {
                        // Paso
                    } else {
                        http_response_code(406);
                        echo 'Cross-Origin Request Detected';
                        die;
                    }
                }
                // Chequea si inicia sesión con un nombre de usuario o un email y valido.
                if (strpos($this->input->post('login'), '@') !== false ) {
                    $this->form_validation->set_rules('login', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email');
                    $data['email'] = $this->input->post('login');                
                } else {
                    $this->form_validation->set_rules('login', 'nombre de usuario', 'trim|required');
                    $data['username'] = $this->input->post('login');
                }
                $this->session->set_flashdata('login', $this->input->post('login'));
                $this->form_validation->set_rules('passwd', 'contraseña', 'trim|required');
                // Si el formulario no es válido, se devuelve a la vista anterior con un error.
                if(!$this->form_validation->run()) {
                    $this->form_validation->set_error_delimiters('', '');
                    $this->middleware->response(validation_errors(), 'error');
                // Si el formulario es válido, se inicia el proceso de inicio de sesión
                } else {
                    // Se consulta a la base de datos por el usuario
                    $query = $this->User->read('users', $data);
                    // Si no existe el usuario, se devuelve error
                    if(!$query) {
                        $msg = 'Nombre de usuario o correo electrónico incorrecto.';
                        $this->middleware->response($msg, 'error');
                    // Se hashea el password.
                    } else {
                        if ($this->config->item('use_salt')) {
                            $pass = $query->salt.$this->input->post('passwd');
                        } else {
                            $pass = $this->input->post('passwd');
                        }
                        // Chequea si el usuario está bloqueado
                        if ($query->is_locked) {
                            $msg = 'Tu usuario se encuentra bloqueado. Ponte en contacto con un administrador de sistema para desbloquearlo.';
                            $this->middleware->response($msg, 'error');
                        }
                        //verifica si el usuario es superadmin
                        if(!$this->session->userdata('permissions') & PERM['sadmin']){
                        // Chequea si el grupo está bloqueado
                            if (!$this->Group->read('groups', ['id' => $query->group_id])->is_active) {
                                $msg = 'Tu grupo tiene su cuenta suspendida, por lo que no puedes iniciar sesión. Ponte en contacto con el administrador de sistema.';
                                $this->middleware->response($msg, 'error');
                            }
                        }
                        // Chequea si el usuario está activo.
                        if (!$query->is_active) {                            
                            $timediff = $query->blocked_time + $this->config->item('blocking_time');
                            if (!$query->blocked_time) {
                                $msg = 'Tu usuario no está activo.';
                                $this->middleware->response($msg, 'error');
                            } elseif ($timediff > time() ) {
                                $msg = 'Aún no puedes iniciar sesión. Por favor espera un poco más.';
                                $this->middleware->response($msg, 'error');
                            } elseif ($timediff < time() ) {
                                // Borrar el blocked time y activar el usuario
                                $data = [
                                    'blocked_time' => NULL,
                                    'is_active' => 1
                                ];
                                $this->User->update('users', $data, ['id' => $query->id]);
                            }                        
                        } 
                        // Chequea el password
                        if (!password_verify($pass, $query->password)) {
                            // Si la función está activada, guarda el intento fallido del usuario.
                            if ($this->config->item('save_failed_attempt')) {
                                $this->failed_attempt($query->id);
                            }
                            // Chequea si el bloqueo por intentos fallidos está activo
                            if ($this->config->item('attempts_to_block') > 0 && $this->config->item('save_failed_attempt')) {
                                // Chequea si el usuario ha tenido múltiples intentos de inicio fallidos
                                $left = $this->check_failed_attempts($query->id);
                                if (!$left) {
                                    // Resetear los intentos de login fallidos
                                    $this->clean_failed_attempts($query->id);
                                    $msg = 'Tu cuenta ha sido desactivada temporalmente por múltiples intentos fallidos de inicio de sesión. Debes esperar '.gmdate("i:s", $this->config->item('blocking_time')).' para poder iniciar sesión otra vez. Si has olvidado tu contraseña, puedes cambiarla en la pantalla de inicio de sesión.';
                                    $this->middleware->response($msg, 'error');
                                } else {
                                    $msg = 'Contraseña incorrecta. Tienes '.$left.' intento(s) restante(s).';
                                    $this->middleware->response($msg, 'error');
                                }
                            } else {
                                $msg = 'Contraseña incorrecta.';
                                $this->middleware->response($msg, 'error');
                            }
                        } else {
                            // Construye el array con los datos de usuario
                            $data = array(
                                'id'                    => $query->id,
                                'name1'                 => $query->name1,
                                'lastname1'             => $query->lastname1,
                                'email'                 => $query->email,
                                'permissions'           => $query->permissions,
                                'group_id'              => $query->group_id,
                                'lastlogin_ip'          => $query->lastlogin_ip,
                                'lastlogin_time'        => $query->lastlogin_time,
                                'avatar'                => $query->avatar_url,
                                'logged_in'             => TRUE
                            );
                            // Recordar sesión
                                if ($this->input->post('remember')) {
                                $data['remember_me'] = $this->input->post('remember');
                            }
                            // Guarda el valor del login actual en la base de datos.
                            $this->last_login($query->id);
                            // Coloca los datos de usuario en la sesión
                            $this->session->set_userdata($data);
                            // Limpia los intentos fallidos de login de ese usuario si se cuentan los intentos fallidos
                            if ($this->config->item('attempts_to_block') > 0) {
                                $this->clean_failed_attempts($data['id']);
                            }
                            if ($this->config->item('save_failed_attempt')) {
                            // Coloca flashdata    
                            }
                            // Chequea si smart redirect está activado. y si hay un redirect
                            if ($this->config->item('smart_redirect') && $this->session->userdata('redirect')) {
                                $redirect = base_url().$this->session->userdata('redirect');
                            // Si no, se manda a la vista normal.
                            } else {
                                $redirect = base_url().$this->config->item('logged_in_controller');
                            }
                            $msg = 'Has iniciado sesión exitosamente. La última vez que lo hiciste fue el '.strftime('%A, %d de %B de %Y a las %H:%M', $this->session->userdata('lastlogin_time')).' desde '.$this->session->userdata('lastlogin_ip');
                            $this->middleware->response($msg, 'success', $redirect);
                        }
                    }
                }
            // Si es otro tipo de request, se devuelve un 400 (Bad Request) 
            } else {
                http_response_code(400);
            }
        }
    }

/*
|--------------------------------------------------------------------------
| Método de Cierre de Sesión
|--------------------------------------------------------------------------
| Este sencillo método quita la información de la sesión.
|
*/
    public function logout() {
        // Si el usuario no tiene sesión iniciada, no queremos que use este método.
        if(!$this->session->userdata('logged_in')) {
            redirect($this->config->item('/'));
        } else {
            $data = ['id', 'avatar', 'name1', 'lastname1', 'email', 'username', 'permissions', 'lastlogin_ip', 'lastlogin_time', 'logged_in'];
            $this->session->unset_userdata($data);;
            $msg = 'Has cerrado sesión exitosamente.';
            $this->middleware->response($msg, 'success', 'auth');
        }
    }

/*
|--------------------------------------------------------------------------
| Método de Registro
|--------------------------------------------------------------------------
| Este método registra a un usuario en el sistema. Puede recibir tanto
| POST como GET requests. Cuando viene con un GET, carga el formulario de 
| inicio de sesión. Cuando manda el POST, ejecuta todas las funciones para
| validar el formulario, hacer chequeos internos, ordenar los datos y 
| colocarlos en la base de datos. Las respuestas se dan automaticamente
| para llamadas ajax o convencionales.
|
*/

    public function register() {
        // Si el módulo de registro está desactivado, bloqueamos el acceso a este método.
        if (!$this->config->item('activate_registration')) {
            redirect('auth');
        // Si el usuario tiene sesión iniciada, no queremos que use este método. Lo pasamos al controlador post login.
        } elseif ($this->session->userdata('logged_in')) {
            redirect($this->config->item('logged_in_controller'));
        } else {
            // Si es un método GET, se manda a la vista de registro.
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                $csrf = bin2hex(random_bytes(32));
                $this->session->set_userdata('csrf', $csrf);
                $data['csrf'] = $csrf;
                $data['title'] = 'Registro de Usuario';
                $data['content'] = 'auth/register';
                $this->load->view('auth/layouts/main', $data);
            // Si es un método POST, se realiza la acción de registro.
            } elseif ($this->input->server('REQUEST_METHOD') == 'POST') {
                // Chequea si el formulario no viene de un Cross Reference
                if ($this->config->item('csrf_protection')) {
                     if (hash_equals($this->session->userdata('csrf'), $this->input->post('csrf'))) {
                        // Paso
                    } else {
                        http_response_code(406);
                        echo 'Cross-Origin Request Detected';
                        die;
                    }
                }
                // Valido el formulario de acuerdo al tipo de registro
                if ($this->config->item('register_with_name')) {
                    $this->form_validation->set_rules('name1', 'nombre', 'trim|required|min_length[1]|max_length[20]');
                    $this->form_validation->set_rules('lastname1', 'apellido', 'trim|required|min_length[1]|max_length[20]');
                } elseif ($this->config->item('register_with_username')) {
                    $this->form_validation->set_rules('username', 'nombre de usuario', 'trim|required|min_length[5]|max_length[15]|is_unique[users.username]');
                }
                $this->form_validation->set_rules('email', 'correo electrónico', 'trim|required|min_length[5]|max_length[40]|valid_email|is_unique[users.email]');
                $this->form_validation->set_rules('passwd', 'contraseña', 'trim|required|min_length[5]|max_length[20]');
                $this->form_validation->set_rules('passwd2', 'confirmación de contraseña', 'trim|required|min_length[5]|max_length[20]|matches[passwd]');
                // Si el formulario no es válido, mensaje de error
                if(!$this->form_validation->run()) {
                    $this->form_validation->set_error_delimiters("\n", '');
                    $msg = 'Hubo algunos problemas con tu formulario: '."\n".validation_errors();
                    $this->middleware->response($msg, 'error');
                // Verificar si tiene activado login por términos
                } elseif ($this->config->item('register_with_terms') && !$this->input->post('terms')) {
                    $msg = 'Debes aceptar los términos de servicio.';
                    $this->middleware->response($msg, 'error');
                // SI es válido, comienzo a construir los datos.
                } else {
                    // Salteo y hasheo de password.
                    if ($this->config->item('use_salt')) {
                        $data['salt'] = uniqid(mt_rand(), true);
                        $data['password'] = password_hash($data['salt'].$this->input->post('passwd'), PASSWORD_BCRYPT);
                    } else {
                        $data['password'] = password_hash($this->input->post('passwd'), PASSWORD_BCRYPT);
                    }
                    // Poner los permisos por defecto
                    $data['permissions'] = $this->config->item('default_permissions');
                    // Creo un código de activación
                    if ($this->config->item('activation_email')) {
                        $data['activation_code'] = hash('ripemd160', mt_rand());
                        $data['is_active'] = 0;
                    } else {
                        $data['is_active'] = 1;
                    }
                    // Pongo el valor de bloqueo
                    $data['is_locked'] = 0;
                    // Obtener los campos
                    if ($this->config->item('register_with_name')) {
                        $data['name1'] = $this->input->post('name1');
                        $data['lastname1'] = $this->input->post('lastname1');
                    } elseif ($this->config->item('register_with_username')) {
                        $data['username'] = $this->input->post('username');
                    }
                    $data['email'] = $this->input->post('email');
                    // Pasar el array de datos al modelo y ejecutarlo.
                    $query = $this->User->create('users', $data);
                    // Redireccionar al usuario dependiendo del resultado
                    if (!$query) {
                        $msg = 'Hubo un problema procesando tu registro. Por favor intenta más tarde.';
                        $this->middleware->response($msg, 'error');
                    // Chequea si la activación de cuenta por email está configurada.
                    } elseif ($this->config->item('activation_email')) {
                        // Se envía el email si todo sale bien.
                        $data['url'] = base_url('auth/login?activate='.$data['activation_code']);
                        $data['button'] = config_item('email_activation_button_text');
                        $data['paragraphs'] = config_item('email_activation_paragraphs');
                        $data['title'] = config_item('email_activation_title');
                        $body = $this->load->view('auth/email/content', $data, true);
                        $subject = $this->config->item('email_activation_subject');
                        // Mandar el correo con el link de activación.
                        if (!$this->send_email($data['email'], $subject, $body)) {
                            $msg = 'Hemos guardado tus datos exitosamente, pero por alguna razón no hemos podido enviarte un correo. Puedes activar tu cuenta haciendo click en <a href="'.$data['url'].'">este link</a>.';
                            $this->middleware->response($msg, 'warning');
                        } else {
                            $msg = 'The has registrado exitosamente. Te hemos mandado un correo electrónico con instrucciones para activar tu cuenta.';
                            $this->middleware->response($msg, 'success');
                        }
                    } else {
                        $msg = 'The has registrado exitosamente. Redirigiendo al inicio de sesión...';
                        $this->middleware->response($msg, 'success', base_url().'auth');
                    }
                }
            // Si es otro tipo de request, se devuelve un 400 (Bad Request)
            } else {
                http_response_code(400);
            }
        }
    }

/*
|--------------------------------------------------------------------------
| Método de Recuperar Contraseña
|--------------------------------------------------------------------------
| Este método recupera la contraseña para un usuario de sistema, ingresando
| un correo electrónico válido, que envía un correo electrónico al usuario
| con un link de cambio de contraseña. GET requests muestran las vistas. 
| POST requests realizan las acciones. Las respuestas son dadas de acuerdo 
| a Ajax o convencionales.
|
*/

    public function password_reset(string $token = NULL) {
        // Si esta función está desactivada, no permitimos el acceso al método.
        if (!$this->config->item('password_reset')) {
            redirect('auth');
        // Si el usuario tiene sesión iniciada, no queremos que use este método. Lo pasamos a la vista post login.
        } elseif ($this->session->userdata('logged_in')) {
            redirect($this->config->item('logged_in_controller'));
        } else {
            // Si es un método GET, se pasa.
            if ($this->input->server('REQUEST_METHOD') == 'GET') {
                // Si este GET no contiene parámetro $token, se manda la vista de email.
                if (!$token) {
                    $csrf = bin2hex(random_bytes(32));
                    $this->session->set_userdata('csrf', $csrf);
                    $data['csrf'] = $csrf;
                    $data['title'] = 'Recuperación de Contraseña';
                    $data['content'] = 'auth/password_reset_email';
                    $this->load->view('auth/layouts/main', $data);
                // Si este GET tiene token, se pasa.
                } else {
                    // Si el token no existe en la base de datos, se lanza error.
                    if(!$this->User->read('users', ['forgotten_password_code' => $token])) {
                        $msg = 'Token inválido o no existente.';
                        $this->session->set_flashdata('error', 'Token inválido o no existente.');
                        redirect('auth');
                    } else {
                        $csrf = bin2hex(random_bytes(32));
                        $this->session->set_userdata('csrf', $csrf);
                        $data['csrf'] = $csrf;
                        // Se pasa el token a la vista para un hidden input.
                        $data['token'] = $token;
                        $data['title'] = 'Cambio de Contraseña';
                        $data['content'] = 'auth/password_reset_new';
                        $this->load->view('auth/layouts/main', $data);
                    }
                }
            // Si es un método POST, se pasa.
            } elseif ($this->input->server('REQUEST_METHOD') == 'POST') {
                // Chequea si el formulario no viene de un Cross Reference
                if ($this->config->item('csrf_protection')) {
                     if (hash_equals($this->session->userdata('csrf'), $this->input->post('csrf'))) {
                        // Paso
                    } else {
                        http_response_code(406);
                        echo 'Cross-Origin Request Detected';
                        die;
                    }
                }
                // Si ese POST contiene un campo email, se pasa.
                if ($this->input->post('email')) {
                    // Se valida el email
                    $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[5]|max_length[40]|valid_email');
                    // Si la validación falla, se redirige a la este método con error.
                    if(!$this->form_validation->run()) {
                        $msg = 'Por favor, ingresa un campo válido de correo electrónico.';
                        $this->middleware->response($msg, 'error');
                    // Si la validación es exitosa, se consulta la base de datos por el email.
                    } else {
                        $email = $this->input->post('email');
                        // Si el email no existe en la base de datos, se redirige al método con error.
                        if (!$this->User->read('users', ['email' => $email])) {
                            $msg = 'No existe una cuenta asociada a esta dirección de correo.';
                            $this->middleware->response($msg, 'error');
                        // Si el email existe, genero el token.
                        } else {
                            $token = bin2hex(random_bytes(30));
                            $query = $this->User->update('users', ['forgotten_password_code' => $token], ['email' => $email]);
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
                                // Si el email no se envía bien, alertar al usuario.
                                if (!$this->send_email($email, $subject, $body)) {
                                    $msg = 'No hemos podido enviarte un correo electrónico. Intenta nuevamente.';
                                    $this->middleware->response($msg, 'error');
                                // Si se envía bien, terminamos el proceso con un mensaje de confirmación.
                                } else {
                                    $msg = 'Te hemos envíado un correo electrónico con un enlace para recuperar tu contraseña. No olvides revisar tu carpeta de spam si no ha llegado.';
                                    $this->middleware->response($msg, 'success', base_url().'auth');
                                }
                            }
                        }
                    }
                // Si existe un campo de contraseña 1 y 2, con el hidden token, se pasa.
                } elseif ($this->input->post('passwd') && $this->input->post('passwd2') && $this->input->post('token')) {
                    // Se valida que las contraseñas sean iguales y si está el token.
                    $this->form_validation->set_rules('passwd', 'contraseña', 'trim|required|min_length[5]|max_length[20]');
                    $this->form_validation->set_rules('passwd2', 'confirmación de contraseña', 'trim|required|min_length[5]|max_length[20]|matches[passwd]');
                    $this->form_validation->set_rules('token', 'token', 'trim|required');
                    // Si el formulario no es válido, se manda un mensaje de error y se redirige con el token.
                    if(!$this->form_validation->run()) {
                        $this->form_validation->set_error_delimiters('', '');
                        $msg = validation_errors();
                        $this->middleware->response($msg, 'error');
                    // Si es válido, se prepara la query
                    } else {
                        // Se chequea el token nuevamente y se devuelve el id del usuario.
                        $user = $this->User->read('users', ['forgotten_password_code' => $this->input->post('token')]);
                        // Luego, se encripta la contraseña si se usa salt o no.
                        if ($this->config->item('use_salt')) {
                            $data['salt'] = uniqid(mt_rand(), true);
                            $data['password'] = password_hash($data['salt'].$this->input->post('passwd'), PASSWORD_BCRYPT);
                        } else {
                            $data['password'] = password_hash($this->input->post('passwd'), PASSWORD_BCRYPT);
                        }
                        $data['forgotten_password_code'] = NULL;
                        $query = $this->User->update('users', $data, ['id' => $user->id]);
                        // Si la query falla, mensaje de error y de vuelta al login.
                        if (!$query) {
                            $msg = 'Algo salió mal. Por favor, intenta nuevamente.';
                            $this->middleware->response($msg, 'error');
                        // Si todo sale bien, a la pantalla de login y con mensaje de éxito.
                        } else {
                            $msg = 'Tu contraseña fue existosamente cambiada. Redirigiendo al login...';
                            $this->middleware->response($msg, 'success', base_url('auth'));
                        }
                    }
                // Si no, se devuelve un bad request.
                } else {
                    http_response_code(400);
                }
            // Otro bad request.
            } else {
                http_response_code(400);
            }
        }
    }

/*
|--------------------------------------------------------------------------
| Funciones de Apoyo
|--------------------------------------------------------------------------
| Las funciones de apoyo ayudan a las funciones principales a realizar
| tareas relativas a la autenticación. Son funciones que se requieren
| seguido y que se colocan aquí para no ocupar tanto espacio en el código.
| Además, están protegidas, por lo que no pueden ser llamadas desde una URI.
|
*/

    // Guarda el último login del usuario en la base de datos
    protected function last_login($id) {
        $data = [
            'lastlogin_ip'      =>  $_SERVER['REMOTE_ADDR'],
            'lastlogin_time'    =>  time(),
        ];
        $query = $this->User->update('users', $data, ['id' => $id]);
        return (object) $data;
    }

    // Envía un email con destinatario, cuerpo y asunto.
    protected function send_email($email, $subject, $body) {
        $this->load->library('email');
        $this->load->config('email');
        $this->email->set_newline("\r\n");
        $this->email->from($this->config->item('smtp_user'), $this->config->item('app_name'));
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($body);
        // Si el correo no se envió bien, pongo error
        return $this->email->send();
    }

    // Activa un usuario en la base de datos
    protected function activate_user($id) {
        $data = [
            'activation_code' => NULL,
            'is_active' => 1 
        ];
        $query = $this->User->update('users', $data, ['id' => $id]);
        return $query;
    }

    // Registra un intento fallido de login
    protected function failed_attempt($id) {
        $data = [
            'ip_address'    => $_SERVER['REMOTE_ADDR'],
            'user_id'       => $id,
            'status'        => 1
        ];
        $this->LoginAttempt->create('login_attempts', $data);
    }

    // Chequea si un usuario no sobrepasa el límite de intentos fallidos.
    protected function check_failed_attempts($id) {
        // Obtiene los logins fallidos de ese usuario, de acuerdo a las condiciones dadas.
        $data = [
            'user_id' => $id,
            'status' => 1,
            'created_at >' => time()-$this->config->item('failed_attempt_expire'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        ];
        $failed = $this->LoginAttempt->count($data);
        // Si es igual o mayor al número de intentos fallidos, se pasa al bloqueo.
        if ($failed >= $this->config->item('attempts_to_block')) {
            $data = [
                'blocked_time'  => time(),
                'is_active'     => 0
            ];
            $this->User->update('users', $data, ['id' => $id]);
            return false;
        // Si es menor, entonces se devuelven los intentos restantes.
        } else {
            $attempts = $this->config->item('attempts_to_block');
            $left = $attempts - $failed;
            return $left;
        }
    }

    // Limpia los intentos de login fallidos de un usuario
    protected function clean_failed_attempts($id) {
        $data['status'] = 0;
        $query = $this->LoginAttempt->update('login_attempts', $data, ['user_id' => $id]);
        return $query;
    }

}