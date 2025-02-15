<?php 
	require "../app/libs/bGeneral.php";
	require "../app/libs/utils.php";
	require "../app/Config.php";
	require "../app/Modelo.php";


	class Controlador{
		
		var $errores=[];
		 
		public function inicio(){
			include "plantillas/inicio.php";
		}

		//típico successful
		public function exito(){
			include "plantillas/exito.php";
		}

		//función para invocar la plantilla donde se nos expondrán los hilos disponibles en el foro

		public function foro(){
			try{
				$db=new Conectate();
				$instancias=$db->foro();
				if(isset($_REQUEST['hiloNuevo'])){
					header("location:index.php?dir=hiloNuevo");

				}

				
				include "plantillas/foro.php";
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}
		}


		//esto es una versión de foro, pero de manera que solo saldrán los hilos que hemos subido nosotros "el usuario logueado en ese momento!"
		public function misHilos(){
			try{
				$db=new Conectate();
				$instancias=$db->misHilos($_SESSION['idUsr']);
				$params = [];
				if(isset($_REQUEST['hiloNuevo'])){
					header("location:index.php?dir=hiloNuevo");

				}


				include "plantillas/misHilos.php";
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}
		}

		

		//desde aquí accederemos a los mensajes contenidos en cada hilo
		public function hilo(){
			//en este caso el obstart empieza desde aquí para que los mensajes queden donde nos interesa
			
			
			
			try{  	
				$errores = [];
				if(isset($_REQUEST['idHilo']) && $_REQUEST['idHilo']!=""){
					ob_start();
					$params = array(
						'hora' => date("H:i:s"),
						'fecha' => date("Y-m-d")
						
					);
					$hora=date("H:i:s");
					$fecha=date("Y-m-d");
					print($fecha);
					$idHilo=$_REQUEST['idHilo']; 

					$db=new Conectate();
					$msjs=$db->hilo($idHilo);
					
					$mensaje=recoge('mensaje');
					cTextarea($mensaje,'mensaje',$errores,500,10);

					if(!isset($_SESSION['idUsr'])){$errores['username']="No has iniciado sesión";}else{$idU=$_SESSION['idUsr']; }

					include("plantillas/hilo.php");
					
					if(isset($_REQUEST['send'])){
						if(count($errores)>0){
							
							foreach($errores as $e){
								echo "<p class='errores'>".$e."</p>";
							}		
						}else{
							if($db->mensajeInsert($idU,$idHilo,$mensaje,$fecha,$hora)){
								header("location:index.php?dir=hilo&idHilo=$idHilo");
							}else{echo "error";}
						}

					}
				}else{ob_start();
					echo "algo salió mal!";$buffer=ob_get_clean(); 


					include("plantillas/head.php");
				}
			
			
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}
		}

		//esto nos llevará a la plantilla para iniciar un hilo, al pinchar en el boton de hilo nuevo del foro, aquí se obliga a poner un mensaje y título para el hilo

		public function hiloNuevo(){
			 try{
				$errores=[];
				if(isset($_REQUEST['send'])){
					$mensaje=recoge('mensaje');
					$titulo=recoge('titulo');

					cTextarea($mensaje,'mensaje',$errores,500,10);
					cTextarea($titulo,'titulo',$errores,50,10);

					if(count($errores)>0){
						foreach($errores as $e);
						echo "<p class='errores'>".$e."</p>";
					}else{
						$db=new Conectate();
						$idusr=$_SESSION['idUsr'];
	
			 
						$hora=date("H:i:s");
						$fecha=date("Y-m-d");
	
	
	
						
						if($db->nuevoHilo($_SESSION['idUsr'],$titulo,$fecha,$hora)>0){
								
							if($db->mensajeInsert($_SESSION['idUsr'],$db->maxHilo(),$mensaje,$fecha,$hora)>0){
								
								header("location:index.php?dir=hilo&idHilo=".$db->maxHilo());
	
								
							}else{
	
								header("location:index.php?dir=error&err=algo pasó con el mensaje");
							}
	
							
						} else{
							header("location:index.php?dir=error&err=algo pasó con el hilo");
						}
					}
				}
				include "plantillas/hiloNuevo.php";
			}catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}
			
		}

		public function error(){
			include "plantillas/error.php";
		}

		public function logoff(){
			session_destroy();
			header("location:index.php?dir=exito&ex=lamentamos que te bayas");
		}

		public function login(){
			
			try{
				$errores=[];
				if(isset($_REQUEST['send'])){
					//user y contraseña del formulario
					$username=recoge('username');
					$contrasena=recoge('contrasena');
					$userId;
					$params = array(
						'usuario' => recoge('username'),
						'contraseña' => recoge('contrasena'),
						'IDUsuario' => ''
					);
					//comprobamos si existen en la base de datos
					$db = new Conectate();
					$instancias=$db->login($username,$contrasena);

					if(count($instancias)<=0){
						$errores[]="usuario o contraseña incorrectos";
					}
					if(count($errores)>0){
						foreach($errores as $e){
							echo '<p class="errores">'.$e."</p>";
						}
					}else{
						print_r($instancias);
						echo "procesando... ";
						echo $instancias[0]['usuario'];
						$userId=$instancias[0]["id"];

						session_start();
						$_SESSION['username']=$username;
						$_SESSION['contrasena']=$contrasena;

						if(isset($userId))$_SESSION['idUsr']=$userId;
						$_SESSION['fotoP']=$instancias[0]['fotoP'];

						$_SESSION['lvl']=1;
						header("location:index.php?dir=exito&ex=welcome again");
					}



				}
				include "plantillas/login.php";
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}	
		}

		public function alta(){
			
			try{
				if(isset($_REQUEST['send'])){

					$db= new Conectate();
					$username=recoge('username');
					$contrasena=recoge('contrasena');
					$mail=recoge('mail');
					$fotoP="imagenes/fotosP/default.jpg";


				    if(!(cGeneral($username,'username',$this->errores,"/^([a-z]|[A-Z]|[_]){1}([a-z]|[A-Z]|[_]|[0-9]|[$]|[&]){0,12}$/"))){
						if(!isset($username)||$username="")$this->errores['username']="el campo usuario es obligatorio";
					}else if(count($db->comprobarUser($username))>0){
								$this->errores['usrDup']="este usuario actualmente ya existe en el sistema!";
					}else{		if(!(cGeneral($contrasena,"contrasena",$this->errores,"/^([a-z]|[A-Z]|_|\*|\+|-|\\|\/|[0-9]|\$|&){1,15}$/"))) 
								if(!isset($contrasena) || $contrasena=="")$this->errores['contrasena'].=" el Campo contraseña es obligatorio";
		    				    if(!cMail($mail,'mail',$this->errores))$this->errores['mail']='El email no ha salido en el formato correcto';
		  					    if(is_uploaded_file($_FILES['fotoP']['tmp_name'])){
					 				echo $_FILES['fotoP']['tmp_name'];
					 				if(cFot($_FILES['fotoP']['name'],$this->errores)){
					 					$rutafot='imagenes/fotosP/'.$username.".".explode(".",$fotoP=$_FILES['fotoP']['name'])[1];
					 					if(moverFoto('fotoP',$rutafot,$this->errores)){$fotoP=$rutafot;}
					 				}
					 			}

					}

					if(count($this->errores)>0){

						foreach($this->errores as $e){
							echo "<p class=\"errores\">"."$e"."</p>";
						}

					}else{


						$hora=date("H:i:s");
						$fecha=date("Y-m-d");

						if($db->crearIdUsr($username,$contrasena,$mail,$fotoP,$fecha)>0)
						{

							header("location:index.php?dir=exito&ex=bienvenido al foro&loc=inicio");


						}else{header("location:index.php?dir=error"); }

					}

				}

				include "plantillas/alta.php";	
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}	
		}

		//opción de borrar tu cuenta de la base de datos si estás en ella
		public function borrarCuenta(){
			try{ 
				ob_start();
				if(isset($_REQUEST['send'])){
					if(isset($_REQUEST['check'])){
						$db=new Conectate();
						if(isset($_SESSION['idUsr'])){

							if($db->borrarCuenta($_SESSION['idUsr'])>0){
								header("location:index.php?dir=logoff");
							}else{
								header("location:index.php?dir=error&err=Nan usuario");
							} 

						}
					}else{
						header("location:index.php?dir=error&err=debes consentir querer borrar tu perfil primero!");
					
					}
				}include("plantillas/borrarCuenta.php");
			} catch (Exception $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logExceptio.txt");
				header('location:index.php?dir=error');
			} catch (Error $e) {
				error_log($e->getMessage() . microtime() . PHP_EOL, 3, "logError.txt");
				header('location:index.php?dir=error');
			}	
		}

	}

 ?>