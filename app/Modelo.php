<?php
include_once ('Config.php');

class Conectate extends PDO
{

    protected $instance;

    public function __construct()
    {                                 
            $this->instance = new PDO("mysql:host=".Config::$dbhost.";"."dbname=".Config::$dbdata,Config::$dbuser,Config::$dbpass);
            
            $this->instance->exec("set names utf8");
            $this->instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
    }

    public function foro(){
        return $this->instance->query(
            "SELECT h.id AS id, h.titulo AS titulo, h.fechaCreacion AS fecha, h.horaCreacion AS hora, 
            u.usuario AS user, m.mensaje AS mensaje 
            FROM hilo h JOIN usuario u ON h.id_user = u.id 
            JOIN mensaje m ON h.id = m.id_hilo 
            WHERE m.id = ( SELECT MIN(m2.id) FROM mensaje m2 WHERE m2.id_hilo = h.id );"
            )->fetchAll(PDO::FETCH_ASSOC);
    }
   
    public function misHilos($IDUser){
        return $this->instance->query(
            "SELECT h.id AS id, h.titulo AS titulo, h.fechaCreacion AS fecha, h.horaCreacion AS hora, 
            u.usuario AS user, m.mensaje AS mensaje 
            FROM hilo h JOIN usuario u ON h.id_user = u.id 
            JOIN mensaje m ON h.id = m.id_hilo 
            WHERE m.id = ( SELECT MIN(m2.id) FROM mensaje m2 WHERE m2.id_hilo = h.id )
            and u.id =".$IDUser
            )->fetchAll(PDO::FETCH_ASSOC);
            
    }

    public function hilo($idHilo){
        return $this->instance->query(
            "select m.mensaje as mensaje,m.fechaPublicacion as fechaPubli, m.horaPublicacion as horaPubli,u.usuario as usuario,u.fotoP as fotoP, h.titulo as titulo
            from mensaje m 
            JOIN hilo h ON h.id = m.id_hilo 
            JOIN usuario u ON u.id=m.id_user 
            where id_hilo = ". $idHilo
            )->fetchAll(PDO::FETCH_ASSOC);
    }

    public function nuevoHilo($idusr,$titulo,$fecha,$hora){
        return  $this->instance->exec(
                            "insert into hilo (`id_user`,`titulo`,`fechaCreacion`,`horaCreacion`) values ('$idusr','$titulo','$fecha','$hora')"
                );
      
    }

    public function maxHilo(){
        return $this->instance->query(
                "select MAX(`id`) from hilo"
            )->fetchAll(PDO::FETCH_ASSOC)[0]["MAX(`id`)"];
    }

    

    public function mensajeInsert($idU,$idH,$m,$f,$h){
        return $this->instance->exec(
            "insert into mensaje (`id_user`,`id_hilo`,`mensaje`,`fechaPublicacion`,`horaPublicacion`) values ('$idU','$idH','$m','$f','$h')  "
            
        );
    }

    public function login($n,$c){
            $result = $this->instance->prepare(
            "select * from usuario
            where usuario = :user
            and contrasena = :pass"
            );
            
            $result->bindParam(':user', $n);
            $result->bindParam(':pass', $c);
            $result->execute();

            return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    //alta

    public function comprobarUser( $user ){
        $result = $this->instance->prepare(
            "select usuario from usuario where usuario= :user"
        );
        
        $result->bindParam(':user', $user);

        $result->execute();
        
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crearIdUsr($username,$pass,$mail,$fotoP,$fecha){
        return $this->instance->exec(
            "insert into usuario (`usuario`,`contrasena`,`mail`,`fotoP`,`fechaAlta`) values ('$username','$pass','$mail','$fotoP','$fecha')"
            
        );
    }

    //borrar

    public function borrarCuenta($idUsr){
        return $this->instance->exec(
            "update usuario set contrasena='@@@@@@@@@@@234234____',usuario='borrado' where id=$idUsr"
            
        );
    }







    
}
?>
