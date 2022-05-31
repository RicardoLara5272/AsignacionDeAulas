<?php
header('Content-Type: application/json');

require("../conexiones/conexion.php");

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$fecha_solicitud=date("Y-m-d");
$estado="Pendiente";

$id_docente=(isset($_POST['id_doc'])) ? $_POST['id_doc'] : '';

switch ($_GET['accion']) {
    case 'listar':
        $datos = "SELECT r.id_pendientes, m.nombre_materia, r.grupo, r.fecha_reserva, r.hora_inicio, r.hora_fin, r.capEstudiantes, r.detalle FROM reserva_pendientes r INNER JOIN materias m ON r.id_materia=m.id_materia";
        $resultado = $conexion->prepare($datos);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        print json_encode($data, JSON_UNESCAPED_UNICODE);
        $conexion = NULL;
        break;
        
    case 'agregar':
            $respuesta = "INSERT INTO reserva_pendientes(id_materia, grupo, fecha_reserva, hora_inicio, hora_fin, capEstudiantes, detalle, id_docente) VALUES ( $_POST[id_materia], '$_POST[grupo]', '$_POST[fecha_reserva]', '$_POST[hora_inicio]', '$_POST[hora_fin]', $_POST[capEstudiantes], '$_POST[detalle]', $_POST[id_doc])";
            $resultado = $conexion->prepare($respuesta);
            $resultado->execute();
            $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
            print json_encode($data, JSON_UNESCAPED_UNICODE);
            break;
        
    case 'borrar':
        $respuesta = "DELETE FROM reserva_pendientes WHERE id_pendientes=$_GET[id_pendientes]";
        $resultado = $conexion->prepare($respuesta);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        print json_encode($data, JSON_UNESCAPED_UNICODE);
        break;

    case 'consultar':
        $datos = "SELECT id_pendientes, id_materia, grupo, fecha_reserva, hora_inicio, hora_fin, capEstudiantes, detalle, id_docente FROM reserva_pendientes WHERE id_pendientes=$_GET[id_pendientes]";
        $resultado = $conexion->prepare($datos);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        print json_encode($data, JSON_UNESCAPED_UNICODE);
        break;

    case 'agregarSolicitud':
        $respuesta = "INSERT INTO solicitudes(fecha_solicitud, estado, id_docente) VALUES ( '$fecha_solicitud', '$estado', $id_docente)";
        $resultado = $conexion->prepare($respuesta);
        $resultado->execute();

        $consulta="SELECT Max(id_solicitudes) as id_solicitudes FROM solicitudes";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        $ultimaSolicitud=$data[0]['id_solicitudes'];

        $consulta="SELECT id_pendientes, id_materia, grupo, fecha_reserva, hora_inicio, hora_fin, capEstudiantes, detalle FROM reserva_pendientes WHERE reserva_pendientes.id_docente='$id_docente'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $items=$resultado->fetchAll(PDO::FETCH_ASSOC);

        foreach($items as $d){
            $id_solicitudes=$ultimaSolicitud;
            $id_materia=$d['id_materia'];
            $grupo=$d['grupo'];
            $fecha_reserva=$d['fecha_reserva'];
            $hora_inicio=$d['hora_inicio'];
            $hora_fin=$d['hora_fin'];
            $capEstudiantes=$d['capEstudiantes'];
            $detalle=$d['detalle'];
            $consulta="INSERT INTO reserva(id_solicitudes, id_materia, grupo, fecha_reserva, hora_inicio, hora_fin, capEstudiantes, detalle) VALUES ('$id_solicitudes','$id_materia','$grupo','$fecha_reserva','$hora_inicio','$hora_fin','$capEstudiantes','$detalle')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        $consulta = "DELETE FROM reserva_pendientes WHERE id_docente='$id_docente'";		
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();


        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        print json_encode($data, JSON_UNESCAPED_UNICODE);
        break;
}
