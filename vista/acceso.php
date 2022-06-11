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
$usuario = $resultado->fetchAll(PDO::FETCH_ASSOC);

if (!empty($usuario)) {
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION["id_docente"] = $usuario[0]["id_docente"];
    $_SESSION["ci_docente"] = $usuario[0]["ci_docente"];
    $_SESSION["nombre_docente"] = $usuario[0]["nombre_docente"];
    $_SESSION["correo"] = $usuario[0]["correo"];
    $_SESSION["usuario"] = $usuario[0]["usuario"];
    $_SESSION["password"] = $usuario[0]["password"];
    $_SESSION["is_admin"] = $usuario[0]["is_admin"];

    $nombreA = 'Bienvenido! ' .$_SESSION["nombre_docente"]. ' Usted esta iniciando sesion como Administrador';
    $nombreD = 'Bienvenido! ' .$_SESSION["nombre_docente"]. ' Usted esta iniciando sesion como Docente';

    if ($_SESSION["is_admin"] == 1) { ?>
        <script> 
            var nombreA = '<?php echo $nombreA;?>';
            alert(nombreA);
        </script>
        <script> 
            location.href='http://localhost/asignacionAulas-main/vista/homeAdministrativo.php';
        </script>
        <?php 
    } 
    else { ?>
        <script> 
            var nombreD = '<?php echo $nombreD; ?>';
            alert(nombreD);
        </script>
        <script> 
            location='../vista/homeDocente.php';
        </script>
        <?php 
    }
} 
else {?>
    <!--header("Location: ../login.php");-->
        <script> alert('El usuario o contraseña no son correctos, intente nuevamente');
        </script>
        <script> location.href='../login.php';
        </script>
<?php }
?>
