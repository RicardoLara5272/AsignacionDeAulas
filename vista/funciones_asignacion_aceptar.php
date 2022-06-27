<?php

include("../config/db.php");
ini_set("pcre.jit", "0");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/src/PHPMailer.php';
require 'librerias/phpmailer/src/SMTP.php';
require 'librerias/phpmailer/src/Exception.php';
ini_set("memory_limit", "-1");
set_time_limit(0);
$id_reserva = (int)$_POST['id_reserva'];
$mensaje = $_POST["mensaje"];
//var_dump($_REQUEST);
//obtenemos los nombres de los docentes
$id_solicitud;
$cantidad_estudiantes;
$grupos;
$id_materia;
$hora_inicio;
$hora_fin;
$fecha_reserva;
$nombre_materia;
$fecha_solicitud;
$IDsDocente = array();
$nombres = array();
$correos = array();
$grupo_ordenado = array();
$IDs = array();

$aulas_asignadas = array();

$consultar_grupo = $conexion->prepare("SELECT * FROM `reserva` WHERE id_reserva = $id_reserva");
$consultar_grupo->execute();
$grupo = $consultar_grupo->fetchAll(PDO::FETCH_ASSOC);
foreach ($grupo as $mostrar_grupo) {
  global $id_solicitud;
  global $cantidad_estudiantes;
  global $grupos;
  global $id_materia;
  global $hora_inicio;
  global $hora_fin;
  global $fecha_reserva;
  $id_solicitud = $mostrar_grupo["id_solicitudes"];
  $cantidad_estudiantes = $mostrar_grupo["capEstudiantes"];
  $grupos = json_decode($mostrar_grupo['grupo']);
  $id_materia = (int)$mostrar_grupo['id_materia'];
  $hora_inicio = $mostrar_grupo["hora_inicio"];
  $hora_fin = $mostrar_grupo["hora_fin"];
  $fecha_reserva = $mostrar_grupo["fecha_reserva"];
}

$consultar_fecha_solicitud = $conexion->prepare("SELECT * FROM `solicitudes` WHERE id_solicitudes = $id_solicitud");
$consultar_fecha_solicitud->execute();
$fech = $consultar_fecha_solicitud->fetchAll(PDO::FETCH_ASSOC);
foreach ($fech as $fecha) {
  global $fecha_solicitud;
  $fecha_solicitud = $fecha["fecha_solicitud"];
}

