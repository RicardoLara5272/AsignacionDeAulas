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
$conexion = $db;
$grupos;
$tipo_solicitud;

$sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='Pendiente' ");
$sentenciaSQL->execute();
$listaSolicitudes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);





?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <br>
                <div class="card-header text-center">
                    <h2>Lista de solicitudes Pendientes</h2>
                </div>
                <!-- comienzo de tabla-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nro</th>
                                <th>Nombre</th>
                                <th>Fecha Solicitud</th>
                                <th>Tipo solicitud</th>
                                <th>Estado</th>
                                <th>Urgencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody action="vistaDetRese.php" method="post">
                            <?php foreach ($listaSolicitudes as $index => $solicitud) { ?>
                                <tr>
                                    <td> <?php echo $index+1; ?> </td>
                                    <td>
                                        <?php
                                        $id_docente = $solicitud['id_docente'];
                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente ");
                                        $sentenciaSQL->execute();
                                        $docente = $sentenciaSQL->fetchColumn(2);
                                        echo $docente;
                                        ?>
                                    </td>
                                    <td> <?php echo $solicitud['fecha_solicitud']; ?> </td>
                                    <td><!-- mostrar tipo de solicitud-->
                                    <?php 
                                        $id_solicitudes = $solicitud['id_solicitudes'];
                                        
                                        $sql = "SELECT * FROM `reserva` where id_solicitudes=" . $id_solicitudes;
                                        $query = $conexion->prepare($sql);
                                        $query->execute();
                                        $result_reserva = $query->fetchAll(PDO::FETCH_ASSOC);
                                        $data_consultar = [];
                                        foreach ($result_reserva as $key => $value) {
                                            $data_consultar = $value;
                                            break;
                                        }
                                        $grupos = json_decode($data_consultar['grupo']);
                                        //var_dump($grupos);
                                        if (count($grupos) > 1){
                                            $tipo_solicitud="Compartido";
                                            echo $tipo_solicitud;
                                        }else{
                                            $tipo_solicitud="Individual";
                                            echo $tipo_solicitud;
                                        }
                                        
                                    ?>
                                    </td>
                                    <td> <?php echo $solicitud['estado']; ?> </td>
                                    <td>
                                        <?php
                                        $id_solicitudes = $solicitud['id_solicitudes'];
                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_solicitudes ");
                                        $sentenciaSQL->execute();
                                        $listaReservas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
                                        //ciclo para sacar primer valor
                                        foreach ($listaReservas as $reserva) {
                                            $pri_fecha = $reserva['fecha_reserva'];
                                            $pri_hora = $reserva['hora_inicio'];
                                            $pri_fecha_comp = $pri_fecha . ' ' . $pri_hora;
                                            $comparacion_date = strtotime($pri_fecha_comp) + 21600;
                                            break;
                                        }
                                        //ciclo para sacar el mayor considerando la fecha de hoy 
                                        foreach ($listaReservas as $reserva) {
                                            $fecha_re = $reserva['fecha_reserva'];
                                            $hora_re = $reserva['hora_inicio'];
                                            $fecha_comp_re = $fecha_re . ' ' . $hora_re;
                                            $comp_date = strtotime($fecha_comp_re) + 21600;
                                            if ($comp_date > time() && $comp_date > $comparacion_date) {
                                                $comparacion_date = $comp_date;
                                            }
                                        }
                                        //ciclo para buscar el menor
                                        if ($comparacion_date > time()) {
                                            foreach ($listaReservas as $reserva) {
                                                $fecha_reser = $reserva['fecha_reserva'];
                                                $hora_reser = $reserva['hora_inicio'];
                                                $date_comp_reser = $fecha_reser . ' ' . $hora_reser;
                                                $conv_date = strtotime($date_comp_reser) + 21600;
                                                if ($conv_date < $comparacion_date && $conv_date > time()) {
                                                    $comparacion_date = $conv_date;
                                                }
                                            }
                                        } else {
                                            $comparacion_date = 0;
                                        }
                                        $fecha_actual = time();
                                        $comparacion = $comparacion_date - $fecha_actual;
                                        if ($comparacion >= 172801) {
                                            echo "NO";
                                        } else {
                                            if ($comparacion <= 172800 && $comparacion > 86400) {
                                                echo "menos de 2 dias";
                                            } else {
                                                if ($comparacion <= 86400 && $comparacion > 43200) {
                                                    echo "menos de 1 dia";
                                                } else {
                                                    if ($comparacion <= 43200 && $comparacion > 0) {
                                                        echo "menos de 12 hrs";
                                                    } else {
                                                        echo "Expedido";
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form action="vistaDetRese.php" method="post" name="formulario">
                                            <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
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