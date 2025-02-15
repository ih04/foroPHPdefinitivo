<?php ?>
		
	<h1 class="centrar"><?php echo $msjs[0]['titulo']; ?></h1>

	<?php if(count($msjs)>0): ?>

		<?php foreach ($msjs as $m): ?>

				escrito <?php echo $m['fechaPubli']; ?> a las<?php echo $m['horaPubli']; ?>
				<table class="mensaje">
					<tr>
						<td class="msjProf">
							<img src=<?php echo $m['fotoP']; ?>><?php echo $m['usuario']; ?>
						</td>
						<td class="msj"><?php echo $m['mensaje']; ?>
						</td>
					</tr>
				</table>



		<?php endforeach; ?>
	<?php else: ?>
			No hay mensajes todavía!
	<?php endif; ?>

	<form class="formMsj" action="" method="POST" >
		<textarea class="campoTArea centrar" name="mensaje" placeholder="escribe tu mensaje"></textarea><br>
		<input class="boton" type="submit" name="send" value="enviar">
	</form>


	




<?php $buffer=ob_get_clean();  include"head.php";?>