$nom_materia = $conexion->prepare("SELECT * FROM `materias`");
$nom_materia->execute();
$la_materia = $nom_materia->fetchAll(PDO::FETCH_ASSOC);
foreach ($la_materia as $mamateria) {
  global $id_materia;
  if ($mamateria["id_materia"] == $id_materia) {
    global $nom_materia;
    $rempl = $mamateria['nombre_materia'];
    $detectamos = mb_detect_encoding($rempl, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
    $remplazo = iconv($detectamos, 'UTF-8', $rempl);
    $nombre_materia = $rempl;
  }
}

$IDdocente = $conexion->prepare("SELECT * FROM `docente_materia` WHERE id_materia = $id_materia");
$IDdocente->execute();
$el_id_docente = $IDdocente->fetchAll(PDO::FETCH_ASSOC);

foreach ($el_id_docente as $elDocente) {
  for ($g = 0; $g < count($grupos); $g++) {
    if ($elDocente['id_grupo'] == $grupos[$g]) {
      $IDs[] = $elDocente['id_docente'];
      $LosDocentes = $conexion->prepare("SELECT * FROM docentes ");
      $LosDocentes->execute();
      $nombre_docente = $LosDocentes->fetchAll(PDO::FETCH_ASSOC);
      foreach ($nombre_docente as $nom_docente) {
        if ($nom_docente['id_docente'] == $elDocente['id_docente']) {
          global $IDsDocente;
          global $nombres;
          global $correos;
          global $grupo_ordenado;
          global $IDs;
          $IDsDocente[$g] = $nom_docente['id_docente'];
          $remplazo = $nom_docente['nombre_docente'];
          $detectar = mb_detect_encoding($remplazo, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
          $remplazo = iconv($detectar, 'UTF-8', $remplazo);
          $nombres[$g] = $remplazo;
          $correos[$g] = $nom_docente['correo'];
          $grupo_ordenado[$g] = $grupos[$g];
        }
      }
    }
  }
}
$consulta_auxiliar = $conexion->prepare("SELECT * FROM auxiliar");
$consulta_auxiliar->execute();
$fila = $consulta_auxiliar->fetchAll(PDO::FETCH_ASSOC);
foreach ($fila as $el_auxiliar) {
  $id_aulaa = (int)$el_auxiliar['id_aula'];
  $stmt = $conexion->prepare('INSERT INTO reservas_atendidas (id_reserva, estado, detalle, id_aula) VALUES (:id_reserva, :estado, :detalle, :id_aula)');
  $stmt->execute(array(
    ':id_reserva' => $id_reserva,
    ':estado' => 'Aceptado',
    ':detalle' => $mensaje,
    ':id_aula' => $id_aulaa
  ));
  $id = $db->lastInsertId('id_docente');
}
$consultar_aula = $conexion->prepare("SELECT * FROM aulas ");
$consultar_aula->execute();
$codigo_aula = $consultar_aula->fetchAll(PDO::FETCH_ASSOC);
$numero = 1;
$numero2 = 0;
foreach ($codigo_aula as $cod_aula) {
  $consultar_auxiliar = $conexion->prepare("SELECT * FROM auxiliar ");
  $consultar_auxiliar->execute();
  $codigo_auxiliar = $consultar_auxiliar->fetchAll(PDO::FETCH_ASSOC);
  foreach ($codigo_auxiliar as $cod_auxiliar) {
    if ($cod_auxiliar['id_aula'] == $cod_aula['id_aula']) {
      $aulas_asignadas[] = $cod_aula['codigo_aula'];
      $numero += 1;
      $numero2 += 1;
    }
  }
}
$aulas = implode(", ", $aulas_asignadas);
if (count($grupos) > 1) {
  global $id_solicitud;
  global $cantidad_estudiantes;
  global $grupos;
  global $id_materia;
  global $hora_inicio;
  global $hora_fin;
  global $fecha_reserva;
  global $id_materia;
  global $nom_materia;
  for ($i = 0; $i < count($grupos); $i++) {
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
      $mail->addCC($correos[$i]);
      $mail->addAddress($correos[$i]);

      $mail->isHTML(true);
      $mail->Subject = 'Respuesta a solicitud de asignacion de aula compartida.';
      $mail->Body = '<h4>' . $nombres[$i] . '</h4><p>Se aprobó su solicitud compartida de aula para la toma de examen para la materia : ' . $nombre_materia . ' <br>grupo ' . $grupos[$i] . '<br> Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud . '<br>Cantidad de estudiantes: ' . $cantidad_estudiantes . '<br>Mensaje: ' . $mensaje . '</p>';
      $mail->send();
    } catch (Exception $e) {
      echo '<script language="javascript">alert("error");</script>';
    }
  }
} else {
  global $id_solicitud;
  global $cantidad_estudiantes;
  global $grupos;
  global $id_materia;
  global $hora_inicio;
  global $hora_fin;
  global $fecha_reserva;
  global $id_materia;
  global $nom_materia;
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
    $mail->addCC($correos[0]);
    $mail->addAddress($correos[0]);

    $mail->isHTML(true);
    $mail->Subject = 'Respuesta a solicitud de asignacion de aula individual.';
    $mail->Body = '<h4>' . $nombres[0] . '</h4><p>Se aprobó su solicitud individual de aula para la toma de examen para la materia : ' . $nombre_materia . ' <br>Grupo ' . $grupos[0] . '<br>Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud . '<br>Cantidad de estudiantes: ' . $cantidad_estudiantes . '<br>Mensaje: ' . $mensaje . '</p>';
    $mail->send();
  } catch (Exception $e) {
    echo '<script language="javascript">alert("error");</script>';
  }
}
$borrar_auxiliar = $conexion->prepare("DELETE FROM auxiliar");
$borrar_auxiliar->execute();
echo '<script language="javascript">alert("asignacion realizada exitosamente ");</script>';
echo "<script language='javascript'>window.location.replace('http://asignaciondeaulas/vista/vistaDetRese.php?id_solicitud_Pend={$_POST['id_solicitud_Pend']}');</script>";
