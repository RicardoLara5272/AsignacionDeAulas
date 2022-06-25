<?php
ini_set("pcre.jit", "0");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'librerias/phpmailer/src/PHPMailer.php';
require 'librerias/phpmailer/src/SMTP.php';
require 'librerias/phpmailer/src/Exception.php';
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
$conexion = $db;

$sql = "INSERT INTO reservas_atendidas(id_reserva, fecha_atendida, estado, detalle, id_aula) VALUES (:id_reserva, :fecha_atendida, :estado, :detalle, :id_aula)";
$stmt = $conexion->prepare($sql);
$stmt->execute(array(
  ':id_reserva' => $_POST['id_reserva'],
  ':fecha_atendida' => date("Y-m-d H:m:s"),
  ':estado' => 'Rechazado',
  ':detalle' => $_POST['motivo'],
  ':id_aula' => null
));
$id = $conexion->lastInsertId('id_reserva_atendida');
header("Location:http://asignaciondeaulas/vista/vistaDetRese.php?id_solicitud_Pend=".trim($_POST['id_solicitud_Pend']));
die;
