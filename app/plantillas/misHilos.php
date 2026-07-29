<?php ob_start(); ?>

<form action="" method="POST"><input type="submit" value="hilo nuevo" name="hiloNuevo">
<div class="foro" name="divHilos"></form>
	<?php 
		
		if(count($instancias)>0){
			
			echo "<table>";
				echo "<tr>";
					echo "<th>";
						echo "<i>"."titulo"."</i>";
					echo "</th>";
					echo "<th>";
						echo "<i>"."fecha"."</i>";
					echo "</th>";
					echo "<th>";
						echo "<i>"."hora"."</i>";
					echo "</th>";
					echo "<th>";
						echo "<i>"."autor"."</i>";
					echo "</th>";
					echo "<th>";
						echo "<i>"."descripción"."</i>";
					echo "</th>";
				echo "</tr>";

				 foreach($instancias as $i){ 
				 	 $idHilo=$i["id"];
				echo "<tr>";
					
					echo "<td>";
						 echo "<a class='titulo fnt2' href='index.php?dir=hilo&idHilo=".$i['id']."'>".$i['titulo']." "."</a>"; 
					echo "</td>";
					echo "<td>";
						 echo "<div class='titulo' >".$i['fecha']." "."</div>"; 
					echo "</td>";
					echo "<td>";
						 echo "<div class='titulo' >".$i['hora']." "."</div>"; 
					echo "</td>";
					echo "<td>";
						 echo "<div class='titulo'>".$i['user']." "."</div>"; 
					echo "</td>";
					echo "<td>";
						 echo "<b class='titulo fnt2'>".$i['mensaje']." </b>"; 
					echo "</td>";
						
				echo "</tr>";
				 } 


			echo "</table>";
					
		}else{
			echo "<p class='parrafo'>Todavía no has publicado ningún hilo</p>";
		}

	 ?>
	
</div>

<?php $buffer=ob_get_clean(); include("head.php"); ?>