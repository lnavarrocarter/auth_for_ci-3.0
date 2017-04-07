# Ncai Auth for CI
Un sistema de autenticación autoinstalable para Codeigniter; rápido, seguro, flexible y totalmente personalizable.

## Caracterísiticas

1. **Auto-Instalable:** No debes preocuparte por instalar la base de datos. Simplemente coloca tus credenciales en /config/database.php y olvídate del resto. El la primera ejecución, Ncai Auth hará todo por ti.

2. **Out-of-the-box:** Simplemente arrastra, configura lo que quieras y usa. ¡Así de fácil!

3. **Redirección Inteligente:** Si tus usuarios visitan un lugar que requiere inciar sesión, mándalos al login, y luego de vuelta al lugar al que querían ir.

4. **Desactivar Registro:** Si no quieres permitir registros de usuarios, un simple `false` en el archivo de configuración es suficiente.

5. **Registro Personalizable:** Elige fácilmente los campos que quieres colocar en tu registro, como nombre de pila y nombre de usuario, además de términos de servicio. Si quieres enviar un correo de confirmación o registrarlos de inmediato. El mínimo es correo electrónico y contraseña.

6. **Sistema de Permisos Flexible:** Manjea los permisos de usuario de forma programática y escalable utilizando constantes y guardando los datos en binarios. ¡Es más fácil de lo que suena!

7. **Medidas de Seguridad:** Puedes elegir utilizar o no todo tipo de medidas de seguridad, como salts, proteccion CSRF para formularios, login oculto, guardar intentos fallidos y bloquear luego de un número determinado de ellos, y cambio de contraseña al ser olvidada.

8. **Ultra personalizable:** Define el color de tus emails, las redes sociales, el texto, tu logo y todo lo demás simplemente utilizando el archivo de configuración. El constructor de correo lo hará todo por ti y hará tus correos lucir profesionales.

## Instalación y Configuración
Es muy fácil instalar y configurar Ncai Auth. Simplemente sigue estos pasos:

1. Descargar la [última versión](https://github.com/mnavarrocarter/ncai_auth_for_ci/releases/latest)

2. Extraer el contenido en el directorio raíz de tu Codeigniter

3. Crea tu base de datos.

4. En tu config/database.php escribe las credenciales de tu base de datos.

5. En config/auth.php define las funciones a utilizar, configura tus correos y todo lo que necesitas.

6. En config/routes.php escribe 'auth' como la ruta por defecto.

7. Ejecútalo, y disfruta la magia!

>NOTA: Ten en cuenta que Ncai Auth sobreescribirá la configuración que tengas en tus archivos de autoload.php, database.php, constants.php, email.php y migration.php. Es recomendable siempre instalarlo en una instancia fresca de Codeigniter. Si lo estás instalando en una instancia que ya posee configuraciones definidas en esos archivos, asegúrate de guardarlas o reconfigurarlas.

## Funcionalidades en Desarrollo

1. Crear una librería para registrar entradas de log para ciertas actividades.

2. Guardar varios intentos fallidos de violación CSRF y quizás bloquear la ip. Mandar un correo al admin.

3. Crear el controlador de Users, con vistas para administrar.

4. Integración con AdminLTE

5. Para sistemas de login que requieren una subscripción, agregar campo para plan y funciones para validar subscripción.

6. Campo para URL de foto de Perfil.

7. Librería para CronJobs, como recordatorios para el usuario y eliminación de tokens.

## Contribuir
1. Puedes [reportar un bug o sugerir una mejora](https://github.com/mnavarrocarter/ncai_auth_for_ci/issues).

2. Puedes [realizar pull requests](https://github.com/mnavarrocarter/ncai_auth_for_ci/pulls) con mejoras y nuevas funcionalidades que desees incluir en la plataforma.