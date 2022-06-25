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
  //include("funciones_asignacion.php");
  //include('layouts/header.php');

  //capturamos datos de la consultar aulas
  $id_solicitud = $_POST["id_solicitud_Pend"];
  $id_reserva=$_POST['id_reserva2'];
  $fecha_solicitud = $_POST["fecha_solicitud"];
  $fecha_reserva = $_POST["fecha_reserva"];
  $cantidad_estudiantes = $_POST["capEstudiantes"];
  $id_docente=$_POST['id_docente'];
  $id_materia = $_POST["id_materia"];
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

  $nombres=array();
  $correos=array();
  $IDs=array();

  $IDdocente = "SELECT * FROM `docente_materia` WHERE id_materia = $id_materia";
  $resultado_IDdocente = mysqli_query($conexion, $IDdocente);
  $contadorGru=0;
  while(($el_id_docente = mysqli_fetch_row($resultado_IDdocente)) == true){
    for($g=0; $g<count($grupos); $g++){
      if($el_id_docente[3] == $grupos[$g]){
      $IDs[]=$el_id_docente[1];
      }
    }
  }

  $LosDocentes = "SELECT * FROM `docentes`";
  $resultado_LosDocentes = mysqli_query($conexion, $LosDocentes);
  while(($nombre_docente = mysqli_fetch_row($resultado_LosDocentes)) == true){
    for($j=0; $j<count($IDs); $j++){
      if($nombre_docente[0] == $IDs[$j]){
      $nombres[]=$nombre_docente[2];
      $correos[]=$nombre_docente[3];
      }
    }
  }

  for($n=0; $n<count($grupos); $n++){
    echo "el id de la materia es $id_materia<br>";
    echo " grupo : $grupos[$n]  tamnaño del arreglo :" . count($grupos) . "<br>";
    echo " id docente : $IDs[$n] tamaño del arreeglo :" . count($IDs) . "<br>";
    echo "el nombre del docente es : $nombres[$n] su tamaño del arreglo " . count($nombres) . "<br>";
    echo " el correo del docente es : $correos[$n] su tamaño del arreglo " . count($correos) . "<br>";
  }

  $consulta = "SELECT * FROM `docentes` WHERE id_docente = $id_docente ";

  $resultado_consulta = mysqli_query($conexion, $consulta);

  while(($fila = mysqli_fetch_row($resultado_consulta)) == true){
    global $nombre;
    global $correo;
    $nombre = $fila[2];
    $correo = $fila[3];
  }
  /*$estado_reseva='nada';
  $reserva_aceptada_rechazada="SELECT * FROM reservas_atendidas WHERE id_reserva = $id_reserva";
  $resultado_reserva = mysqli_query($conexion, $reserva_aceptada_rechazada);
  while(($su_estado = mysqli_fetch_row($resultado_reserva)) == true){
    global $estado_reseva;
    $estado_reseva = $su_estado[3];
  }
  
  if($estado_reseva=='aceptado'){
    $borrar_auxiliar = "DELETE FROM auxiliar";
    $borrar = mysqli_query($conexion, $borrar_auxiliar);
    mysqli_close($conexion);
    echo '<script language="javascript">alert("Esta reeserva ya fue aceptada");</script>';
    echo "<script language='javascript'>window.location.replace('http://localhost/pruebasTis/AsignacionDeAulas/vista/vistaDetPend.php')</script>";
    echo "<br>$estado_reseva";
  }else if($estado_reseva == 'rechazado'){
    $borrar_auxiliar = "DELETE FROM auxiliar";
    $borrar = mysqli_query($conexion, $borrar_auxiliar);
    mysqli_close($conexion);
    echo '<script language="javascript">alert("Esta reeserva ya fue rechazada");</script>';
    echo "<script language='javascript'>window.location.replace('http://localhost/pruebasTis/AsignacionDeAulas/vista/vistaDetPend.php')</script>";
    echo "<br>$estado_reseva";
  }else if($estado_reseva=='nada'){
    echo "<br>$estado_reseva";
  }*/
 


  echo "<form action='funciones_asignacion.php' method='post'><br>";
  echo  "<div class='elem-group'>";
  echo  "<center><label for='id_solicitud'><b>ID Solicitud:</b> <br/> " . $id_solicitud . "</label>  ";
  echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . "' placeholder='id_solicitud' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo  "<div class='elem-group'>";
  echo  "<center><label for='id_reserva2'><b>ID Reserva:</b> <br/> " . $id_reserva . "</label>  ";
  echo  "<input type='hidden' id='id_reserva2' name='id_reserva2' value='" . $id_reserva . "</center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='nombre'> <b>Docente:</b> <br/> " . $nombre . "</label>";
  echo "<input type='hidden' id='nombre' name='nombre' value='" . $nombre . "' placeholder='nonmbre' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "<div class='elem-group'>";
  echo "<center><label for='correo'><b>Correo:</b> " . $correo . "</label>";
  echo "<input type='hidden' id='correo' name='correo' value='" . $correo . "'</center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='fecha_solicitud'><b>Fecha de Solicitud: </b><br/> " . $fecha_solicitud . "</label>";
  echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . "' placeholder='fecha_solicitud' pattern=[A-Z\sa-z]{3,20} required></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='fecha_reserva'><b>Fecha de Reserva: </b><br/> " . $fecha_reserva . "</label>";
  echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . "></center>";
  echo "</div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:<br></b> " . $cantidad_estudiantes . "</label>";
  echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . "></center>";
  echo "</div>";

  /*echo "<div class='elem-group'>";
  echo "<center><label for='aula'><b>Aula:</b></label></center><center>";
  for ($i=0;$i<count($aulas);$i++)    
  {    
    echo "<label for='aula'>". $aulas[$i] ."</label><br></div>";
  } */
  /*echo "</center></div>";
  echo "<div class='elem-group'>";
  echo "<center><label for='aula'><b>Aula:</b></label>";
  echo "<input type='text' id='aula' name='aula' placeholder='Introduzca el aula' required></center>";
  echo "</div>";*/
  echo "<div class='elem-group'>";
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
        echo "<center><label for='aula'><b>Aula $numero:</b></label>";
        echo "<center><label for='aula'>". $codigo_aula[1] ."</label>";
        $aulas_asignadas[$numero2]=$codigo_aula[1];
        $numero+=1;
        $numero2+=1;
      }else{echo "$codigo_aula[0] -- $codigo_auxiliar[0] // ";}
    }
  }
  echo "</div>";

  echo "<div class='elem-group'>";
  echo "<center><label for='message'><b>Respuesta de asignacion</b></label></center>";
  echo "<center><textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí (Opcional)'> </textarea></center>";
  echo "</div>";
 
  echo "<center><input type='submit' name='Enviar' id='Enviar' value='Guardar/Enviar'></center>";
  /*echo "<a href='aceptar_rechazar.php' > <input type='submit' name='Cancelado' id='Cancelado' value='Cancelar'> </a>";*/
  echo "</form>";
  echo "<center><form action='vistaDetRese.php' method='post'>
  <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
  <input type='submit' name='Cancelar' value='Cancelar'>
  </form></center>";

   mysqli_close($conexion);

?>