
<!doctype html>
<hmtl>
<head>
    <meta charset="utf-8">
    <title>UMSS fcyt</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="aparienciaFormularioAsignacion.css">

</head>
<body>
<?php
include("funciones_asignacion.php");

//capturamos datos de la tabla solicitud
$id_solicitud=$_GET["id_solicitud"];
$fecha_solicitud=$_GET["fecha_solicitud"];
$fecha_reserva=$_GET["fecha_reserva"];
$cantidad_estudiantes=$_GET["cantidad_estudiantes"];
$detalle=$_GET["detalle"];
$id_docente_materia=$_GET["id_docente_materia"];

$id_docente=9;
$nombre="nom";
$apellido="ape";
$correo="corr";

//Realizamos la consulta
$db_host="localhost";
$db_nombre="umss_tis";
$db_usuario="root";
$db_contrasenia="";
$conexion=mysqli_connect($db_host,$db_usuario,$db_contrasenia,$db_nombre);
if(mysqli_connect_errno()){
    echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
    exit();
}
mysqli_set_charset($conexion, "utf8");

$consulta="SELECT * FROM `asignacion_de_m`";

$resultado_consulta=mysqli_query($conexion,$consulta);

while(($fila=mysqli_fetch_row($resultado_consulta))==true){
  if($fila[0]==$id_docente_materia){
    global $id_docente;
    $id_docente=$fila[2];
    $consulta_docente="SELECT * FROM `docente`";
    $resultado_consulta_docente=mysqli_query($conexion,$consulta_docente);
    while(($fila2=mysqli_fetch_row($resultado_consulta_docente))==true){

      if($fila2[0]==$id_docente){
        global $nombre;
        global $apellido;
        global $correo;
        $nombre=$fila2[1];
        $apellido=$fila2[2];
        $correo=$fila2[3];
        break;
      }
    }
  }
}
mysqli_close($conexion);

echo "<header class='conteiner-fluid'>";
    echo "<h1>SISTEMA DE ASIGNACION DE AULAS</h1>";
echo "</header>";
echo "<form method='post'>";
 echo  "<div class='elem-group'>";
   echo  "<center><label for='id_solicitud'>id_solicitud : " . $id_solicitud . "</label>";
   echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . "' placeholder='id_solicitud' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='nombre'>nombre : " . $nombre . "</label>";
    echo "<input type='hidden' id='nombre' name='nombre' value='" . $nombre . "' placeholder='nonmbre' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='apellido'>apellido : " . $apellido . "</label>";
    echo "<input type='hidden' id='apellido' name='apellido' value='" . $apellido . "' placeholder='apellido' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='correo'>correo : " . $correo . "</label>";
    echo "<input type='hidden' id='correo' name='correo' value='" . $correo . "' placeholder='correo' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='fecha_solicitud'>fecha_solicitud : " . $fecha_solicitud . "</label>";
    echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . "' placeholder='fecha_solicitud' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='fecha_reserva'>fecha_reserva : " . $fecha_reserva . "</label>";
    echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . "' placeholder='ejemplo@email.com' required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='cantidad_estudiantes'>cantidad_estudiantes : " . $cantidad_estudiantes . "</label>";
    echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . "' placeholder='cantidad_estudiantes' pattern=[A-Z\sa-z]{3,20} required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='aula'>aula :</label><br>";
    echo "<input type='text' id='aula' name='aula' placeholder='introduzca el aula' required></center><br>";
  echo "</div>";
  echo "<div class='elem-group'>";
    echo "<center><label for='message'>Respuesta de asignacion</label></center>";
    echo "<textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí' required></textarea>";
  echo "</div>";
   echo "<input type='submit' name='Enviar' id='Enviar' value='Guardar/Enviar'>";
  /*echo "<a href='aceptar_rechazar.php' > <input type='submit' name='Cancelado' id='Cancelado' value='Cancelar'> </a>";*/
  echo "<center><a href='aceptar_rechazar.php' type='botton' type='submit' name='Cancelado'>Cancelar/volver</a></center>";
echo "</form>";
echo "<footer class='conteiner-fluid'>";
  echo "<center><h2>Universidad Mayor de San Simon</h2></center>";
echo "</footer>";
echo "</body>";
?>

</html>
