<?php include("../config/db.php");
  $id_reserva= $_POST['id_reserva2'];
  $id_aula= $_POST['id_aula'];

  $sentenciaSQL= $conexion->prepare("INSERT INTO `auxiliar` (`id_aula`) VALUES ('$id_aula')");
  $sentenciaSQL->execute();
  echo "<form name='envia' action='AsignacionDeAulas.php' method='post'>
          <input type='hidden' name='id_reserva2' value=".$id_reserva.">".
        "</form> 
        <script language="."JavaScript".">
          document.envia.submit()
        </script>";
?>

