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
$id_sol_DetPend='';
if (isset( $_POST['id_solicitud_Pend'])) {
    $id_sol_DetPend = $_POST['id_solicitud_Pend'];
}
if (isset($_GET['id_solicitud_Pend'])) {
    $id_sol_DetPend = $_GET['id_solicitud_Pend'];
}

$sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_sol_DetPend");
$sentenciaSQL->execute();
$listaReservas = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <section>
                    <div class="row text-center">
                        <div class="col-lg-12">
                            <br>
                            <h2>Solicitud nro # <?php echo $id_sol_DetPend; ?> </h2>
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
                            //echo $nom_docente;
                        ?>
                        <label for="nombre_docente">Solicitado por:</label>
                        <h5><?php echo $nom_docente?></h5>
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
                                    <th>Num</th>
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
                                        <td> <?php echo $reserva['detalle']; ?> </td>
                                        <td>
                                            <!--redirigr mariscal y ricardo-->
                                            <div class="btn-group">
                                                <form action="consultarAulas.php" method="post">
                                                    <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $reserva['id_reserva']; ?> ">
                                                    <input type="submit" class="btn btn-info btn-space" value="CONSULTAR">
                                                </form>
                                                <form action="formulario_Rechazar.php" method="post">
                                                    <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $reserva['id_reserva']; ?> ">
                                                    <input type="submit" name="enviar" class="btn btn-secondary" value="RECHAZAR">
                                                </form>
                                            </div>
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
                        <button type="submit" class="btn btn-primary" href="funciono.php" id="boton" onclick="comprobacion()">REVISADO</button>

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