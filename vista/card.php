<?php 
  require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
  //if not logged in redirect to login page
  $conexion = $db;
  $id_aula= $_POST['id_aula'];
  $id_reserva= $_POST['id_reserva'];
  
  $consulta="INSERT INTO `auxiliar` (`id_aula`) VALUES ('$id_aula')";
  $resultado = $conexion->prepare($consulta);
  $resultado->execute();
  echo "<form name='envia' method='POST' action='asignaciones_Conjutas.php'>
          <input type=hidden name=id_solicitud_Pend value=$id_reserva>
          </form>
        <script language="."JavaScript".">
          document.envia.submit()
        </script>";
?>