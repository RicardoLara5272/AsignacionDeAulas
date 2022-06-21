<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Asignaciones</title>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- <link rel="stylesheet" href="aparienciaFormularioAsignacion.css"> -->
  <link rel="stylesheet" href="css/stylesheets/asignacionDeAulas.css">
</head>

<body>

<?php
  include('layouts/header.php');
  include('layouts/navegacion.php');
  //include("funciones_asignacion.php");

  //capturamos datos de la tabla solicitud
  $id_solicitud = $_POST["id_solicitud_Pend"];
  $id_reserva=$_POST['id_reserva2'];
  $fecha_solicitud = $_POST["fecha_solicitud"];
  $fecha_reserva = $_POST["fecha_reserva"];
  $cantidad_estudiantes = $_POST["capEstudiantes"];
  $id_docente=$_POST['id_docente'];
  $id_materia = (int)$_POST["id_materia"];
  $Grupo = $_POST["grupo"];
  $hora_inicio = $_POST["hora_inicio"];
  $hora_fin = $_POST["hora_fin"];
  //echo $id_solicitud.$id_reserva.$fecha_solicitud.$fecha_reserva.$cantidad_estudiantes.$id_docente.$id_materia.$Grupo.$hora_inicio.$hora_fin; 
  
  $nombre = "nom";
  //$apellido = "ape";
  $correo = "corr";

  $aulas_asignadas=array();

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

  $consultar_grupo="SELECT * FROM `reserva` WHERE id_reserva = $id_reserva AND id_solicitudes = $id_solicitud";
  $resultado_consulta_grupo=mysqli_query($conexion, $consultar_grupo);
  while(($grupo = mysqli_fetch_row($resultado_consulta_grupo)) == true){
    $grupos=json_decode($grupo[3]);
  }

  $IDsDocente=array();
  $nombres=array();
  $correos=array();
  $grupo_ordenado=array();
  $IDs=array();

  $IDdocente = "SELECT * FROM `docente_materia` WHERE id_materia = $id_materia";
  $resultado_IDdocente = mysqli_query($conexion, $IDdocente);
  $contadorGru=0;
  while(($el_id_docente = mysqli_fetch_row($resultado_IDdocente)) == true){
    for($g=0; $g<count($grupos); $g++){
      if($el_id_docente[3] == $grupos[$g]){
      $IDs[]=$el_id_docente[1];
        $LosDocentes = "SELECT * FROM `docentes` WHERE id_docente = $el_id_docente[1]";
        $resultado_LosDocentes = mysqli_query($conexion, $LosDocentes);
        while(($nombre_docente = mysqli_fetch_row($resultado_LosDocentes)) == true){
            $IDsDocente[]=$nombre_docente[0];
            $nombres[]=$nombre_docente[2];
            $correos[]=$nombre_docente[3];
            $grupo_ordenado[]=$grupos[$g];         
        }
      }
    }
  }

  $consulta = "SELECT * FROM `docentes` WHERE id_docente = $id_docente ";

  $resultado_consulta = mysqli_query($conexion, $consulta);

  while(($fila = mysqli_fetch_row($resultado_consulta)) == true){
    global $nombre;
    global $correo;
    $nombre = $fila[2];
    $correo = $fila[3];
  }

  if(count($grupos)>1){
    echo "<p class='title'>Asignación a solicitud compartida</p>";
    echo "<form action='funciones_asignacion.php' method='post' class='contenedor'>";
    echo  "<div class='side'><div class='elem-group'>";
    echo  "<label for='id_solicitud'><b>ID Solicitud:<br></b> " . $id_solicitud . "</label>  ";
    echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . ">";
    echo "</div>";
    echo  "<div class='elem-group'>";
    echo  "<label for='id_reserva2'><b>ID Reserva:<br></b> " . $id_reserva . "</label>  ";
    echo  "<input type='hidden' id='id_reserva2' name='id_reserva2' value='" . $id_reserva . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='nombre'> <b>Docentes:</b></label>";
    for($n=0; $n<count($grupos); $n++){
      echo "<label>$nombres[$n] </label>";
    }
    echo "<input type='hidden' id='nombre' name='nombre' value='" . htmlspecialchars(serialize($nombres)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='nombre'> <b>Grupos:</b> </label>";
    for($g=0; $g<count($grupos); $g++){
      echo "<label>$grupos[$g] </label>";
    }
    echo "<input type='hidden' id='grupo' name='grupo' value='" . htmlspecialchars(serialize($grupos)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='correo'><b>Correos:</b></label>";
    for($c=0; $c<count($grupos); $c++){
      echo "<label>$correos[$c] </label>";
    }
    echo "<input type='hidden' id='correo' name='correo' value='" . htmlspecialchars(serialize($correos)) . "'>";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<input type='hidden' id='idCorrecto' name='idCorrecto' value='" . htmlspecialchars(serialize($IDsDocente)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<input type='hidden' id='ordenado' name='ordenado' value='" . htmlspecialchars(serialize($grupo_ordenado)) . ">";
    echo "</div></div>";
    echo "<div class='side'><div class='elem-group'>";
    echo "<label for='fecha_solicitud'><b>Fecha de Solicitud:<br></b> " . $fecha_solicitud . "</label>";
    echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='fecha_reserva'><b>Fecha de Reserva:<br></b> " . $fecha_reserva . "</label>";
    echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:<br></b> " . $cantidad_estudiantes . "</label>";
    echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . ">";
    echo "</div>";
    echo "<div class='aulas'>";
    $numero=1;
    $numero2=0;
    $consulta_aula = "SELECT * FROM `aulas`";
    $resultado_consulta_aula = mysqli_query($conexion, $consulta_aula);
    while(($codigo_aula = mysqli_fetch_row($resultado_consulta_aula)) == true){
      $consulta_auxiliar = "SELECT * FROM `auxiliar` WHERE id_aula = $codigo_aula[0]";
      $resultado_consulta_auxiliar = mysqli_query($conexion, $consulta_auxiliar);
      if(($codigo_auxiliar = mysqli_fetch_row($resultado_consulta_auxiliar)) == true){
        if((int)$codigo_auxiliar[0] == (int)$codigo_aula[0])
        {
          echo "<label for='aula'><b>Aula $numero:</b></label>";
          echo "<label for='aula'>". $codigo_aula[1] ."</label>";
          $aulas_asignadas[$numero2]=$codigo_aula[1];
          $numero+=1;
          $numero2+=1;
        }else{echo "$codigo_aula[0] -- $codigo_auxiliar[0] // ";}
      }
    }
    echo "</div></div>";
    echo "<div class='respuesta'><div>";
    echo "<label for='message'><b class='respuesta-asignacion'>Respuesta de asignacion</b></label>";
    echo "<textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí (Opcional)'> </textarea>";
    echo "</div>";
   
    echo "<input type='submit' name='Enviar' id='Enviar' value='GUARDAR/ENVIAR' class='enviar'>";
    /*echo "<a href='aceptar_rechazar.php' > <input type='submit' name='Cancelado' id='Cancelado' value='Cancelar'> </a>";*/
    echo "</form>";
    echo "<form action='vistaDetRese.php' method='post' class='form-cancelar'>
    <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
    <input type='submit' name='Cancelar' value='CANCELAR' class='cancelar'>
    </form></div>";

     mysqli_close($conexion);
  }else{
    echo "<p class='title'>Asignación a solicitud individual</p>";
    echo "<form action='funciones_asignacion.php' method='post' class='contenedor'>";
    echo  "<div class='side'><div class='elem-group'>";
    echo  "<label for='id_solicitud'><b>ID Solicitud:</b><br> " . $id_solicitud . "</label>";
    echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . ">";
    echo "</div>";
    echo  "<div class='elem-group'>";
    echo  "<label for='id_reserva2'><b>ID Reserva:<br></b> " . $id_reserva . "</label>  ";
    echo  "<input type='hidden' id='id_reserva2' name='id_reserva2' value='" . $id_reserva . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='nombre'> <b>Docente:</b></label>";
    for($n=0; $n<count($grupos); $n++){
      echo "<label>$nombres[$n] </label>";
    }
    echo "<input type='hidden' id='nombre' name='nombre' value='" . htmlspecialchars(serialize($nombres)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='nombre'> <b>Grupo:</b> </label>";
    for($g=0; $g<count($grupos); $g++){
      echo "<label>$grupos[$g] </label>";
    }
    echo "<input type='hidden' id='grupo' name='grupo' value='" . htmlspecialchars(serialize($grupos)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='correo'><b>Correo:</b></label>";
    for($c=0; $c<count($grupos); $c++){
      echo "<label>$correos[$c] </label>";
    }
    echo "<input type='hidden' id='correo' name='correo' value='" . htmlspecialchars(serialize($correos)) . "'>";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<input type='hidden' id='idCorrecto' name='idCorrecto' value='" . htmlspecialchars(serialize($IDsDocente)) . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<input type='hidden' id='ordenado' name='ordenado' value='" . htmlspecialchars(serialize($grupo_ordenado)) . ">";
    echo "</div></div>";
    echo "<div class='side'><div class='elem-group'>";
    echo "<label for='fecha_solicitud'><b>Fecha de Solicitud: <br></b> " . $fecha_solicitud . "</label>";
    echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='fecha_reserva'><b>Fecha de Reserva:<br> </b> " . $fecha_reserva . "</label>";
    echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . ">";
    echo "</div>";
    echo "<div class='elem-group'>";
    echo "<label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:<br></b> " . $cantidad_estudiantes . "</label>";
    echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . ">";
    echo "</div>";
    echo "<div class='aulas'>";
    $numero=1;
    $numero2=0;
    $consulta_aula = "SELECT * FROM `aulas`";
    $resultado_consulta_aula = mysqli_query($conexion, $consulta_aula);
    while(($codigo_aula = mysqli_fetch_row($resultado_consulta_aula)) == true){
      $consulta_auxiliar = "SELECT * FROM `auxiliar` WHERE id_aula = $codigo_aula[0]";
      $resultado_consulta_auxiliar = mysqli_query($conexion, $consulta_auxiliar);
      if(($codigo_auxiliar = mysqli_fetch_row($resultado_consulta_auxiliar)) == true){
        if((int)$codigo_auxiliar[0] == (int)$codigo_aula[0])
        {
          echo "<label for='aula'><b>Aula $numero:</b></label>";
          echo "<label for='aula'>". $codigo_aula[1] ."</label>";
          $aulas_asignadas[$numero2]=$codigo_aula[1];
          $numero+=1;
          $numero2+=1;
        }else{echo "$codigo_aula[0] -- $codigo_auxiliar[0] // ";}
      }
    }
    echo "</div></div>";
    echo "<div class='respuesta'><div>";
    echo "<label for='message'><b class='respuesta-asignacion'>Respuesta de asignación</b></label>";
    echo "<textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí (Opcional)'> </textarea>";
    echo "</div>";
   
    echo "<input type='submit' name='Enviar' id='Enviar' value='GUARDAR/ENVIAR' class='enviar'>";
    echo "</form>";
    echo "<form action='vistaDetRese.php' method='post' class='form-cancelar'>
    <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
    <input type='submit' name='Cancelar' value='CANCELAR' class='cancelar'>
    </form></div>";

     mysqli_close($conexion);
  }
?>