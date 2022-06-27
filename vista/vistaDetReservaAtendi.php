<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
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
$id_sol_DetPend = $_POST['id_solicitud_Revi'];
$data_consultar = [];

$sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_sol_DetPend");
$sentenciaSQL->execute();
$listaReservas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
foreach ($listaReservas as $key => $value) {
    $data_consultar = $value;
    break;
}
$grupos = json_decode($data_consultar['grupo']);
if (count($grupos) > 1) {
    $tipo_solicitud = "Compartido";
} else {
    $tipo_solicitud = "Individual";
}
?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section>
                    <div class="row text-center">
                        <div class="col-lg-12">
                            <br>
                            <h2>Solicitud <?php echo $tipo_solicitud?> nro # <?php echo $id_sol_DetPend; ?> </h2>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <strong><label for="nombre_docente">Solicitado por:</label></strong><br>
                        <?php
                        $sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE id_solicitudes = $id_sol_DetPend");
                        $sentenciaSQL->execute();
                        $id_docente = $sentenciaSQL->fetchColumn(3);
            
                        $sentenciaSQL= $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente ");
                        $sentenciaSQL->execute();
                        $nom_docente = $sentenciaSQL->fetchColumn(2);
                        echo $nom_docente; 
                        ?>
                        <br>
                        <strong><label for="nombre_docente">Revisado por:</label></strong><br>
                        <?php 
                        $sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes_atendidas WHERE id_solicitud = $id_sol_DetPend");
                        $sentenciaSQL->execute();
                        $id_admi = $sentenciaSQL->fetchColumn(3);
                        
                        $sentenciaSQL= $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_admi");
                        $sentenciaSQL->execute();
                        $nombre_admi = $sentenciaSQL->fetchColumn(2);
                        echo $nombre_admi;

                        ?>      
                        <div class="card-header text-center">

                            <h3>Reservas </h3>

                        </div>
                </section>
                <!-- comienzo de tabla-->
                <div class="row justify-content-center">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Materia</th>
                                    <th>Grupo</th>
                                    <th>Cap.</th>
                                    <th>Fecha Reserva</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Final</th>
                                    <th>Estado</th>
                                    <th>Detalle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $indice = 1;
                                $indiceRev = 0;
                                ?>
                                <?php foreach ($listaReservas as $reserva) { ?>
                                    <tr>
                                        <td> <?php echo $indice++; ?> </td>
                                        <td>
                                            <?php
                                            $id_materia = $reserva['id_materia'];
                                            $sentenciaSQL = $conexion->prepare(" SELECT * FROM materias WHERE id_materia = $id_materia ");
                                            $sentenciaSQL->execute();
                                            $nom_materia = $sentenciaSQL->fetchColumn(2);
                                            echo $nom_materia;
                                            ?>
                                        </td>
                                        <th> <?php echo $reserva['grupo']; ?></th>
                                        <th> <?php echo $reserva['capEstudiantes']; ?></th>
                                        <td> <?php echo $reserva['fecha_reserva']; ?> </td>
                                        <td> <?php echo $reserva['hora_inicio']; ?> </td>
                                        <td> <?php echo $reserva['hora_fin']; ?> </td>
                                        <td class="texto">
                                            <?php
                                            //es unico el id_reserva?
                                            $estaditos='';
                                            $id_reserva = $reserva['id_reserva'];
                                            $sentenciaSQL = $conexion->prepare(" SELECT * FROM reservas_atendidas WHERE id_reserva = $id_reserva ");
                                            $sentenciaSQL->execute();
                                            $estado_reserva = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
                                            $estado_reserva_value = '';
                                            $detalle_reserva_value = '';
                                            foreach ($estado_reserva as $key => $value) {
                                                $estado_reserva_value = $value['estado'];
                                                $detalle_reserva_value = $value['detalle'];
                                                break;
                                            }
                                            if(strtolower($estado_reserva_value)=='rechazado'){
                                                $estaditos ='<span class="rojo">'.$estado_reserva_value.'</span>';
                                            }elseif (strtolower($estado_reserva_value)=='aceptado') {
                                                $estaditos ='<span class="verde">'.$estado_reserva_value.'</span>';
                                                $indiceRev++; 
                                            }
                                            echo $estaditos;
                                            /*if ($estado_reserva_value) {
                                                echo $estado_reserva_value;
                                            } else {
                                                echo $estado_reserva_value;
                                                $indiceRev++;
                                            }*/
                                            ?>
                                        </td>
                                        <td class="texto"> <?php echo ($detalle_reserva_value);
                                                ?> </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <div style="text-align:right">
                        <br><br>
                        <a class="btn btn-danger" href="vistaDetRevi.php">SALIR</a>
                    </div>
                </div>
            </div>
        </div>
</main>
<script language="javascript" src="../js/redireccion.js"> </script>

<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>