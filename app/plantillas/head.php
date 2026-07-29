<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="fonts.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Orbitron:wght@400..900&family=Oswald:wght@200..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>

	<h1><p>FORO</p><p>DEV</p></h1>
	<div class="perfilHeader">

	<?php  if(isset($_SESSION['fotoP']))echo "<img src=".$_SESSION['fotoP'].">"."<br>";if(isset($_SESSION['username']))echo $_SESSION['username']; echo "<br>nivel: "; if($_SESSION['lvl']==0){echo "Invitado";}else{if($_SESSION['lvl']==1){echo "Usuario ";}} ?></div>
	<header class="header">
		<div class="celdas"><a href="index.php?dir=inicio">Inicio</a></div>
		<?php if($_SESSION['lvl']==0): ?><div class="celdas"><a href="index.php?dir=alta">regístrate</a></div><?php endif; ?>
		<?php if($_SESSION['lvl']==0): ?><div class="celdas"><a href="index.php?dir=login">login</a></div><?php endif; ?>
		<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=foro">foro</a></div><?php endif; ?>
		<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=misHilos">mis hilos</a></div><?php endif; ?>
		<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=borrarCuenta">borrarCuenta</a></div><?php endif; ?>
		<?php if($_SESSION['lvl']>0): ?><div class="celdas"><a href="index.php?dir=logoff">finalizar sesion</a></div><?php endif; ?>

		
		
	</header>
	
	<br>
	<br>
	<br>

	
	<?php echo $buffer; ?>



</body>
</html>