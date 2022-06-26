<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
if (!$user->is_logged_in()) {
    header('Location: login.php');
    exit();
}
//define page title
$title = 'Asignaciones';
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
$_POST["fecha"] = date("Y-m-d");
$id_docente = $_SESSION['id_docente'];
$id_materias = 1;
$conexion = $db;
$array_id_solicitudes=array();

//materia y grupo que dicta el docente logeado
$sentenciaSQL = $conexion->prepare(" SELECT id_materia, id_grupo FROM docente_materia WHERE id_docente=$id_docente");
$sentenciaSQL->execute();
$listaMaterias = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
//var_dump($listaMaterias);

//materias que tienen reserva y que dicta el docente logeado 
$sentenciaSQL = $conexion->prepare(" SELECT DISTINCT res.id_solicitudes, res.id_reserva, res.id_materia, res.grupo, docmat.id_docente FROM reserva res INNER JOIN docente_materia docmat ON res.id_materia=docmat.id_materia WHERE docmat.id_docente = $id_docente ");
$sentenciaSQL->execute();
$listaDocente = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
//var_dump($listaDocente);
//$grupo_materia_reserva=array();

foreach($listaMaterias as $key => $materiasDictaDocente){
    $id_materia_dicta_docente=$materiasDictaDocente['id_materia'];
    $grupo_materia_dicta_docente=$materiasDictaDocente['id_grupo'];
    foreach($listaDocente as $key => $docenteMaterias){
        $id_materia_reserva=$docenteMaterias['id_materia'];
        $grupo_materia_reserva=json_decode( $docenteMaterias['grupo']);
        
        if($id_materia_dicta_docente==$id_materia_reserva){
            $cont=0;
            for ($i=0; $i < count($grupo_materia_reserva); $i++) { 
                
                if($grupo_materia_dicta_docente == $grupo_materia_reserva[$i]){
                    $array_id_solicitudes[]=$docenteMaterias['id_solicitudes'];
                $cont++;
                
                }
                

            }
        }
    }
}
$array_id_solicitudes=array_values(array_unique($array_id_solicitudes));
sort($array_id_solicitudes);
?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <br>
                <div class="card-header text-center">
                    <h2>Seguimiento de solicitudes</h2>
                </div>        
                <!-- comienzo de tabla-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Materia</th>
                            <th>Fecha Solicitud</th>
                            <th>Fecha Revisada</th>
                            <th>Estado</th>
                            <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody action="vistaDetRese.php" method="post">
                            <?php 
                                for ($i=0; $i < count($array_id_solicitudes); $i++) { 
                                    $valor=$array_id_solicitudes[$i];
                                    $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE id_solicitudes=$valor");
                                    $sentenciaSQL->execute();
                                    $solicitud = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC)[0];
                            ?>
                                <tr>
                                <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
                                <td> 
                                <?php 
                                $id_solicitud = $solicitud['id_solicitudes'];
                                $sentenciaSQL= $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_solicitud ");
                                $sentenciaSQL->execute();
                                $id_materia= $sentenciaSQL->fetchColumn(2);

                                $sentenciaSQL= $conexion->prepare(" SELECT * FROM materias WHERE id_materia = $id_materia ");
                                $sentenciaSQL->execute();
                                $nombre_materia= $sentenciaSQL->fetchColumn(2);
                                echo $nombre_materia; 
                                ?> 
                            </td>
                                <td> <?php echo $solicitud['fecha_solicitud']; ?></td>
                                <td>
                                <?php
                                    $id_solicit = $solicitud['id_solicitudes'];
                                    $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes_atendidas WHERE id_solicitud = $id_solicit ");
                                    $sentenciaSQL->execute();
                                    $fecha_atend = $sentenciaSQL->fetchColumn(2);
                                    if (empty($fecha_atend)) {
                                        echo 'Aún no se reviso';
                                    } else {
                                        echo $fecha_atend;
                                    }
                                ?>
                                </td>
                                <td class="mayuscula"> <?php echo $solicitud['estado']; ?></td>
                                <td>
                                    <form action="DetalleReserDocent.php" method="post" name="formulario">
                                        <input type="hidden" name="id_solicitud" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                                        <input type="submit" class="btn btn-secondary botton" value="DETALLES">
                                    </form>
                                </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table> <!-- final de tabla-->
                </div>
            </div>
        </div>
    </div>
</main>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>