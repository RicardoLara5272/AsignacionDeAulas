<?php
ini_set("pcre.jit", "0");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/src/PHPMailer.php';
require 'librerias/phpmailer/src/SMTP.php';
require 'librerias/phpmailer/src/Exception.php';

$texto=$_POST['experiencia'];
$id_reserva=$_POST["id_reserva"];
  $db_host="localhost";
  $db_nombre="asignacionaulas";
  $db_usuario="root";
  $db_contra="";
  $conexion=mysqli_connect($db_host,$db_usuario,$db_contra,$db_nombre);
  $estado="pendiente";

  $sql5 = "SELECT * FROM `reservas_atendidas`";
  $result5 = mysqli_query($conexion, $sql5);
  while ($mostrar5 = mysqli_fetch_array($result5)) {
    if ($id_reserva == $mostrar5['id_reserva']) {
          if($mostrar5['estado']=="rechazado"){
            $boolean="rechazado";
            echo "
        <div>
        <h1   style='background:red; color:white; text-align: center;font-family:Verdana, sans-serif;'; > La Solicitud ha sido rechazada anteriormente  
        </div>  </h1>";
          }
          if($mostrar5['estado']=="aceptado"){
            $boolean="aceptado";
            echo "
        <div>
        <h1   style='background:red; color:white; text-align: center;font-family:Verdana, sans-serif;'; > La Solicitud ha sido aceptada anteriormente   
        </div>  </h1>";
          }
    }
  }


  if($estado=="pendiente"){
   // $sql2="UPDATE `solicitud` SET estados='Rechazado' WHERE id_solicitud='$id_solicitud'";
   // $result2=mysqli_query($conexion,$sql2);
  $sql="INSERT INTO `reservas_atendidas` (`id_reserva_atendida`, `id_reserva`, `fecha_atendida`, `estado`, `detalle`) VALUES (null,'$id_reserva',null, 'rechazado','$texto')";
  $result=mysqli_query($conexion,$sql);
   
 // $insertar2="INSERT INTO `rechazados` (`Id`, `Doecente`, `Fecha`, `Materia`, `Motivo_Rechazo`) VALUES (NULL, '$docente', '2022-04-13', '$materia', '$texto')";
  if($result){
    $nombreDoc = "SELECT nombre_docente FROM reserva, solicitudes, docentes WHERE reserva.id_reserva = $id_reserva AND reserva.id_solicitudes = solicitudes.id_solicitudes AND solicitudes.id_docente = docentes.id_docente";
    $correoDoc = "SELECT correo FROM reserva, solicitudes, docentes WHERE reserva.id_reserva = $id_reserva AND reserva.id_solicitudes = solicitudes.id_solicitudes AND solicitudes.id_docente = docentes.id_docente";
    
    $nombresql = mysqli_query($conexion, $nombreDoc);
    $correosql = mysqli_query($conexion, $correoDoc);
    $nombre_docente = mysqli_fetch_array($nombresql);
    $correo_docente = mysqli_fetch_array($correosql);

    $mail = new PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'gerfsoftware.srl@gmail.com';
      $mail->Password = 'gerf2022';
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
      $mail->addAddress($correo_docente[0]);
      $mail->addCC($correo_docente[0]);


      $mail->isHTML(true);
      $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
      $mail->Body = '<h4>'.$nombre_docente[0].'</h4>' .
        '<p>
            Su solicitud de reserva de aula fue rechazada. <br>
            <b>Motivo de rechazo: </b>'. $texto.
        '</p>';
      $mail->send();

    } catch(Exception $e) {

    }
    
      echo "<div  style='background:green;color:white; text-align: center;font-family:Verdana, sans-serif;'>
      <h1 >  SE ENVIO CORRECTAMENTE EL FORMULARIO  </h1>
      </div>";
  }
 else{
     echo "
     <h1   style='background:red; color:white; text-align: center;font-family:Verdana, sans-serif;'; > NO SE ENVIO CORRECTAMENTE EL FORMULARIO   
      </h1>";
 }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificacion de Envio</title>
</head>
<body>     
         <a href="vistaDetPend.php">    <img src="boton_Atras.png" width="70" height="70"  alt=""  style="position:absolute; top: 20%; left: 10%;"> </a>
</body>
</html>