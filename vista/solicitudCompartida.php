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
$conexion = $db; 

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
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
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
            <form class="form-horizontal" id="formCompartida" onsubmit="return validationForm(event)" action="./solicitudVistaCompartida.php" method="POST">
                <div class="row justify-content-center" >
                    <div class="col-md-4">
                        <strong><label for="nombre_docente">Solicitud realizada por el Docente :</label></strong><br>
                        <?php echo $nombre_docente['0']['nombre_docente'] ?>
                    </div><br>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        
                        <strong><label for="id_materia">Materia:</label></strong>
                        <div class="form-group">
                            <select class="form-control col-md-4 grupoMultiple" name="nombre_materia" id="id_materia_compartido">
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
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <strong><label for="grupo" class="col-form-label">Selecciona el o los docentes:</label></strong>
                        <div class="form-group">
                            <select class="form-control col-md-8" name="grupo[]" id="grupo" multiple>
                                <option value="">Seleccionar docentes...</option>
                            </select>
                        </div>
                    </div>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <br><br>
                        <input class="btn btn-primary btnContinuar" id="btnContinuar" type="hidden" name="registroDoc" value="CONTINUAR">
                        <button class="btn btn-primary" type="submit">CONTINUAR</button>
                        <input class="btn btn-danger" type="button" id="btnCancelar" onClick="window.parent.location='./homeDocente'" value="CANCELAR" style="width: 115px;">
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="http://asignaciondeaulas/vista/scrip.js"></script>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>