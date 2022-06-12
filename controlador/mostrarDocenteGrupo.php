<?php

require_once("../conexiones/conexion.php");
require_once("../modelo/Docente.php");
//require_once("../config/headers.php");

$grupo = new Docente();
// php://input es una manera de leer datos del cuerpo de la peticion realizada
$body = json_decode(file_get_contents("php://input"),true);

switch ($_GET['opcion']) {
        case 'getDocenteGrupo':
        $ex = $grupo -> getDocenteGrupo($body['id_materia']);
        echo json_encode($ex);
        break;
}
?>