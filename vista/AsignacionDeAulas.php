<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asignaciones</title>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link rel="stylesheet" href="aparienciaFormularioAsignacion.css">
</head>

<body>

  <?php
  include('layouts/header.php');
  include('layouts/navegacion.php');
  include("funciones_asignacion.php");
  //include('layouts/header.php');

  //capturamos datos de la tabla solicitud
  $id_solicitud = $_GET["id_solicitud"];
  $fecha_solicitud = $_GET["fecha_solicitud"];
  $fecha_reserva = $_GET["fecha_reserva"];
  $cantidad_estudiantes = $_GET["cantidad_estudiantes"];
  $detalle = $_GET["detalle"];
  $id_docente_materia = $_GET["id_docente_materia"];

  $id_docente = 9;
  $nombre = "nom";
  $apellido = "ape";
  $correo = "corr";

  //Realizamos la consulta
  $db_host = "localhost";
  $db_nombre = "asignacionaulas";
  $db_usuario = "root";
  $db_contrasenia = "";
  $conexion = mysqli_connect($db_host, $db_usuario, $db_contrasenia, $db_nombre);
  if (mysqli_connect_errno()) {
    echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
    exit();
  }
  mysqli_set_charset($conexion, "utf8");

  $consulta = "SELECT * FROM `docente_materia`";

  $resultado_consulta = mysqli_query($conexion, $consulta);

  while (($fila = mysqli_fetch_row($resultado_consulta)) == true) {
    if ($fila[0] == $id_docente_materia) {
      global $id_docente;
      $id_docente = $fila[2];
      $consulta_docente = "SELECT * FROM `docente`";
      $resultado_consulta_docente = mysqli_query($conexion, $consulta_docente);
      while (($fila2 = mysqli_fetch_row($resultado_consulta_docente)) == true) {

        if ($fila2[0] == $id_docente) {
          global $nombre;
          global $apellido;
          global $correo;
          $nombre = $fila2[1];
          $apellido = $fila2[2];
          $correo = $fila2[3];
          break;
        }
      }
    }
  }
  mysqli_close($conexion);


  echo "<form method='post'>";
  echo  "<div class='elem-group'>";
  echo  "<center><label for='id_solicitud'><b>ID Solicitud:</b> <br/> " . $id_solicitud . "</label>  ";
  echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . "' placeholder='id_solicitud' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='nombre'> <b>Docente:</b> <br/> " . $nombre . $apellido . "</label>";
  echo "<input type='hidden' id='nombre' name='nombre' value='" . $nombre . "' placeholder='nonmbre' pattern=[A-Z\sa-z]{3,20} required></center>";
  //echo "</div>";
  //echo "<div class='elem-group'>";
  //echo "<label for='apellido'> " . $apellido . " <br/> </label>";
  echo "<input type='hidden' id='apellido' name='apellido' value='" . $apellido . "' placeholder='apellido' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='correo'><b>Correo:</b> " . $correo . "</label>";
  echo "<input type='hidden' id='correo' name='correo' value='" . $correo . "' placeholder='correo' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='fecha_solicitud'><b>Fecha de Solicitud: </b><br/> " . $fecha_solicitud . "</label>";
  echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . "' placeholder='fecha_solicitud' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='fecha_reserva'><b>Fecha de Reserva: </b><br/> " . $fecha_reserva . "</label>";
  echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . "' placeholder='ejemplo@email.com' required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:</b> " . $cantidad_estudiantes . "</label>";
  echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . "' placeholder='cantidad_estudiantes' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='aula'><b>Aula:</b></label>";
  echo "<input type='text' id='aula' name='aula' placeholder='Introduzca el aula' required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='message'><b>Respuesta de asignacion</b></label></center>";
  echo "<textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí' required></textarea>";
  echo "</div>";
  echo "<input type='submit' name='Enviar' id='Enviar' value='Guardar/Enviar'>";
  /*echo "<a href='aceptar_rechazar.php' > <input type='submit' name='Cancelado' id='Cancelado' value='Cancelar'> </a>";*/
  echo "<center><a href='aceptar_rechazar.php' type='botton' type='submit' name='Cancelado'>Cancelar/volver</a></center>";
  echo "</form>";
?>
</body>
</html>