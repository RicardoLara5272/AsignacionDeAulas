<?php
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
$id_docente = $_SESSION['id_docente'];
$id_materias = 1;
//$objeto = new Conexion();
$conexion = $db; // $objeto->Conectar();

$sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE id_docente=$id_docente");
$sentenciaSQL->execute();
$listaSolicitudes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
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
                            <th>Fecha Solicitud</th>
                            <th>Fecha Revisada</th>
                            <th>Estado</th>
                            <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody action="vistaDetRese.php" method="post">
                            <?php foreach ($listaSolicitudes as $solicitud) { ?>
                                <tr>
                                <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
                                <td> <?php echo $solicitud['fecha_solicitud']; ?></td>
                                <td>
                                <?php
                                    $id_solicit = $solicitud['id_solicitudes'];
                                    $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes_atendidas WHERE id_solicitud = $id_solicit ");
                                    $sentenciaSQL->execute();
                                    $fecha_atend = $sentenciaSQL->fetchColumn(2);
                                    if (empty($fecha_atend)) {
                                        echo 'aún no se reviso';
                                    } else {
                                        echo $fecha_atend;
                                    }
                                ?>
                                </td>
                                <td> <?php echo $solicitud['estado']; ?></td>
                                <td>
                                    <form action="DetalleReserDocent.php" method="post" name="formulario">
                                        <input type="hidden" name="id_solicitud" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                                        <input type="submit" class="btn btn-success botton" value="Detalles">
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