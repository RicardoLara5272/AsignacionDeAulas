<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: ../login.php');
}
if($_SESSION['is_admin'] != 1)
    {
        header("location: /asignacionAulas-main/vista/homeDocente.php");
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaciones</title>

    <link href="bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="datatables/datatables.min.css" rel="stylesheet">


    <script src="js/jquery-3.4.1.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="./bootstrap-4.3.1/js/bootstrap.min.js"></script>
    <script src="./DataTables/datatables.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/estiloVista.css">
</head>
<?php
include("./layouts/navAdministrativo.php");
include("../config/db.php");

$sentenciaSQL = $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='Pendiente' ");
$sentenciaSQL->execute();
$listaSolicitudes = $sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <section>
        <div class="row text-center">
            <div class="col-lg-12">
                <h2>Lista de solicitudes Pendientes</h2>
            </div>
        </div>
    </section>
    <div class="container">
        <br />
        <div class="row">
            <table class="table table-bordered">
                <thead class="text-center">
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
                                //como sacar solo la primera fecha y hora de la tabla reserva solo con id_solicitudes
                                $id_solicitudes = $solicitud['id_solicitudes'];
                                $sentenciaSQL = $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_solicitudes ");
                                $sentenciaSQL->execute();
                                $fecha_reserva = $sentenciaSQL->fetchColumn(2);
                                $hora_soli = $sentenciaSQL->fetchColumn(5);
                                $fecha_soli_com = $fecha_reserva . ' ' . $hora_soli;
                                $fecha_actual = time();
                                $fecha_soli_conv = strtotime($fecha_soli_com) + 21600;
                                $comparacion = $fecha_soli_conv - $fecha_actual;
                                if ($comparacion >= 172801) {
                                    echo "NO";
                                } else {
                                    if ($comparacion <= 172800 && $comparacion > 86400) {
                                        echo "menos de 2 dias";
                                    } else {
                                        if ($comparacion <= 86400 && $comparacion > 43200) {
                                            echo "menos de 1 dia";
                                        } else {
                                            echo "menos de 12 hrs";
                                        }
                                    }
                                }
                                ?>
                            </td>

                            <td>
                                <form action="homeAdministrativo.php" method="post" name="formulario">
                                    <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                                    <input type="submit" class="btn btn-success botton" value="Detalles">
                                </form>
                            </td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <?php include("../template/pie.php"); ?>

</body>

</html>