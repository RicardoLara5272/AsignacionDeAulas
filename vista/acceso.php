<?php
include_once("../conexiones/conexion.php");

//$_POST["fecha"] = date("Y-m-d");
//$id_docente = 1;
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$username = $_POST["username"];
$password = $_POST["password"];


$sql = "SELECT * FROM docentes WHERE usuario='{$username}' AND password = '{$password}'";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$usuarioAuth = $resultado->fetchAll(PDO::FETCH_ASSOC);
session_start();

if (!empty($usuarioAuth)) {
    foreach ($usuarioAuth as $key => $usuario) {
        $_SESSION["id_docente"] = $usuario["id_docente"];
        $_SESSION["ci_docente"] = $usuario["ci_docente"];
        $_SESSION["nombre_docente"] = $usuario["nombre_docente"];
        $_SESSION["correo"] = $usuario["correo"];
        $_SESSION["usuario"] = $usuario["usuario"];
        $_SESSION["password"] = $usuario["password"];
        $_SESSION["is_admin"] = $usuario["is_admin"];
        break;
    }
   

    $nombreA = 'Bienvenido! ' . $_SESSION["nombre_docente"] . ' Usted esta iniciando sesion como Administrador';
    $nombreD = 'Bienvenido! ' . $_SESSION["nombre_docente"] . ' Usted esta iniciando sesion como Docente';
    if ($_COOKIE) {
        if (isset($_COOKIE[$_SESSION["usuario"]])) {
            unset( $_COOKIE[$_SESSION["usuario"]]);
        }
    }
    if (isset($_COOKIE[$_SESSION["usuario"]])) {
        $contador = $_COOKIE[$_SESSION["usuario"]];
        setcookie($_SESSION["usuario"], $contador + 1, time() + 3600);
    } else {
        setcookie($_SESSION["usuario"], 1, time() + 3600);
    }
    if ($_SESSION["is_admin"] == 1) {
        if (isset($_SESSION)) {
            header("Location: ../vista/vistaDetPend.php");
            die();
        }
    } else {
        if (isset($_SESSION)) {
            header("Location: ../vista/homeDocente.php");
            die();
        }else{
            header("Location: ../index.php");
            die();
        }
    }
   
} else {
    header("Location: ../index.php");
    die();
}
