<?php 

include("../config/db.php");
ini_set("pcre.jit", "0");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
    
require 'librerias/phpmailer/src/PHPMailer.php';
require 'librerias/phpmailer/src/SMTP.php';
require 'librerias/phpmailer/src/Exception.php';
var_dump($_POST["nombre"]);
$id_solicitud=$_POST["id_solicitud"];
$id_reserva=(int)$_POST['id_reserva2'];
$fecha_solicitud=$_POST["fecha_solicitud"];
$fecha_reserva=$_POST["fecha_reserva"];
$cantidad_estudiantes=$_POST["cantidad_estudiantes"];
$nombre=@unserialize($_POST["nombre"]);
$las_aulas=@unserialize($_POST["las_aulas"]);
//$detalle=$_POST["detalle"];
//$id_docente_materia=$_POST["id_docente_materia"];
//$id_docente=$_POST["id_docente"];
$grupo=@unserialize($_POST["grupo"]);
//$apellido=$_POST["apellido"];
$correo=@unserialize($_POST["correo"]);
$IDsDocente=@unserialize($_POST["idCorrecto"]);
$grupo_ordenado=@unserialize($_POST["ordenado"]);
//$aula=$_POST["aula"];
$mensaje=$_POST["mensaje"];
//$id_materia=$_POST['id_materia'];
//$hora_inicio=$_POST["hora_inicio"];
//$hora_fin=$_POST["hora_fin"];

$consulta_auxiliar=$conexion->prepare("SELECT * FROM auxiliar");
$consulta_auxiliar->execute();
$fila=$consulta_auxiliar->fetchAll(PDO::FETCH_ASSOC);
foreach($fila as $el_auxiliar) {
      $id_aulaa=(int)$el_auxiliar['id_aula'];
      $guardar=$conexion->prepare("INSERT INTO reservas_atendidas (id_reserva, estado, detalle, id_aula) VALUES ('$id_reserva', 'aceptado', '$mensaje', '$id_aulaa')");
      $guardar->execute();
}
var_dump($_POST);
for($a=0;$a<count($grupo);$a++){
      echo "<p>".  count($nombre) ."</p>";
}
            // esta mal el $nombre[$a]
$aulas = implode(", ", $las_aulas);

if(count($grupo)>1){
                  for($i=0; $i<count($grupo); $i++){
                        global $aulas;
                     /* $mail = new PHPMailer(true);
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
                              $mail->Body = '<h4>Docente</h4><p>Se aprobó su solicitud de aula para la toma de examen.<br> Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud .'<br>Cantidad de estudiantes: ' . $cantidad_estudiantes .'Mensaje: ' . $mensaje .'</p>';
                              $mail->send();
                          } catch(Exception $e) {
                              echo '<script language="javascript">alert("error");</script>';
                          }*/
                  }
            }else{
                  global $aulas;
                  $mail = new PHPMailer(true);
                   /* try {
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
                        $mail->Body = '<h4>Docente</h4><p>Se aprobó su solicitud de aula para la toma de examen.<br> Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud .'<br>Cantidad de estudiantes: ' . $cantidad_estudiantes .'Mensaje: ' . $mensaje .'</p>';
                        $mail->send();
                    } catch(Exception $e) {
                        echo '<script language="javascript">alert("error");</script>';
                    }*/
            }

$borrar_auxiliar=$conexion->prepare("DELETE FROM auxiliar");
$borrar_auxiliar->execute();

echo '<script language="javascript">alert("asignacion realizada exitosamente ");</script>';
echo "<script language='javascript'>window.location.replace('http://asignaciondeaulas/vista/vistaDetRese.php?id_solicitud_Pend=".trim($_POST["id_solicitud"]).")</script>";
?>