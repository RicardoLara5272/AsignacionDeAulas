<?php

require_once("../conexiones/conexion.php");
require_once("../modelo/Grupo.php");
//require_once("../config/headers.php");

$grupo = new Grupo();
// php://input es una manera de leer datos del cuerpo de la peticion realizada
$body = json_decode(file_get_contents("php://input"),true);

switch ($_GET['opcion']) {
        case 'getGrupo':
        $ex = $grupo -> getGrupo($body['id_materia'], $body['id_docente']);
        echo json_encode($ex);
        break;
}
?>