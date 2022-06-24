<?php include("../config/db.php"); ?>
<?php 
  
  $id_solicitud= $_POST['id_solicitud_Pend'];
  $id_reserva= $_POST['id_reserva2'];
  $fecha_solicitud= $_POST['fecha_solicitud'];
  $fecha_reserva= $_POST['fecha_reserva'];
  $capEstudiantes= $_POST['capEstudiantes'];
  $id_docente= $_POST['id_docente'];
  $id_materia= $_POST['id_materia'];
  $grupo= $_POST['grupo'];
  $hora_inicio= $_POST['hora_inicio'];
  $hora_fin= $_POST['hora_fin'];
  $id_aula= $_POST['id_aula'];

  $sentenciaSQL= $conexion->prepare("INSERT INTO `auxiliar` (`id_aula`) VALUES ('$id_aula')");
  $sentenciaSQL->execute();

  echo "<form name='envia' action='AsignacionDeAulas.php' method='post'>
  <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
  <input type='hidden' name='id_reserva2' value=". $id_reserva .">
  <input type='hidden' name='fecha_solicitud' value=". $fecha_solicitud .">
  <input type='hidden' name='fecha_reserva' value=". $fecha_reserva .">
  <input type='hidden' name='capEstudiantes' value=". $capEstudiantes .">
  <input type='hidden' name='id_docente' value=". $id_docente .">
  <input type='hidden' name='id_materia' value=". $id_materia .">
  <input type='hidden' name='grupo' value=". $grupo .">
  <input type='hidden' name='hora_inicio' value=". $hora_inicio .">
  <input type='hidden' name='hora_fin' value=". $hora_fin .">".
  "</form> 
  <script language="."JavaScript".">
document.envia.submit()
</script>";

?>

