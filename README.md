Esto es un resumen de como funciona el foro, intentaré que sea breve a la vez que no pierda detalle:

Se ha utilizado el modelo MVC, por una parte la carpeta web contiene la parte visual + el controlador frontal index.

La parte app contiene lo interesante que es la lógica de la aplicación:
    -libs contiene los archivos con todas las funciones que se utilizan (explicaré las menos obvias):
        ◉ las que dicen "sin" son para quitar tildes y espacios
    -plantillas contiene cada parte de html que se carga según la parte del foro en la que estamos.
    -Controlador.php tiene la función necesaria que se utilizará a la par con la plantilla según el menú/opción/página en la que estemos
    -Modelo contiene la lógica de negocio, las querries que utilizarán las funciones del controlador
    -Config tiene los datos necesarios para conectarse a la base de datos.

COMO FUNCIONA??: 
    
    En index.php tenemos el html inicial, este comienza importando el controlador y el modelo para tenerlos a disposición, 
    
    Seguidamente comprobamos si tenemos sesion declarando session_Start() y checando la variable $_SESSION, en caso de que esta por cualquier razón venga vacía le ponemos nivel de usuario 0---> esto influirá en que botones del menú puede o no ver el usuario si está o no registrado. 

    Ahora declaramos el array menus, el que conmtendrá todas las opciones posibles con el formato "inicio"=>array("clase"=>"Controlador","menu"=>"inicio") 
    
    Tengamos en cuenta que esta web funciona mediante variables por url, es decir arriba tendremos al final de url una variable "index.php?dir=hiloNuevo" y esa variable de la url ahora la compararemos con las que tenemos en el array para ver si esta contiene algún menú que coincida, así podemos sacar y meter menús dentro.

    Si hay coincidencia la variable $fin anteriormente creada le pondremos el nombre de este menú, en caso de que no la haya o que no haya variable por url la nombraremos inicio que es el menú principal

    Comprobaremos que el menú que lleve la variable $fin tenga un metodo existente con method_exists() y en caso  de que nos devuelva true llamaremos a la función así nombrada dentro de la clase Controlador call_user_func().

    Supongamos que nuestra variable $fin lleva por valor la func inicio, siguiendo los pasos anteriores nos lleva a la función inicio() dentro del controlador y esta función carga la plantilla inicio.php

    Una vez en inicio empezamos a grabar en buffer el html con ob_start(), cuando acabamos de escribir el html al final declaramos la variable $buffer y le damos el valor del html escribo conm ob_get_Clean()

    Ahora viene un proceso que ocurre en toodas las plantillas, inclimos la plantilla head.php la cual viene con un echo $buffer para cargar todo el html anteriormente escrito peero después de su propio html que vendría a ser el menú, al final del head.html.. asi conseguimos que el header siempre esté encima del contenido.

    prácticamente este sería el funcionamiento de cualquier menú.

LO ANTERIOR ERA UN EJEMPLO DE COMO FUNCIONA EL APARTADO FORO; PERO Y LAS DEMAS SECCIONES COMO VAN?:
    si por ejmplo cargamos el menu foro, este tiene un imput hiloNuevo, si apretamos dicho imput nos llevará al funcion hiloNuevo() del Controlador el cual recogerá en variables $mensaje $titulo  los datos respectivos y comprobará su longitud con los metodos util cTextarea(), si no tuviesen el formato correcto se haría saber en la variable $errores, si $errores es 0 entonces procedemos a declarar la clase Conectate cuyo constructor creará una instancia para conectarse a la base de datos con la info que tenemos en Config.php, crearemos $idusr (auí irá otra variable de sesion), $hora y $fecha y en caso de que el hilo se haya creado y el mensaje se haya insertado en el ULTIMO id de hilo, nos redireccionará al menú hilo de esa id creada.

    Algo parecido pasa dentro de cada hilo creado, cada vez que insertamos un mensaje nuevo hay una conexión a la base de datos, una comprobación de mensajes existentes y si el resultado devuelve menos de uno  saldrá en pantalla el else "no hay mensajes todavía" (en caso de no haber ningún hilo creado) 

    Así mismo borrarCuenta  nos llevará a -- > controlador borrarcuenta el cual comprobará si el usuario existe en la base de datos, y en caso de ser así --> llamará al model borrarcuenta el que ejecutará la querry necesaria con el id de dicho usuario comprobado para eliminarlo de la base de datos y reconducir la cuenta a inicio de nuevo.

    En caso de ALTA de usuario (boton registrate en el foro) nos pedirá nombre contraseña email y foto de perfil opcional, sino se proporciona foto se pone una por defecto, una vez apretamos el imput enviar el controlador ejecutará la función alta() la cual rellenará las variables con la información solicitada a travez del formulario, seguidamente comprobará si dichos datos están en el formato correcto, por ejemplo el username con cGeneral le pasamos un regex de formato concreto para ver si el usuario que nos introducen efectivamente cumple con los criterios del regex, luego comprobarUser comprobara si dicho usuario ya existe en base de datos y en caso negativo, solo, procederá a crear su entrada con lso datos proporcionados acudiendo a la función crearIdUser en nuestro model(también aplicamos cGeneral sobre contraseña, cMail, is_uploaded_file para ver si hemos subido una foto )

    en caso del login es más sencillo, recogemos campos usuario y contraseña , comprobamos a travez del model si existen en la base de datos y en caso afirmativo procedemos a loguear a nuestro usuario cambiando su estatus en $_SESSION  de 0 a 1 para que pueda ver menus adicionales que un invitado no puede. 

    Logout justo lo contrario, borramos el status de usuario y boramos la cookie o sesión que almacena la id de nuestro usuario para navegar por el foro destruyendo la sesión para que así no haya inyecciones en la base de datos ni contacto con el servidor después de hacer logout