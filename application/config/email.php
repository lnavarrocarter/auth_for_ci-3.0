<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Configuración de Correo
|--------------------------------------------------------------------------
| Aquí puedes configurar las credenciales de tu servidor de correo.
| Está preconfigurado para funcionar con smtp, con credenciales que vienen
| desde vriables de ambiente con .htaccess. Si desconoces cómo funcionan las
| variables de ambiente, te sugiero que leas la documentación al respecto.
|
| Autor: Matías Navarro Carter
| Email: mnavarrocarter@gmail.com
| Licencia: MIT
|
|
*/

$config = array(
    'protocol'      => 'smtp',
    'smtp_host'     => $_SERVER['SMTPHOST'], // Definir con SetEnv en un .htaccess
    'smtp_port'     => $_SERVER['SMTPPORT'], // Definir con SetEnv en un .htaccess
    'smtp_user'     => $_SERVER['SMTPUSER'], // Definir con SetEnv en un .htaccess
    'smtp_pass'     => $_SERVER['SMTPPASS'], // Definir con SetEnv en un .htaccess
    'mailtype'      => 'html',
    'validate'      => true,
    'smtp_crypto'   => 'ssl'
);