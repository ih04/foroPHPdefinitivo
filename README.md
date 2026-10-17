# Foro Dev — Foro web en PHP y MySQL

**Foro Dev** es una aplicación web de tipo foro desarrollada con PHP, HTML, CSS y MySQL/MariaDB. Permite registrar usuarios, iniciar y cerrar sesión, crear hilos de conversación, publicar mensajes dentro de los hilos y consultar las publicaciones propias.

El proyecto utiliza una organización inspirada en el patrón **MVC (Modelo–Vista–Controlador)**, sesiones PHP para identificar a los usuarios y PDO para comunicarse con la base de datos.

> **Nota sobre el estado del proyecto:** esta documentación describe el código incluido en el repositorio. Algunas funciones están implementadas de forma básica y hay aspectos de seguridad y validación que conviene corregir antes de desplegar la aplicación en Internet.

## Índice

- [Funcionalidades](#funcionalidades)
- [Tecnologías](#tecnologías)
- [Arquitectura](#arquitectura)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Modelo de datos](#modelo-de-datos)
- [Funcionamiento de la aplicación](#funcionamiento-de-la-aplicación)
- [Secciones del foro](#secciones-del-foro)
- [Instalación y ejecución](#instalación-y-ejecución)
- [Seguridad y mejoras recomendadas](#seguridad-y-mejoras-recomendadas)

## Funcionalidades

- **Registro de usuarios:** solicita nombre de usuario, contraseña, correo electrónico y, opcionalmente, una imagen de perfil.
- **Inicio de sesión:** comprueba las credenciales almacenadas en la base de datos y crea variables de sesión.
- **Cierre de sesión:** destruye la sesión actual.
- **Listado general de hilos:** muestra el título, fecha, hora, autor y mensaje inicial de cada hilo.
- **Hilos propios:** permite consultar los hilos creados por el usuario que ha iniciado sesión.
- **Creación de hilos:** crea un hilo con título y un primer mensaje.
- **Publicación de mensajes:** permite añadir mensajes a un hilo existente.
- **Perfiles:** muestra el nombre y la fotografía de perfil del usuario.
- **Borrado lógico de cuenta:** cambia el nombre y la contraseña guardados para marcar la cuenta como borrada, en lugar de eliminar físicamente el registro.
- **Validación de formularios:** incluye funciones auxiliares para validar textos, correos electrónicos, longitudes y determinados formatos.
- **Gestión básica de errores:** captura excepciones y errores en algunas acciones y escribe información en archivos de registro.

## Tecnologías

| Tecnología | Uso |
|---|---|
| PHP | Lógica del servidor, controladores, sesiones y plantillas |
| PDO | Conexión y consultas a la base de datos |
| MySQL / MariaDB | Almacenamiento de usuarios, hilos y mensajes |
| HTML | Estructura de las páginas y formularios |
| CSS | Diseño visual del foro |
| JavaScript no es esencial para el flujo principal | Las operaciones principales se realizan mediante formularios HTML y peticiones HTTP tradicionales |
| Apache o servidor PHP equivalente | Servir la aplicación y ejecutar los scripts PHP |

El volcado `foro.sql` se generó originalmente con MariaDB y phpMyAdmin. El código conecta con una base de datos llamada `foro`.

## Arquitectura

El proyecto sigue una organización inspirada en MVC, aunque no utiliza un framework MVC formal.

```text
Navegador
   |
   | GET / POST
   v
web/index.php
(Controlador frontal y enrutamiento)
   |
   v
app/Controlador.php
(Acciones y flujo de la aplicación)
   |
   +--------------------------+
   |                          |
   v                          v
app/plantillas/           app/Modelo.php
(Vistas PHP/HTML)         (Consultas SQL mediante PDO)
                              |
                              v
                       MySQL/MariaDB
                       Base de datos foro
```

### Responsabilidad de cada parte

- **Controlador frontal (`web/index.php`):** inicia la sesión, establece el nivel de acceso inicial y decide qué método del controlador ejecutar según el parámetro `dir` de la URL.
- **Controlador (`app/Controlador.php`):** procesa formularios, valida los datos, consulta el modelo, gestiona redirecciones y carga las plantillas.
- **Modelo (`app/Modelo.php`):** contiene la clase `Conectate`, que crea la conexión PDO y ejecuta las consultas SQL.
- **Configuración (`app/Config.php`):** define el servidor, el nombre de la base de datos y las credenciales de conexión.
- **Plantillas (`app/plantillas/`):** generan el HTML de las distintas páginas.
- **Biblioteca de validación (`app/libs/bGeneral.php`):** ofrece funciones reutilizables para recoger y validar datos de formularios.
- **Utilidades (`app/libs/utils.php`):** contiene funciones auxiliares; actualmente incluye `timeOut()`.
- **Recursos web (`web/`):** contiene el punto de entrada, las hojas de estilo y las imágenes de perfil.

## Estructura del proyecto

```text
foroPHPdefinitivo-master/
├── README.md
├── foro.sql
├── app/
│   ├── .htaccess
│   ├── Config.php
│   ├── Controlador.php
│   ├── Modelo.php
│   ├── libs/
│   │   ├── bGeneral.php
│   │   └── utils.php
│   ├── plantillas/
│   │   ├── alta.php
│   │   ├── borrarCuenta.php
│   │   ├── error.php
│   │   ├── exito.php
│   │   ├── foro.php
│   │   ├── head.php
│   │   ├── hilo.php
│   │   ├── hiloNuevo.php
│   │   ├── inicio.php
│   │   ├── login.php
│   │   └── misHilos.php
│   └── logExceptio.txt
├── web/
│   ├── index.php
│   ├── style.css
│   ├── fonts.css
│   ├── logError.txt
│   ├── logExceptio.txt
│   └── imagenes/
│       └── fotosP/
│           ├── default.jpg
│           └── otras imágenes
└── logExceptio.txt
```

### Archivos principales

- `web/index.php`: punto de entrada de la web y enrutador central.
- `app/Controlador.php`: contiene las acciones `inicio`, `alta`, `login`, `foro`, `misHilos`, `hilo`, `hiloNuevo`, `borrarCuenta`, `logoff`, `exito` y `error`.
- `app/Modelo.php`: implementa las operaciones de lectura y escritura en la base de datos.
- `app/Config.php`: configura los parámetros de conexión a la base de datos.
- `app/plantillas/head.php`: genera la estructura HTML común, el encabezado, el menú y el contenido de cada plantilla.
- `app/plantillas/alta.php`: formulario de registro.
- `app/plantillas/login.php`: formulario de acceso.
- `app/plantillas/foro.php`: listado de hilos.
- `app/plantillas/misHilos.php`: listado de hilos creados por el usuario actual.
- `app/plantillas/hiloNuevo.php`: formulario para crear un hilo y su primer mensaje.
- `app/plantillas/hilo.php`: muestra los mensajes de un hilo y el formulario para publicar otro.
- `app/libs/bGeneral.php`: funciones de recogida y validación de datos.
- `foro.sql`: crea la base de datos, las tablas, los índices y las relaciones.
- `web/style.css` y `web/fonts.css`: estilos, distribución, colores y tipografías.
- `web/imagenes/fotosP/`: imágenes usadas como fotos de perfil.

## Modelo de datos

La base de datos `foro` contiene tres tablas principales.

### Tabla `usuario`

| Campo | Tipo SQL | Descripción |
|---|---|---|
| `id` | `INT` | Clave primaria autoincremental |
| `usuario` | `VARCHAR(100)` | Nombre de usuario |
| `contrasena` | `VARCHAR(100)` | Contraseña almacenada por la aplicación |
| `mail` | `VARCHAR(100)` | Correo electrónico |
| `fotoP` | `VARCHAR(200)` | Ruta de la imagen de perfil |
| `fechaAlta` | `DATE` | Fecha de registro |

### Tabla `hilo`

| Campo | Tipo SQL | Descripción |
|---|---|---|
| `id` | `INT` | Clave primaria autoincremental |
| `id_user` | `INT` | Usuario que creó el hilo; referencia a `usuario.id` |
| `titulo` | `VARCHAR(200)` | Título del hilo |
| `fechaCreacion` | `DATE` | Fecha de creación |
| `horaCreacion` | `TIME` | Hora de creación |

### Tabla `mensaje`

| Campo | Tipo SQL | Descripción |
|---|---|---|
| `id` | `INT` | Clave primaria autoincremental |
| `id_user` | `INT` | Usuario que publicó el mensaje |
| `id_hilo` | `INT` | Hilo al que pertenece el mensaje |
| `mensaje` | `VARCHAR(500)` | Contenido del mensaje |
| `fechaPublicacion` | `DATE` | Fecha de publicación |
| `horaPublicacion` | `TIME` | Hora de publicación |

### Relaciones

- Un usuario puede crear varios hilos.
- Un usuario puede publicar varios mensajes.
- Un hilo puede contener varios mensajes.
- `hilo.id_user` referencia `usuario.id`.
- `mensaje.id_user` referencia `usuario.id`.
- `mensaje.id_hilo` referencia `hilo.id`.
- La clave foránea de `mensaje.id_hilo` tiene `ON DELETE CASCADE`, por lo que eliminar un hilo de la base de datos elimina sus mensajes asociados. Las relaciones con usuarios no permiten borrar un usuario que aún esté referenciado.

El script crea claves primarias, índices y restricciones de clave foránea. No incluye datos de ejemplo insertados en las tablas. Aunque la página inicial muestra unas credenciales de demostración (`prueba` / `123`), el volcado SQL incluido no crea ese usuario; para utilizarlo tendría que existir previamente en la base de datos.

## Funcionamiento de la aplicación

### 1. Entrada y enrutamiento

La aplicación se abre desde `web/index.php`. Este archivo:

1. Incluye el controlador y el modelo.
2. Ejecuta `session_start()` para acceder a la sesión del usuario.
3. Si no existe `$_SESSION['lvl']`, establece el nivel `0`, correspondiente a un visitante.
4. Define una lista de rutas permitidas en el array `$menus`.
5. Lee el parámetro `dir` de la URL, por ejemplo `index.php?dir=foro`.
6. Comprueba que la ruta está en la lista y que existe el método correspondiente en `Controlador`.
7. Invoca el método con `call_user_func()`.

Así, la mayor parte de las secciones se sirven a través de un único punto de entrada.

### 2. Construcción de las páginas

Las plantillas suelen utilizar `ob_start()` para capturar el HTML en un búfer. Al terminar, guardan el contenido en `$buffer` y cargan `head.php`, que añade la estructura común, el menú y finalmente el contenido capturado.

Este mecanismo permite reutilizar el encabezado y la navegación sin repetirlos en cada página.

### 3. Registro de usuario

1. El visitante abre `index.php?dir=alta`.
2. Introduce nombre de usuario, contraseña, correo y, opcionalmente, una foto.
3. El controlador recoge los valores con `recoge()`.
4. Valida el formato del nombre, comprueba que no exista ya, revisa la contraseña y valida el correo.
5. Si se ha subido una foto, intenta comprobar su extensión y moverla a `web/imagenes/fotosP/`. Si no se sube, se usa una imagen predeterminada.
6. Si no hay errores, el modelo inserta el usuario en la tabla `usuario`.
7. La aplicación redirige a la página de éxito o de error.

**Importante:** el flujo actual no cifra la contraseña antes de almacenarla. Es una limitación de seguridad que debe corregirse.

### 4. Inicio y cierre de sesión

Para iniciar sesión:

1. El usuario envía el nombre y la contraseña.
2. El controlador llama a `Conectate::login()`.
3. El modelo busca una coincidencia en la tabla `usuario`.
4. Si encuentra un registro, el controlador guarda datos como el ID, el nombre, la foto y el nivel en `$_SESSION`.
5. El navegador es redirigido a una página de éxito.

Para cerrar sesión, el método `logoff()` ejecuta `session_destroy()` y redirige a la página de inicio. Se recomienda también limpiar la cookie de sesión y regenerar el identificador en el inicio de sesión.

### 5. Listado general de hilos

Cuando un usuario accede al foro:

1. El controlador crea una conexión mediante `Conectate`.
2. Ejecuta `foro()`.
3. El modelo consulta los hilos, sus autores y el primer mensaje de cada hilo.
4. La plantilla `foro.php` muestra título, fecha, hora, autor y descripción.
5. Al pulsar un título, se abre `index.php?dir=hilo&idHilo=...`.

La consulta selecciona el mensaje con menor ID de cada hilo para mostrarlo como introducción.

### 6. Hilos propios

La ruta `index.php?dir=misHilos` llama al método `misHilos()` del controlador. El modelo obtiene los hilos cuyo autor coincide con el ID almacenado en la sesión. La plantilla `misHilos.php` muestra el resultado y permite abrir cada hilo.

### 7. Creación de un hilo

1. Desde el foro se pulsa el botón para crear un hilo.
2. El formulario solicita un título y un mensaje inicial.
3. El controlador valida las longitudes de ambos campos.
4. El modelo inserta el nuevo registro en `hilo`.
5. Después se inserta el primer mensaje en `mensaje`.
6. La aplicación redirige a la vista del hilo recién creado.

El código obtiene el ID del hilo recién creado mediante una consulta `MAX(id)`. En un entorno con varios usuarios concurrentes es más seguro obtener el ID generado por la propia inserción, para evitar que otra petición cree un hilo entre ambas operaciones.

### 8. Publicación de mensajes

1. La página del hilo carga sus mensajes desde el modelo.
2. El controlador recoge el texto del formulario y valida su longitud.
3. Comprueba que exista un usuario identificado en la sesión.
4. Inserta el mensaje con el ID del usuario, el ID del hilo y la fecha y hora actuales.
5. Si la inserción funciona, redirige a la misma página para mostrar el nuevo mensaje.

### 9. Borrado de cuenta

El usuario accede a la opción de borrar su cuenta y debe marcar la casilla de confirmación. El modelo no elimina el registro físicamente: cambia el nombre a `borrado` y sustituye la contraseña por un texto fijo. Después, el controlador cierra la sesión.

Esto es un **borrado lógico simplificado**, no una eliminación completa de los datos personales. Debe revisarse si se quiere anonimizar correctamente la cuenta y qué debe ocurrir con sus hilos y mensajes.

## Secciones del foro

| Sección | Función |
|---|---|
| Inicio | Presentación y acceso a las opciones disponibles |
| Registro | Alta de una cuenta |
| Login | Autenticación del usuario |
| Foro | Listado general de hilos |
| Mis hilos | Publicaciones creadas por el usuario |
| Hilo | Mensajes de una conversación y formulario de respuesta |
| Hilo nuevo | Formulario para crear una conversación |
| Borrar cuenta | Confirmación y desactivación lógica de la cuenta |
| Finalizar sesión | Cierre de sesión |
| Éxito / Error | Mensajes informativos para el usuario |

La navegación cambia según `$_SESSION['lvl']`: los visitantes ven las opciones públicas y los usuarios con sesión iniciada ven las opciones adicionales. Esta ocultación del menú es una decisión de interfaz; las acciones protegidas también deben validar permisos en el servidor.

## Instalación y ejecución

### Requisitos

- PHP con las extensiones `PDO` y `pdo_mysql` habilitadas.
- MySQL o MariaDB.
- Apache con PHP, XAMPP, WAMP, MAMP o un servidor equivalente.
- Un navegador web.

### 1. Crear la base de datos

Importa `foro.sql` desde phpMyAdmin o desde la consola de MySQL/MariaDB. El script crea la base de datos `foro`, las tres tablas y sus relaciones.

Con la consola:

```bash
mysql -u root -p < foro.sql
```

El comando solicitará la contraseña del usuario de base de datos.

### 2. Configurar la conexión

Edita `app/Config.php` y adapta estos valores a tu entorno:

```php
class Config {
    static public $dbhost = "localhost";
    static public $dbdata = "foro";
    static public $dbuser = "TU_USUARIO";
    static public $dbpass = "TU_PASSWORD";
}
```

No uses credenciales reales en un repositorio público. En una instalación más segura, la configuración debe leerse de variables de entorno o de un archivo de configuración fuera del directorio público.

### 3. Colocar el proyecto en el servidor

Sitúa la carpeta del proyecto en el directorio servido por Apache. Configura el servidor para que el **document root apunte a la carpeta `web/`**, ya que `web/index.php` es el punto de entrada y las plantillas y el modelo están fuera de esa carpeta.

Por ejemplo, en una instalación local, accede a la URL que corresponda a la configuración de tu servidor, como `http://localhost/foroPHPdefinitivo-master/web/` si el proyecto está dentro del directorio público y no se ha configurado un virtual host.

### 4. Comprobar permisos de subida de imágenes

El servidor PHP debe poder escribir en `web/imagenes/fotosP/`. Limita los permisos al usuario que ejecuta PHP y evita conceder permisos de escritura más amplios de lo necesario.

### 5. Abrir la aplicación

Accede a la ruta web configurada. La página inicial permite entrar al foro o registrar un usuario. El proyecto no incluye un sistema de migraciones ni una instalación automatizada.

## Seguridad y mejoras recomendadas

El código sirve como proyecto de aprendizaje, pero no debería exponerse directamente a Internet sin revisar estos puntos.

1. **Contraseñas en texto plano.** El registro guarda la contraseña tal cual y el login compara ese texto. Usa `password_hash()` al registrar y `password_verify()` al autenticar; actualiza también las cuentas existentes.
2. **Consultas SQL concatenadas.** Varias consultas del modelo interpolan valores directamente, por ejemplo al crear hilos, insertar mensajes, consultar un hilo o filtrar los hilos del usuario. Utiliza sentencias preparadas con parámetros en todas las operaciones.
3. **Escape de salida HTML.** Los nombres, títulos y mensajes se imprimen en las plantillas sin escapar sistemáticamente. Usa `htmlspecialchars($valor, ENT_QUOTES, 'UTF-8')` al insertar texto de usuario en HTML para reducir el riesgo de XSS.
4. **Control de acceso en el servidor.** No basta con ocultar enlaces según `$_SESSION['lvl']`. Cada acción que exige una cuenta debe comprobar la sesión y la autorización antes de modificar datos.
5. **Gestión de sesión.** El controlador frontal ya inicia la sesión y el método `login()` vuelve a llamar a `session_start()`. Centraliza el inicio de sesión, regenera el ID con `session_regenerate_id(true)` tras autenticar y limpia adecuadamente la sesión al salir.
6. **Subida de imágenes.** La comprobación de extensión de `cFot()` es frágil y la comprobación de tamaño está después de un `return`, por lo que no se ejecuta. Comprueba tamaño, MIME real y extensión permitida, genera nombres de archivo seguros y rechaza archivos que no sean imágenes válidas.
7. **Protección CSRF.** Las operaciones que cambian datos se ejecutan mediante formularios sin tokens CSRF visibles en el código revisado. Añade tokens y verifícalos en el servidor.
8. **Validación de campos obligatorios.** En el registro hay una condición de asignación (`$username=""`) donde parece que se pretendía comparar. Conviene corregirla y revisar todos los casos límite de los validadores.
9. **Transacciones.** Crear un hilo y su primer mensaje son dos inserciones relacionadas. Usa una transacción para que ambas operaciones se confirmen o reviertan juntas.
10. **Identificador del hilo recién creado.** Evita usar `MAX(id)` para recuperar el último hilo. Recupera el ID generado en la misma conexión mediante el mecanismo de PDO apropiado, por ejemplo `lastInsertId()`.
11. **Mensajes de error.** Evita mostrar detalles internos al usuario. Registra información técnica en un destino no público y presenta mensajes genéricos.
12. **Archivos de log.** Los archivos de registro están dentro del árbol del proyecto. Asegúrate de que no sean accesibles por HTTP y de que no contengan contraseñas, tokens u otros datos sensibles.
13. **Configuración y credenciales.** `Config.php` usa valores locales predeterminados (`root` sin contraseña). Configura un usuario de base de datos con privilegios mínimos y saca los secretos del repositorio.
14. **Borrado de cuenta.** El borrado lógico actual no elimina ni anonimiza todos los datos personales y utiliza un valor fijo para sustituir la contraseña. Diseña un proceso explícito de desactivación o anonimización.
15. **Pruebas.** Añade pruebas de registro, login, creación de hilos, publicación de mensajes, permisos y entradas maliciosas.

## Resumen final

Foro Dev es una aplicación de foro en PHP que demuestra un flujo completo básico de gestión de usuarios y conversaciones: registro, login, listado de hilos, creación de conversaciones, publicación de mensajes y cierre de sesión. Su estructura separa el enrutamiento, el controlador, el modelo de datos, las plantillas y las funciones auxiliares.

El proyecto es útil para estudiar PHP tradicional, PDO, sesiones, formularios, validación y relaciones entre tablas. Las mejoras prioritarias son almacenar contraseñas con hash, parametrizar todas las consultas SQL, escapar las salidas HTML, reforzar los permisos y validar de forma segura las imágenes subidas.
