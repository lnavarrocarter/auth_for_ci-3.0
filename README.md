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

1. Descarga la [última versión](https://github.com/mnavarrocarter/ncai_auth_for_ci/releases/latest)

2. Extrae el contenido en el directorio raíz de tu Codeigniter

3. Crea tu base de datos.

4. En tu config/database.php, escribe las credenciales de tu base de datos.

5. En config/auth.php, define las funciones a utilizar, configura tus correos y todo lo que necesitas.

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

8. Confirmación de password para acciones sensibles de seguridad, bajo `auth/confirm`.

## Específicos

### Sobre el Sistema de Permisos
Ncai Auth utiliza una de las mejores prácticas para manejar permisos de usuarios de forma programática. Mientras que IonAuth utiliza una tabla aparte para grupos donde se guardan a los grupos administrativos a los que pertenece el usuario, nosotros definimos los grupos de manera programática.

Para empezar, es tan flexible que no se puede encapsular sólo en grupos de usuarios, sino más bien en permisos. Al final de nuestro constants.php definimos con qué permisos queremos trabajar en nuestro software, asignándoles valores enteros en potencia de dos a cada uno de los que queramos definir. Por defecto, Ncai Auth trae tres "grupos".

```
defined('USER')     OR define('USER', 1);       // User
defined('ADMIN')    OR define('ADMIN', 2);      // Admin
defined('SADMIN')   OR define('SADMIN', 4);     // Super Admin
```
Para agregar más permisos, simplemente debemos preocuparnos que el número que le sigue está multiplicado por dos. Además, puedes reescribir el sistema de acuerdo a la necesidad de tu aplicación. Por ejemplo:
```
defined('CREATE_USER')     OR define('CREATE_USER', 1);
defined('DELETE_USER')    OR define('DELETE_USER', 2);
defined('VIEW_COURSE')   OR define('VIEW_COURSE', 4);
defined('REGISTER_COURSE')   OR define('REGISTER_COURSE', 8);
```
Parecido a como funcionan los permisos en UNIX (1 = read, 2 = write, 3 = execute), las sumatorias de éstos números producen resultados únicos que son fáciles de obtener utilizando el operador bitwise & en una query. Por ejemplo, en UNIX, si tengo un permiso 5, yo sé que los permisos que tienen son read (1) y execute (4) solamente. No hay otra suma posible de los valores asignados que me pueda dar 5. Así, es fácil indentificar múltiples permisos.

Para guardar y revisar permisos es muy sencillo.
```
// Esto suma los tres, y guarda un integer 7 en la base de datos.
$this->db->update('users', ['permissions' => USER], ['id' => $id]);

// Para revisar, por ejemplo, si un usuario tiene un permiso, utilizamos la libería predefinida:
$this->middleware->only_permission(ADMIN, 'app');
```
Lo que hace la útima función es permitir el acceso sólo a los que pertenecen al grupo de admins. Al resto, los va a redirigir a controlador app. Esto sirve para validar el acceso a un controlador. Sin embargo, pueden establecerse y validarse múltiples permisos tan sólo separandolos con el operador |.

## Contribuir
1. Puedes [reportar un bug o sugerir una mejora](https://github.com/mnavarrocarter/ncai_auth_for_ci/issues).

2. Puedes [realizar pull requests](https://github.com/mnavarrocarter/ncai_auth_for_ci/pulls) con mejoras y nuevas funcionalidades que desees incluir en la plataforma.