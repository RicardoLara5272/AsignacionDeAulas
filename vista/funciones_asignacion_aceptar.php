<?php 

include("../config/db.php");
ini_set("pcre.jit", "0");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
    
require 'librerias/phpmailer/src/PHPMailer.php';
require 'librerias/phpmailer/src/SMTP.php';
require 'librerias/phpmailer/src/Exception.php';

$id_solicitud=$_POST["id_solicitud"];
$id_reserva=(int)$_POST['id_reserva2'];
$fecha_solicitud=$_POST["fecha_solicitud"];
$fecha_reserva=$_POST["fecha_reserva"];
$cantidad_estudiantes=$_POST["cantidad_estudiantes"];
$nombre=@unserialize($_POST["nombre"]);
$las_aulas=@unserialize($_POST["las_aulas"]);
$grupo=@unserialize($_POST["grupo"]);
$correo=@unserialize($_POST["correo"]);
$IDsDocente=@unserialize($_POST["idCorrecto"]);
$grupo_ordenado=@unserialize($_POST["ordenado"]);
$mensaje=$_POST["mensaje"];
//$id_materia=$_POST["materia"];
$materia=$_POST["la_materia"];
var_dump($_REQUEST);
$id_materia='';
//consultamos aulas
$consultar_grupo = $conexion->prepare("SELECT * FROM reserva WHERE id_reserva ='" . $id_reserva . "' AND id_solicitudes =" . $id_solicitud);
$consultar_grupo->execute();
$grupo = $consultar_grupo->fetchAll(PDO::FETCH_ASSOC);
foreach ($grupo as $mostrar_grupo) {
  $grupos = json_decode($mostrar_grupo['grupo']);
  $id_materia = $mostrar_grupo['id_materia'];
}

//obtenemos los nombres de los docentes
$IDsDocente=array();
  $nombres=array();
  $correos=array();
  $grupo_ordenado=array();
  $IDs=array();
  

  $IDdocente=$conexion->prepare("SELECT * FROM `docente_materia` WHERE id_materia = $id_materia");
  $IDdocente->execute();
  $el_id_docente=$IDdocente->fetchAll(PDO::FETCH_ASSOC);

  foreach($el_id_docente as $elDocente) {
    for($g=0; $g<count($grupo); $g++){
      if($elDocente['id_grupo'] == $grupo[$g]){
      $IDs[]=$elDocente['id_docente'];
        $LosDocentes=$conexion->prepare("SELECT * FROM docentes ");
        $LosDocentes->execute();
        $nombre_docente=$LosDocentes->fetchAll(PDO::FETCH_ASSOC);
        foreach($nombre_docente as $nom_docente) {
          if($nom_docente['id_docente']==$elDocente['id_docente']){
            $IDsDocente[]=$nom_docente['id_docente'];
            $remplazo=$nom_docente['nombre_docente'];
            $detectar=mb_detect_encoding($remplazo, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
            $remplazo = iconv($detectar, 'UTF-8', $remplazo);
            $nombres[]=$remplazo;
            $correos[]=$nom_docente['correo'];
            $grupo_ordenado[]=$grupo[$g];
          }
        }
      }
    }
  }
//

$consulta_auxiliar=$conexion->prepare("SELECT * FROM auxiliar");
$consulta_auxiliar->execute();
$fila=$consulta_auxiliar->fetchAll(PDO::FETCH_ASSOC);
foreach($fila as $el_auxiliar) {
      $id_aulaa=(int)$el_auxiliar['id_aula'];
      $guardar=$conexion->prepare("INSERT INTO reservas_atendidas (id_reserva, estado, detalle, id_aula) VALUES ('$id_reserva', 'Aceptado', '$mensaje', '$id_aulaa')");
      $guardar->execute();
}

$aulas = implode(", ", $las_aulas);

if(count($grupo)>1){
    for($i=0; $i<count($grupo); $i++){
        global $aulas;
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
            $mail->addCC($correo[$i]);
            $mail->addAddress($correo[$i]);
                      
            $mail->isHTML(true);
            $mail->Subject = 'Respuesta a solicitud de asignacion de aula compartida.';
            $mail->Body = '<h4>'. $nombres[$i] .'</h4><p>Se aprobó su solicitud compartida de reserva de aula para la toma de examen para la Materia: ' . $materia . ' <br>Grupo: ' . $grupo_ordenado[$i] . '<br> Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud .'<br>Fecha de la reserva: ' . $fecha_reserva .'<br>Cantidad de estudiantes: ' . $cantidad_estudiantes .'<br>Mensaje: ' . $mensaje .'</p>';
            $mail->send();
        } catch(Exception $e) {
            echo '<script language="javascript">alert("error");</script>';
        }
    }
}else{
    global $aulas;
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
        $mail->addCC($correo[0]);
        $mail->addAddress($correo[0]);
                
        $mail->isHTML(true);
        $mail->Subject = 'Respuesta a solicitud de asignacion de aula individual.';
        $mail->Body = '<h4>'. $nombres[0] .'</h4><p>Se aprobó su solicitud individual de reserva de aula para la toma de examen para la Materia: ' . $materia . ' <br>Grupo: ' . $grupo[0] . '<br> Aulas: ' . $las_aulas[0] . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud .'<br>Fecha de la reserva: ' . $fecha_reserva .'<br>Cantidad de estudiantes: ' . $cantidad_estudiantes .'<br>Mensaje: ' . $mensaje .'</p>';
        $mail->send();
    } catch(Exception $e) {
        echo '<script language="javascript">alert("error");</script>';
    }
}

$borrar_auxiliar=$conexion->prepare("DELETE FROM auxiliar");
$borrar_auxiliar->execute();

echo '<script language="javascript">alert("asignacion realizada exitosamente ");</script>';
echo "<script language='javascript'>window.location.replace('http://asignaciondeaulas/vista/vistaDetRese.php?id_solicitud_Pend={$_POST['id_solicitud_Pend']}');</script>";
?>