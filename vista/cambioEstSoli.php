<?php 
include("../config/db.php");
$id = $_POST['idSolicitud']; 
$id_administrador = $_POST['idAdmin'];
//actualizar estado de solicitud tabla solicitud
$sentenciaSQL= $conexion->prepare("UPDATE solicitudes SET estado = 'revisado' WHERE id_solicitudes = $id");
$sentenciaSQL->execute();
//insertar fecha revisada en tabla solictud_revisada
$fecha_actual = date('Y-m-d', time());
$sentenciaSQL= $conexion->prepare(" INSERT INTO `solicitudes_atendidas` (id_solicitud_atendida, id_solicitud, fecha_atendida, id_administrador) VALUES (null, $id, '$fecha_actual', '$id_administrador') ");
$sentenciaSQL->execute();   
?>                                      