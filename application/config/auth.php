<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| CONFIGURACIÓN
| -------------------------------------------------------------------
| Este arhivo contiene la configuración Ncai Auth for CI.
|
| Autor: Matías Navarro Carter
| Licencia: MIT
|
|
*/

// Opciones Generales
$config['auto_install_db'] = true;          // Auto-instalar la estructura de la base de datos.

// Opciones de Usuario
$config['activate_plan_module'] = false;    // Módulo de plan. TODO
$config['plan_default_value'] = 0;          // 0 es sin plan. TODO

// Opciones de Inicio de Sesión
$config['smart_redirect'] = true;           // Activa redireccionamiento inteligente. Ver Wiki para configurar.
$config['logged_in_controller'] = 'app';    // El controlador donde el sistema de login redirecciona

// Opciones de Registro
$config['activate_registration'] = true;    // Activar el registro de usuarios
$config['register_with_name'] = false;      // Pedir el nombre en el registro
$config['register_with_username'] = false;  // Pedir un nombre de usuario en el registro
$config['register_with_terms'] = true;      // Pedir aceptar los términos de servicio en el registro
$config['activation_email'] = false;        // Envía un email de confirmación para el registro.
$config['default_permissions'] = USER;      // Puedes elegir entre USER, ADMIN o SADMIN. Puedes fijar tus propias en constants.php
$config['send_welcome_email'] = false;      // Depende de activation email.

// Opciones de Seguridad
$config['use_salt'] = true;                 // Encriptar las contraseñas con un salt aleatoreo.
$config['save_last_login'] = true;          // Guardar último login en la base de datos y notificar al usuario.
$config['csrf_protection'] = true;          // Proteger los formularios de CSRF con un token.
$config['hidden_login'] = false;            // Ocultar el botón de iniciar sesión
$config['save_failed_attempt'] = false;     // Llevar un registro de los intentos de login fallidos.
$config['attempts_to_block'] = 4;           // Número de intentos fallidos antes de bloquear la cuenta. 0 desactiva el bloqueo.
$config['failed_attempt_expire'] = 600;     // Tiempo (en segundos) para que el intento de login fallido no se tome en cuenta.
$config['blocking_time'] = 300;             // Tiempo de espera (en segundos) para desbloquear cuenta bloqueada.
$config['password_reset'] = true;           // Activa la opción de recuperar clave si el usuario la olvida. Requere email configurado.

/*
| -------------------------------------------------------------------
| Configuración de Personalización
| -------------------------------------------------------------------
| Puedes utilizar esta configuración para darle un aspecto único a
| tus correos electrónicos y tu pantalla de login.
|
|
*/
$config['app_name'] = 'Ncai Auth System for CI';
$config['app_motto'] = 'Te ahorramos el trabajo pesado.';
$config['company_address'] = 'Huérfanos 1055, Oficina 503. Santiago, Chile.';
$config['app_url'] = base_url();
$config['email_body_background_color'] = '#FFFFFF';
$config['email_body_font_color'] = '#4E4E4E';
$config['email_header_background_color'] = '#F2F2F2';
$config['email_header_font_color'] = '#4E4E4E';
$config['email_footer_background_color'] = '#F2F2F2';
$config['email_footer_font_color'] = '#4E4E4E';
$config['email_font_color'] = '#4E4E4E';
$config['email_brand_color'] = '#EA3A17';
$config['email_container_background_color'] = '#F2F2F2';
$config['app_logo_url'] = 'assets/img/email/logo.png';  // 191 x 60 pixeles, fondo transparente PNG para óptimo resultado.
$config['email_social_style'] = 'dark';                 // Opciones: light, dark, y brand.
$config['email_social_links'] = [                       // Opciones: facebook, gplus, instagram, linkedin, medium, pinterest, soundcloud, spotify, tumblr, twitter, vimeo, youtube.
    [ 'name'  => 'facebook', 'url'   => '#' ],
    [ 'name'  => 'twitter', 'url'   => '#' ],
    [ 'name'  => 'instagram', 'url'   => '#' ]
];

// Texto Email Activación
$config['email_activation_subject'] = 'Activación de Cuenta';
$config['email_activation_title'] = 'Por favor, activa tu cuenta.';
$config['email_activation_paragraphs'] = [
    'p1' => 'Estimado Usuario:',
    'p2' => '¡Gracias por registrarte con nosotros! Por favor, activa tu cuenta presionando el enlace más abajo.',
    'p3' => 'Muchas gracias por preferirnos,',
    'p4' => 'Ncai SpA'
];
$config['email_activation_button_text'] = 'Activar Cuenta';


// Texto Email Bienvenida
$config['email_welcome_subject'] = '¡Bienvenido!';
$config['email_welcome_title'] = '¡Bienvenido!';
$config['email_welcome_paragraphs'] = [
    'p1' => 'Estamos muy felices de que estés junto a nosotros.',
    'p2' => 'Queremos contarte un poco de nuestra apliación.',
    'p3' => 'Puedes iniciar sesión en el enlace de abajo.',
    'p4' => 'Eso es todo,',
];
$config['email_welcome_button_text'] = 'Ir a la Aplicación';

// Texto Email Cambio Contraseña
$config['email_passchange_subject'] = 'Cambio de Contraseña';
$config['email_passchange_title'] = '¡Ooops!';
$config['email_passchange_paragraphs'] = [
    'p1' => 'Estás recibiendo este email porque alguien ha solicitado restaurar la contraseña asociada a tu cuenta de correo.',
    'p2' => 'Si no has sido tú quien ha hecho esto, entonces no hagas nada.',
    'p3' => 'Si, por el contrario, has sido tú quien ha solicitado este cambio, puedes llevarlo a cabo haciendo click en el link de más abajo.',
    'p4' => 'Muchas gracias por preferirnos.',
];
$config['email_passchange_button_text'] = 'Cambiar Contraseña';