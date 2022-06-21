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
$id_solicitud="";
$id_materia = "";
$fecha_reserva="";
$grupo=array();
$hora_inicio="";
$hora_fin="";
$cap_est="";
$detalle="";

$nom_materia="";
$nivel="";

$sqlP = "SELECT * FROM `reserva`";
$resultP = mysqli_query($conexion, $sqlP);
while ($mostrarP = mysqli_fetch_array($resultP)) {
        if($mostrarP['id_reserva']==$id_reserva){
            $id_solicitud=$mostrarP['id_solicitudes'];
            $id_materia=$mostrarP['id_materia'];
            $fecha_reserva=$mostrarP['fecha_reserva'];
            // $grupo=$mostrarP['grupo'];
            $hora_inicio=$mostrarP['hora_inicio'];
            $hora_fin=$mostrarP['hora_fin'];
            $cap_est=$mostrarP['capEstudiantes'];
            $detalle=$mostrarP['detalle'];
        }
      }

      $sql2 = "SELECT * FROM `materias`"; 
      $result2 = mysqli_query($conexion, $sql2);
      while ($mostrar2 = mysqli_fetch_array($result2)) {
        if ($mostrar2['id_materia'] == $id_materia) {
          $codigo_materia = $mostrar2['codigo_materia'];
          $nom_materia=$mostrar2['nombre_materia'];
          $nivel=$mostrar2['nivel'];
         }
      }

  if($estado=="pendiente"){
   // $sql2="UPDATE `solicitud` SET estados='Rechazado' WHERE id_solicitud='$id_solicitud'";
   // $result2=mysqli_query($conexion,$sql2);
  $sql="INSERT INTO `reservas_atendidas` (`id_reserva_atendida`, `id_reserva`, `fecha_atendida`, `estado`, `detalle`, `id_aula`) VALUES (null,'$id_reserva',null, 'rechazado','$texto',0);";
  $result=mysqli_query($conexion,$sql);
   
 // $insertar2="INSERT INTO `rechazados` (`Id`, `Doecente`, `Fecha`, `Materia`, `Motivo_Rechazo`) VALUES (NULL, '$docente', '2022-04-13', '$materia', '$texto')";
  if($result){
    $nombreDoc = "SELECT nombre_docente FROM reserva, solicitudes, docentes WHERE reserva.id_reserva = $id_reserva AND reserva.id_solicitudes = solicitudes.id_solicitudes AND solicitudes.id_docente = docentes.id_docente";
    $correoDoc = "SELECT correo FROM reserva, solicitudes, docentes WHERE reserva.id_reserva = $id_reserva AND reserva.id_solicitudes = solicitudes.id_solicitudes AND solicitudes.id_docente = docentes.id_docente";
    
    $nombresql = mysqli_query($conexion, $nombreDoc);
    $correosql = mysqli_query($conexion, $correoDoc);
    $nombre_docente = mysqli_fetch_array($nombresql);
    $correo_docente = mysqli_fetch_array($correosql);


  //   if(isset($_POST["Enviar"])){
  //     $grupos=unserialize($_POST["grupo"]);
  //     $nombre=unserialize($_POST["nombre"]);
  //     $correo=unserialize($_POST["correo"]);

  //   if(count($grupo)>1){
  //     for($i=0; $i<count($grupo); $i++){
  //         $mail = new PHPMailer(true);
  //             try {
  //                 $mail->isSMTP();
  //                 $mail->Host = 'smtp.gmail.com';
  //                 $mail->SMTPAuth = true;
  //                 $mail->Username = 'gerfsoftware.srl@gmail.com';
  //                 $mail->Password = 'ozkerkuevtskural';
  //                 $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  //                 $mail->Port = 587;
          
  //                 $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
  //                 $mail->addCC($correo[$i]);
  //                 $mail->addAddress(trim($correo[$i]), trim($nombre[$i]));
          
  //                 $mail->isHTML(true);
  //                 $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
  //                 $mail->Body = '<h4>'.$nombre[$i].'</h4>' .
  //                               '<p>
  //                                   <h3>Su solicitud de reserva de aula fue rechazada.</h3><br>
  //                                   <b>Motivo de rechazo: </b>'. $texto. '<br>'.
  //                                   'Materia: '. $nom_materia. '<br>'.
  //                                   'Fecha de Reserva: '. $fecha_reserva . '<br>' .
  //                                   'Grupo: '. $grupo. '<br>' .
  //                                   'Hora Inicio: '. $hora_inicio. '<br>' .
  //                                   'Hora Fin: '. $hora_fin. '<br>' .
  //                                   'Cantidad de estudiantes: '. $cap_est . '<br>' .
  //                                   'Detalle: '. $detalle. '<br>'.
  //                               '</p>';
  //                 $mail->send();
  //             } catch(Exception $e) {
  //                 echo '<script language="javascript">alert($correo_docente);</script>';
  //             }
  //     }
  //   }else{
  //     $mail = new PHPMailer(true);
  //       try {
  //           $mail->isSMTP();
  //           $mail->Host = 'smtp.gmail.com';
  //           $mail->SMTPAuth = true;
  //           $mail->Username = 'gerfsoftware.srl@gmail.com';
  //           $mail->Password = 'ozkerkuevtskural';
  //           $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  //           $mail->Port = 587;
    
  //           $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
  //           $mail->addCC($correo[0]);
  //           $mail->addAddress($correo[0], $nombre[0]);
    
  //           $mail->isHTML(true);
  //           $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
  //           $mail->Body = '<h4>'. $nombre[0] .'</h4>' .
  //                               '<p>
  //                                   <h3>Su solicitud de reserva de aula fue rechazada.</h3><br>
  //                                   <b>Motivo de rechazo: </b>'. $texto. '<br>'.
  //                                   'Materia: '. $nom_materia. '<br>'.
  //                                   'Fecha de Reserva: '. $fecha_reserva . '<br>' .
  //                                   'Grupo: '. $grupo. '<br>' .
  //                                   'Hora Inicio: '. $hora_inicio. '<br>' .
  //                                   'Hora Fin: '. $hora_fin. '<br>' .
  //                                   'Cantidad de estudiantes: '. $cap_est . '<br>' .
  //                                   'Detalle: '. $detalle. '<br>'.
  //                               '</p>';
  //           $mail->send();
  //       } catch(Exception $e) {
  //          echo '<script language="javascript">alert($correo_docente);</script>';
  //       }
  //   }
  // }
  $query = mysqli_query($conexion, "SELECT * FROM auxiliar, aulas WHERE aulas.id_aula = auxiliar.id_aula");
            $result = mysqli_num_rows($query);
            $aula=array();
            if($result > 0) {
                  while($data = mysqli_fetch_array($query)) {    
                      array_push($aula, $data["codigo_aula"]);
                  }
            }
  $aulas = implode(", ", $aula);

  $consultar_grupo="SELECT * FROM `reserva` WHERE id_reserva = $id_reserva AND id_solicitudes = $id_solicitud";
  $resultado_consulta_grupo=mysqli_query($conexion, $consultar_grupo);
  while(($grupo_capturado = mysqli_fetch_row($resultado_consulta_grupo)) == true){
    $grupos=json_decode($grupo_capturado[3]);
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
  if(count($grupos)>1){
    for($i=0; $i<count($grupos); $i++){
        $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'gerfsoftware.srl@gmail.com';
                $mail->Password = 'ozkerkuevtskural';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
        
                $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
                $mail->addCC($correos[$i]);
                $mail->addAddress(trim($correos[$i]), trim($nombres[$i]));
        
                $mail->isHTML(true);
                $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
                $mail->Body = '<h4>'. $nombres[$i] . '</h4>
                                  <p>
                                        <h3>Su solicitud de reserva de aula fue rechazada.</h3><br> 
                                        <b>Motivo de rechazo: </b>'. $texto. '<br>'.'
                                        Materia: ' . $nom_materia . '<br>
                                        Fecha de reserva: ' . $fecha_reserva . '<br
                                        Hora Inicio: ' . $hora_inicio . '<br>
                                        Hora Fin: ' . $hora_fin . '<br>
                                        Cantidad de estudiantes: ' . $cap_est .'<br>
                                        Detalle: ' . $detalle .
                                  '</p>';
                $mail->send();
            } catch(Exception $e) {
                //echo '<script language="javascript">alert("Error");</script>';
            }
    }
}else{
    $mail = new PHPMailer(true);
      try {
          $mail->isSMTP();
          $mail->Host = 'smtp.gmail.com';
          $mail->SMTPAuth = true;
          $mail->Username = 'gerfsoftware.srl@gmail.com';
          $mail->Password = 'ozkerkuevtskural';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
          $mail->Port = 587;
  
          $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
          $mail->addCC($correos[0]);
          $mail->addAddress($correos[0], $nombres[0]);
  
          $mail->isHTML(true);
          $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
          $mail->Body = '<h4>'. $nombres[0] . '</h4>
                            <p>
                                  <h3>Su solicitud de reserva de aula fue rechazada.</h3><br> 
                                  <b>Motivo de rechazo: </b>'. $texto. '<br>'.'
                                  Materia: ' . $nom_materia . '<br
                                  Fecha de reserva: ' . $fecha_reserva . '<br
                                  Hora Inicio: ' . $hora_inicio . '<br
                                  Hora Fin: ' . $hora_fin . '<br
                                  Cantidad de estudiantes: ' . $cap_est .'<br
                                  Detalle: ' . $detalle .
                            '</p>';
          $mail->send();
      } catch(Exception $e) {
         // echo '<script language="javascript">alert("Error);</script>';
      }
}


    // $mail = new PHPMailer(true);

    // try {
    //   $mail->isSMTP();
    //   $mail->Host = 'smtp.gmail.com';
    //   $mail->SMTPAuth = true;
    //   $mail->Username = 'gerfsoftware.srl@gmail.com';
    //   $mail->Password = 'ozkerkuevtskural';
    //   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    //   $mail->Port = 587;

    //   $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
    //   $mail->addAddress($correo_docente[0]);
    //   $mail->addCC($correo_docente[0]);
      

    //   $mail->isHTML(true);
    //   $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
    //   $mail->Body = '<h4>'.$nombre_docente[0].'</h4>' .
    //     '<p>
    //         <h3>Su solicitud de reserva de aula fue rechazada.</h3><br>
    //         <b>Motivo de rechazo: </b>'. $texto. '<br>'.
    //         'Materia: '. $nom_materia. '<br>'.
    //         'Fecha de Reserva: '. $fecha_reserva . '<br>' .
    //         'Grupo: '. $grupo. '<br>' .
    //         'Hora Inicio: '. $hora_inicio. '<br>' .
    //         'Hora Fin: '. $hora_fin. '<br>' .
    //         'Cantidad de estudiantes: '. $cap_est . '<br>' .
    //         'Detalle: '. $detalle. '<br>'.
    //     '</p>';
    //   $mail->send();

    // } catch(Exception $e) {

    // }
    
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
  mysqli_close($conexion);
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