<?php 
class Conexion{	  
    public static function Conectar() {        
        //$opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');			
        try{
            $conexion =$db;// new PDO("mysql:host=".'localhost'."; dbname=".'asignacionaulas', 'root', '', $opciones);			
            return $conexion;
        }catch (Exception $e){
            die("El error de Conexión es: ". $e->getMessage());
        }
    }
}
?>