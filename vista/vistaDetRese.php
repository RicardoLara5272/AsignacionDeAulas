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
ini_set('date.timezone','America/Los_Angeles');

$_POST["fecha"] = date("Y-m-d");
$conexion = $db;
$id_sol_DetPend='';

$tipo_solicitud;
if (isset( $_POST['id_solicitud_Pend'])) {
    $id_sol_DetPend = $_POST['id_solicitud_Pend'];
}
if (isset($_GET['id_solicitud_Pend'])) {
    $id_sol_DetPend = $_GET['id_solicitud_Pend'];
}

$sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_sol_DetPend");
$sentenciaSQL->execute();
$listaReservas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
$data_consultar = [];
foreach ($listaReservas as $key => $value) {
    $data_consultar = $value;
    break;
}

//var_dump($listaReservas);
$grupos = json_decode($data_consultar['grupo']);
//var_dump($grupos);
if (count($grupos) > 1) {
    $tipo_solicitud = "Compartido";
} else {
    $tipo_solicitud = "Individual";
}
//echo date_default_timezone_get();
//echo date("Y-m-d H:m:s");

$sentencia= $conexion->prepare("DELETE FROM `auxiliar` WHERE id_aula>0");
$sentencia->execute();

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
                        <?php
                            $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE id_solicitudes = $id_sol_DetPend");
                            $sentenciaSQL->execute();
                            $id_docente = $sentenciaSQL->fetchColumn(3);

                            $sentenciaSQL = $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente ");
                            $sentenciaSQL->execute();
                            $nom_docente = $sentenciaSQL->fetchColumn(2);
                        ?>
                        <strong><label for="nombre_docente">Solicitado por:</label></strong><br>
                        <?php echo $nom_docente?>
                    </div>
                    <div class="card-header text-center">
                    <h3>Reservas</h3>
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
                                    <th>Cap.</th>
                                    <th>Fecha Reserva</th>
                                    <th>Hora Inicio</th>
                                    <th>Hora Final</th>
                                    <th>Estado</th>
                                    <th>Detalle</th>
                                    <th>Acciones</th>
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
                                        <th> <?php echo $reserva['capEstudiantes']; ?></th>
                                        <td> <?php echo $reserva['fecha_reserva']; ?> </td>
                                        <td> <?php echo $reserva['hora_inicio']; ?> </td>
                                        <td> <?php echo $reserva['hora_fin']; ?> </td>
                                        <td>
                                            <?php
                                            $id_reserva = $reserva['id_reserva'];
                                            $sentenciaSQL = $conexion->prepare(" SELECT * FROM reservas_atendidas WHERE id_reserva = $id_reserva ");
                                            $sentenciaSQL->execute();
                                            $estado_reserva = $sentenciaSQL->fetchColumn(3);
                                            if (empty($estado_reserva)) {
                                                echo 'Pendiente';
                                            } else {
                                                echo $estado_reserva;
                                                $indiceRev++;
                                            }
                                            ?>
                                        </td>
                                        <td class="texto"> <?php echo $reserva['detalle']; ?> </td>
                                        <td>
                                            <?php
                                                $sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_reserva = $id_reserva");
                                                $sentenciaSQL->execute();
                                                $listaReservas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
                                                //ciclo para sacar el mayor considerando la fecha de hoy 

                                                $fecha_re = $reserva['fecha_reserva'];
                                                $hora_re = $reserva['hora_inicio'];
                                                $fecha_comp_re = $fecha_re . ' ' . $hora_re;
                                                $comp_date = strtotime($fecha_comp_re) + 21600;

                                                $fecha_actual = time();
                                                $comparacion = $comp_date - $fecha_actual;
                                                if ($comparacion < 0) { 
                                                    if(strtolower($estado_reserva)=='rechazado'){ 
                                                        echo "Atendido";
                                                    }else{?>
                                                        <form action="formulario_Rechazar.php" method="post">
                                                            <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $reserva['id_reserva']; ?> ">
                                                            <input type="submit" name="enviar" class="btn btn-secondary" value="RECHAZAR">
                                                        </form>
                                                    <?php  
                                                    }
                                                }else{
                                                    if(!(strtolower($estado_reserva)=='rechazado' || strtolower($estado_reserva)=='aceptado')){ ?>
                                                        <form action="consultarAulas.php" method="post">
                                                            <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $reserva['id_reserva']; ?> ">
                                                            <input type="submit" class="btn btn-info btn-space" value="CONSULTAR">
                                                        </form>
                                                    <?php 
                                                    }else{
                                                        echo "Atendido";
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-12">
                    <div style="text-align:right">
                        <br><br>
                        <input id="id_solicitud" type="hidden" value="<?php echo $id_sol_DetPend; ?>">
                        <input id="id_admin" type="hidden" value="<?php echo $_SESSION['id_docente'] ?>">
                        <input id="num_total_rese" type="hidden" value="<?php echo $indice; ?>">
                        <input id="num_rese_ate" type="hidden" value="<?php echo $indiceRev; ?>">
                        <button type="submit" class="btn btn-primary" id="boton" onclick="comprobacion()">REVISADO</button>
                        <a class="btn btn-danger" href="vistaDetPend.php">SALIR</a>
                    </div>
                </div>
                <script language="javascript" src="../js/redireccion.js"> </script>
        </div>
    </div>
</main>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>