<?php
// include_once("layouts/head.php");
// include_once("../conexiones/conexion.php");
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
    exit();
}

//define page title
$title = 'Docentes Page';

//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
$_POST["fecha"] = date("Y-m-d");
$conexion = $db;
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
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Fecha Solicitud</th>
                                <th>Estado</th>
                                <th>Urgencia</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody action="vistaDetRese.php" method="post">
                            <?php foreach ($listaSolicitudes as $solicitud) { ?>
                                <tr>
                                    <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
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
                                            $comp_date = strtotime($pri_fecha_comp) + 21600;
                                            break;
                                        }
                                        //ciclo para buscar el menor

                                        foreach ($listaReservas as $reserva) {
                                            $fecha_reser = $reserva['fecha_reserva'];
                                            $hora_reser = $reserva['hora_inicio'];
                                            $date_comp_reser = $fecha_reser . ' ' . $hora_reser;
                                            $conv_date = strtotime($date_comp_reser) + 21600;
                                            if ($conv_date < $comp_date) {
                                                $comp_date = $conv_date;
                                            }
                                        }
                                        $fecha_actual = time();
                                        $comparacion = $comp_date - $fecha_actual;
                                        if ($comparacion >= 172801) {
                                            echo "NO";
                                        } else {
                                            if ($comparacion <= 172800 && $comparacion > 86400) {
                                                echo "Menos de 2 dias";
                                            } else {
                                                if ($comparacion <= 86400 && $comparacion > 43200) {
                                                    echo "Menos de 1 dia";
                                                } else {
                                                    if ($comparacion <= 43200 && $comparacion > 0) {
                                                        echo "Menos de 12 hrs";
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