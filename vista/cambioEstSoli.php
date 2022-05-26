<?php 
include("../config/db.php");
$id = $_POST['idSolicitud']; 
$sentenciaSQL= $conexion->prepare("UPDATE solicitudes SET estado = 'revisado' WHERE id_solicitudes = $id");
$sentenciaSQL->execute();
?>