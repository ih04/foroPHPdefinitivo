<?php ob_start(); ?>
<h2>Welcome to dev forum</h2>
<br>
If you dont want to register use this: <h2>user: <u>prueba</u> & pass: <u>123</u></h2> 
<br>
<h2>What should I do in this forum?</h2>
<ol >
	<li>Register to see all the menus and options. </li>
	<li>If have an account login.</li>
	<li>If there is a problem it will be solved as fast as possible.</li>
</ol>


<?php $buffer = ob_get_clean();  include "head.php";?>
