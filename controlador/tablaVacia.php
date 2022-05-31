<?php
$id_docente = (isset($_POST['id_doc'])) ? $_POST['id_doc'] : '';
include_once '../conexiones/conexion.php';

$objeto = new Conexion();
$conexion = $objeto->Conectar();
$consulta = "SELECT * FROM reserva_pendientes WHERE id_docente='$id_docente'";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data=$resultado->fetchAll(PDO::FETCH_ASSOC);  

$fila = empty($data);

print json_encode($fila, JSON_UNESCAPED_UNICODE); //enviar el array final en formato json a JS
$conexion = NULL;
?>