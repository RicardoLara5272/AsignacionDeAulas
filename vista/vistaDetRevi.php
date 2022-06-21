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
$id_docente = $_SESSION['id_docente'];
$id_materias = 1;

//$objeto = new Conexion();
$conexion = $db; // $objeto->Conectar();
$sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='revisado' ");
$sentenciaSQL->execute();
$listaSolicitudes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <br>
                <div class="card-header text-center">
                    <h2>Lista de solicitudes Revisadas</h2>
                </div>
                <!-- comienzo de tabla-->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del docente</th>
                                <th>Revisado por</th>
                                <th>Fecha Solicitud</th>
                                <th>Fecha Atendida</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaSolicitudes as $solicitud) { ?>
                                <tr>
                                    <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
                                    <td>
                                        <?php
                                        $id_docente = $solicitud['id_docente'];
                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente");
                                        $sentenciaSQL->execute();
                                        $docente = $sentenciaSQL->fetchColumn(2);
                                        echo $docente;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        $id_solic = $solicitud['id_solicitudes'];
                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes_atendidas WHERE id_solicitud = $id_solic");
                                        $sentenciaSQL->execute();
                                        $id_admin = $sentenciaSQL->fetchColumn(3);

                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_admin");
                                        $sentenciaSQL->execute();
                                        $nomb_admin = $sentenciaSQL->fetchColumn(2);
                                        echo $nomb_admin;
                                        ?>
                                    </td>
                                    <td> <?php echo $solicitud['fecha_solicitud']; ?> </td>
                                    <td>
                                        <?php
                                        $id_solic = $solicitud['id_solicitudes'];
                                        $sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes_atendidas WHERE id_solicitud = $id_solic");
                                        $sentenciaSQL->execute();
                                        $fecha_aten = $sentenciaSQL->fetchColumn(2);
                                        echo $fecha_aten;
                                        ?>
                                    </td>
                                    <td> <?php echo $solicitud['estado']; ?> </td>
                                    <td>
                                        <form action="vistaDetReservaAtendi.php" method="post" name="formulario">
                                            <input type="hidden" name="id_solicitud_Revi" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
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