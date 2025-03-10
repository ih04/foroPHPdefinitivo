<?php ob_start(); ?>
<h1>Bienvenido al foro del DEV</h1>
<br>
Sino quiere registrarse acceda con <h2>pass: <u>prueba</u> y contraseña: <u>1234</u></h2> 
<br>
<h2>Que debería hacer en este foro?</h2>
<ol>
	<li>Regístrate en este foro para ver todas las opciones así como crear hilos nuevos y participar en los hilos de otros usuarios</li>
	<li>Si ya tienes una cuenta logueate.</li>
	<li>Si surge cualquier problema se resolverá lo antes posible</li>
</ol>


<?php $buffer = ob_get_clean();  include "head.php";?>
