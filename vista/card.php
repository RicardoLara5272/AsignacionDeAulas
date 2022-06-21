<?php 
  
  $id_aula= $_POST['id_aula'];
  $id_reserva= $_POST['id_reserva'];
  $db_host = "localhost";
  $db_nombre = "asignacionaulas";
  $db_usuario = "root";
  $db_contra = "";
  $conexion = mysqli_connect($db_host, $db_usuario, $db_contra, $db_nombre);
  $sqlv = "INSERT INTO `auxiliar` (`id_aula`) VALUES ('$id_aula')";
  $result=mysqli_query($conexion,$sqlv);
  echo "<form name='envia' method='POST' action='asignaciones_Conjutas.php'>
<input type=hidden name=id_solicitud_Pend value=$id_reserva>
</form>
<script language="."JavaScript".">
document.envia.submit()
</script>";
?>