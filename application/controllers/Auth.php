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
        // Compara las versiones de PHP
        if (version_compare(PHP_VERSION, '7.0') == -1) {
            echo 'Necesitas PHP 7.0 o superior para utilizar Ncai Auth for CI.';
            echo '<br>';
            echo 'No es tan dificil de instalar. Puedes ir a <a href="#" target="_blank">nuestra documentación</a> para encontrar instrucciones.';
            die;
        }
        // Carga las librerías requeridas
        //$this->load->library('database');
        //$this->load->library('form_validation');
        //$this->load->library('session');
        
        // Cargar el Archivo de Configuración
        $this->config->load('auth');

        // Si la tabla Users no existe, la crea.
        if ($this->config->item('auto_install_db') && !$this->db->table_exists('users')) {
            $this->load->library("migration");
            $this->migration->version(1);
        }
        // Cargar el modelo User y LoginAttempt, porque este controlador realiza acciones asociadas a usuarios.
        $this->load->model('User');
        $this->load->model('LoginAttempt');
    }

    public function index() {
        $data['title'] = 'Inicio';
        $data['content'] = 'auth/home';
        $this->load->view('auth/layouts/main', $data);
    }

    ##############################
    # MÉTODO DE INICIO DE SESIÓN #
    ##############################

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
                    $query = $this->User->read('users', ['activation_code' => $this->input->get('activate')]);
                    // Si la query falla, se manda un mensaje de error
                    if (!$query) {
                        $this->session->set_flashdata('error', 'Código de activación inválido o inexistente.');
                        redirect('auth/login');
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
                // Chequea si inicia sesión con un nombre de usuario o un email y valida.
                if (strpos($this->input->post('login'), '@') !== false ) {
                    $this->form_validation->set_rules('login', 'email', 'trim|required|min_length[5]|max_length[40]|valid_email');
                    $data['email'] = $this->input->post('login');                
                } else {
                    $this->form_validation->set_rules('login', 'username', 'trim|required');
                    $data['username'] = $this->input->post('login');
                }
                $this->session->set_flashdata('login', $this->input->post('login'));
                $this->form_validation->set_rules('passwd', 'password', 'trim|required');
                // Si el formulario no es válido, se devuelve a la vista anterior con un error.
                if(!$this->form_validation->run()) {
                    $this->form_validation->set_error_delimiters('', '');
                    $this->session->set_flashdata('error', validation_errors());
                    redirect('auth');
                // Si el formulario es válido, se inicia el proceso de inicio de sesión
                } else {
                    // Se consulta a la base de datos por el usuario
                    $query = $this->User->read('users', $data);
                    // Si no existe el usuario, se devuelve error
                    if(!$query) {
                        $this->session->set_flashdata('error', 'Usuario o contraseña incorrecto.');
                        redirect('auth/login');
                    // Se hashea el password.
                    } else {
                        if ($this->config->item('use_salt')) {
                            $pass = $query->salt.$this->input->post('passwd');
                        } else {
                            $pass = $this->input->post('passwd');
                        }
                        // Chequea si el usuario está activo.
                        if (!$query->is_active) {                            
                            $timediff = $query->blocked_time + $this->config->item('blocking_time');
                            if (!$query->blocked_time) {
                                $this->session->set_flashdata('error', 'Tu usuario no está activo.');
                                redirect('auth/login');
                            } elseif ($timediff > time() ) {
                                $this->session->set_flashdata('error', 'Aún no puedes iniciar sesión. Por favor espera un poco más.');
                                redirect('auth/login');
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
                        if (password_verify($pass, $query->password)) {
                            // Si la función está activada, guarda el intento fallido del usuario.
                            if ($this->config->item('save_failed_attempt')) {
                                $this->failed_attempt($query->id);
                            }
                            // Chequea si el bloqueo por intentos fallidos está activo
                            if ($this->config->item('attempts_to_block') > 0) {
                                // Chequea si el usuario ha tenido múltiples intentos de inicio fallidos
                                $left = $this->check_failed_attempts($query->id);
                                if (!$left) {
                                    // Resetear los intentos de login fallidos
                                    $this->clean_failed_attempts($query->id);
                                    $this->session->set_flashdata('error', 'Tu cuenta ha sido desactivada temporalmente por múltiples intentos fallidos de inicio de sesión. Debes esperar '.gmdate("i:s", $this->config->item('blocking_time')).' para poder iniciar sesión otra vez. Si has olvidado tu contraseña, puedes cambiarla en la pantalla de inicio de sesión.');
                                    redirect('auth/login');
                                } else {
                                    $this->session->set_flashdata('warning', 'Contraseña incorrecta. Tienes '.$left.' intento(s) restante(s).');
                                    redirect('auth/login');
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Contraseña incorrecta.');
                                redirect('auth/login');
                            }
                        } else {
                            // Construye el array con los datos de usuario
                            $data = array(
                                'id'                    => $query->id,
                                'name1'                 => $query->name1,
                                'lastname1'             => $query->lastname1,
                                'email'                 => $query->email,
                                'permissions'           => decbin($query->permissions),
                                'lastlogin_ip'          => $query->lastlogin_ip,
                                'lastlogin_time'        => $query->lastlogin_time,
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
                            $this->session->set_flashdata('success', 'Has iniciado sesión exitosamente. La última vez que lo hiciste fue el '.strftime('%A, %d de %B de %Y a las %H:%M', $this->session->userdata('lastlogin_time')).' desde '.$this->session->userdata('lastlogin_ip'));    
                            }
                            $this->session->set_flashdata('success', 'Has iniciado sesión exitosamente.');    
                            
                            // Chequea si smart redirect está activado. y si hay un redirect
                            if ($this->config->item('smart_redirect') && $this->session->userdata('redirect')) {
                                redirect($this->session->userdata('redirect'));
                            // Si no, se manda a la vista normal.
                            } else {
                                redirect($this->config->item('logged_in_controller'));
                            }
                        }
                    }
                }
            // Si es otro tipo de request, se devuelve un 400 (Bad Request) 
            } else {
                http_response_code(400);
            }
        }
    }

    ##############################
    # MÉTODO DE CIERRE DE SESIÓN #
    ##############################
    
    public function logout() {
        // Si el usuario no tiene sesión iniciada, no queremos que use este método.
        if(!$this->session->userdata('logged_in')) {
            redirect($this->config->item('/'));
        } else {
            $data = ['id', 'name1', 'lastname1', 'email', 'username', 'permissions', 'lastlogin_ip', 'lastlogin_time', 'logged_in'];
            $this->session->unset_userdata($data);;
            $this->session->set_flashdata('success', 'Has cerrado sesión exitosamente.');
            redirect('/');
        }
    }

    #################################
    # MÉTODO DE REGISTRO DE USUARIO #
    #################################

    // Registra un usuario en el sistema
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
                    $this->form_validation->set_rules('name1', 'name', 'trim|required|min_length[1]|max_length[20]');
                    $this->form_validation->set_rules('lastname1', 'lastname', 'trim|required|min_length[1]|max_length[20]');
                    $this->session->set_flashdata('name1', $this->input->post('name1'));
                    $this->session->set_flashdata('lastname1', $this->input->post('lastname1'));
                } elseif ($this->config->item('register_with_username')) {
                    $this->form_validation->set_rules('username', 'username', 'trim|required|min_length[5]|max_length[15]|is_unique[users.username]');
                    $this->session->set_flashdata('username', $this->input->post('username'));
                }
                $this->form_validation->set_rules('email', 'email', 'trim|required|min_length[5]|max_length[40]|valid_email|is_unique[users.email]');
                $this->session->set_flashdata('email', $this->input->post('email'));
                $this->form_validation->set_rules('passwd', 'password', 'trim|required|min_length[5]|max_length[20]');
                $this->form_validation->set_rules('passwd2', 'password confirm', 'trim|required|min_length[5]|max_length[20]|matches[passwd]');
                // Si el formulario no es válido, mensaje de error
                if(!$this->form_validation->run()) {
                    $this->form_validation->set_error_delimiters('', '');
                    $this->session->set_flashdata('error', 'Hubo algunos problemas con tu formulario: '.validation_errors());
                    redirect('auth/register');
                // Verificar si tiene activado login por términos
                } elseif ($this->config->item('register_with_terms') && !$this->input->post('terms')) {
                    $this->session->set_flashdata('error', 'Debes aceptar los términos de servicio.');
                    redirect('auth/register');
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
                        $this->session->set_flashdata('error', 'Hubo un problema procesando tu registro. Por favor intenta más tarde.');
                        redirect('auth/register');
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
                            $this->session->set_flashdata('warning', 'Hemos guardado tus datos exitosamente, pero por alguna razón no hemos podido enviarte un correo. Puedes activar tu cuenta haciendo click en <a href="'.$data['url'].'">este link</a>.');
                            redirect('auth');
                        } else {
                        $this->session->set_flashdata('success', 'The has registrado exitosamente. Te hemos mandado un correo electrónico con instrucciones para activar tu cuenta.');
                        redirect('auth/login');
                        }
                    } else {
                        $this->session->set_flashdata('success', 'The has registrado exitosamente. Ya puedes iniciar sesión.');
                        redirect('auth/login');
                    }
                }
            // Si es otro tipo de request, se devuelve un 400 (Bad Request)
            } else {
                http_response_code(400);
            }
        }
    }

    ##################################
    # MÉTODO DE RECUPERAR CONTRASEÑA #
    ##################################

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
                        $this->session->set_flashdata('error', 'Por favor, ingresa un campo válido de correo electrónico.');
                        redirect('auth/password_reset');
                    // Si la validación es exitosa, se consulta la base de datos por el email.
                    } else {
                        $email = $this->input->post('email');
                        // Si el email no existe en la base de datos, se redirige al método con error.
                        if (!$this->User->read('users', ['email' => $email])) {
                            $this->session->set_flashdata('error', 'No existe una cuenta asociada a esta dirección de correo.');
                            redirect('auth/password_reset');
                        // Si el email existe, genero el token.
                        } else {
                            $token = bin2hex(random_bytes(30));
                            $query = $this->User->update('users', ['forgotten_password_code' => $token], ['email' => $email]);
                            // Si la query falla, alertamos al usuario.
                            if(!$query) {
                                $this->session->set_flashdata('error', 'Algo salió mal. Por favor, intenta nuevamente.');
                                redirect('auth/password_reset');
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
                                    $this->session->set_flashdata('error', 'No hemos podido enviarte un correo electrónico. Intenta nuevamente.');
                                    redirect('auth');
                                // Si se envía bien, terminamos el proceso con un mensaje de confirmación.
                                } else {
                                    $this->session->set_flashdata('success', 'Te hemos envíado un correo electrónico con un enlace para recuperar tu contraseña. No olvides revisar tu carpeta de spam si no ha llegado.');
                                    redirect('auth');
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
                        $this->session->set_flashdata('error', 'Hubo algunos problemas: '.validation_errors());
                        redirect('auth/password_reset/'.$this->input->post('token'));
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
                            $this->session->set_flashdata('error', 'Algo salió mal. Por favor, intenta nuevamente.');
                            redirect('auth');
                        // Si todo sale bien, a la pantalla de login y con mensaje de éxito.
                        } else {
                            $this->session->set_flashdata('success', 'Tu contraseña fue existosamente cambiada. Ahora puedes iniciar sesión.');
                            redirect('auth');
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

    #################################
    # FUNCIONES DE APOYO PROTEGIDAS #
    #################################

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