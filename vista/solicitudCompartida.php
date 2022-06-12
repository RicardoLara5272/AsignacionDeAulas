<!DOCTYPE html>
<html lang="es">

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
    <script src="./sweet/dist/sweetalert2.all.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<?php
include_once("layouts/head.php");
include_once("../conexiones/conexion.php");
$_POST["fecha"] = date("Y-m-d");
$id_docente = 1;

$objeto = new Conexion();
$conexion = $objeto->Conectar();

/*echo "<pre>";
print_r($data);
print_r($fila);
echo "</pre>";*/

$sql = "SELECT nombre_docente FROM docentes WHERE id_docente = $id_docente";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$nombre_docente = $resultado->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT DISTINCT d.id_materia, m.nombre_materia FROM docente_materia d INNER JOIN materias m ON d.id_materia=m.id_materia WHERE d.id_docente=$id_docente";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$tablamaterias = $resultado->fetchAll(PDO::FETCH_ASSOC);

$consulta = "SELECT count(solicitudes.id_solicitudes) from solicitudes WHERE id_docente=$id_docente";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$nrosolicitudes = $resultado->fetchAll(PDO::FETCH_ASSOC);
$nro = $nrosolicitudes['0']['count(solicitudes.id_solicitudes)'] + 1;

if (isset($_GET['Message'])) {
    print '<script type="text/javascript">alert("Error!!\nPara continuar debe seleccionar al menos un grupo dictado por usted ");</script>';
}
?>

<body>
    <section>
        <div class="row text-center">
            <div class="col-lg-12">
                <h3>Solicitud Compartida Reserva de Aula: # <?php echo $nro ?></h3>
            </div>
            <div class="col-lg-12">
                <h4><?php echo $_POST["fecha"] ?></h4>
            </div>
        </div>
    </section>
    <div class="container">
        <div class="solicitudCompartida">
            <form class="form-horizontal" id="formCompartida" onsubmit="return validationForm(event)" action="./solicitudVistaCompartida.php" method="POST">
                <div class="row">
                    <div class="col-md-4 offset-md-4">
                        <label for="nombre_docente">Solicitud realizada por el Docente :</label>
                        <h5><?php echo $nombre_docente['0']['nombre_docente'] ?></h5>
                    </div>
                    <div class="col-md-4 offset-md-4">
                        <label for="id_materia">Materia:</label>
                        <div class="form-group">
                            <select class="form-control col-md-12 grupoMultiple" name="nombre_materia" id="id_materia_compartido">
                                <option value="">Seleccionar materia...</option>
                                <?php
                                foreach ($tablamaterias as $key => $materia) {
                                ?>
                                    <option value="<?php echo $materia['id_materia'] ?>"><?php echo $materia['nombre_materia'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4 offset-md-4">
                        <label for="grupo" class="col-form-label">Selecciona el o los docentes:</label>
                        <div class="form-group">
                            <select class="form-control col-md-12" name="grupo[]" id="grupo" multiple>
                                <option value="">Seleccionar docentes...</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 offset-md-4 justify-content-md-center">
                        <div class="row justify-content-around">
                            <div class="row-4">
                                <input class="btn btn-primary btnContinuar" id="btnContinuar" type="hidden" name="registroDoc" value="CONTINUAR">
                                <button class="btn btn-primary" type="submit">CONTINUAR</button>
                            </div>
                            <div class="row-4">
                                <input class="btn btn-danger" type="button" id="btnCancelar" onClick="window.parent.location='./homeDocente'" value="CANCELAR">
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="./scrip.js"></script>
</body>

</html>