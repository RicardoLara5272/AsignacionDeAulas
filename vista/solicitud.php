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

$consulta = "SELECT fecha_reserva, grupo, capEstudiantes, detalle FROM reserva r WHERE r.id_solicitudes = $nro";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <section>
                <div class="row text-center">
                    <div class="col-lg-12">
                        <h2>Solicitud de Reserva de Aula: # <?php echo $nro ?></h2>
                    </div>
                    <div class="col-lg-12">
                        <h2><?php echo $_POST["fecha"] ?></h2>
                    </div>
                </div>
            </section>
            <div class="col-md-12 mb-2">
                <div class="col-md-12">
                    <label for="nombre_docente">Solicitado por Docente:</label>
                    <h5><?php echo $nombre_docente['0']['nombre_docente'] ?></h5>
                </div>
                <button id="BotonAgregar" type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#FormularioArticulo" data-whatever="@mdo">NUEVA RESERVA</button>
            </div>
            <br>
            <div class="col-md-12 mb-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed" id="tablaarticulos" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>Nro reserva</th>
                                <th>Materia</th>
                                <th>Grupo</th>
                                <th>Fecha</th>
                                <th>Hora inicio</th>
                                <th>Hora fin</th>
                                <th>Capacidad Estudiantes</th>
                                <th>Detalle</th>
                                <th>Borrar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="col-md-12 mb-2">
                <button type="button" id="btnReserva" class="btn btn-primary text-center btnReserva">ENVIAR Y GUARDAR</button>
                <button type="button" id="btnCancelReserva" class="btn btn-danger text-center btnCancelReserva">CANCELAR</button>

            </div>
        </div>
    </div>
</main>

<!-- Formulario (Agregar, Modificar) -->

<div class="modal fade" id="FormularioArticulo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title col-11 text-center" id="FormularioArticulo">NUEVA RESERVA</h5>
                <button type="button" class="close cancelModal" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_reserva">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="id_materia">Materia:</label>
                        <select class="form-control" name="nombre_materia" id="id_materia">
                            <option value="">Seleccionar materia...</option>
                            <?php
                            foreach ($tablamaterias as $key => $materia) {
                            ?>
                                <option value="<?php echo $materia['id_materia'] ?>"><?php echo $materia['nombre_materia'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <input type="hidden" value="<?php echo $id_docente ?>" id="id_docente">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="grupo" class="col-form-label">Grupo:</label>
                        <select class="form-control" name="grupo[]" id="grupo">
                            <option value="">Primero seleccione materia...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Fecha:</label>
                        <input type="date" id="fecha_reserva" class="form-control" name="fecha" min='<?php echo date("Y-m-d", strtotime(date('Y-m-d') . "- 1 days")); ?>' required>
                        <span id="fechaSelected"></span>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12" id="TituloHoraInicio">
                        <label for="hora_inicio">Hora inicio:</label>
                        <select class="form-control" onchange="obtenerhorainicio()" name="hora_inicio" id="hora_inicio">
                            <option value="">Seleccione hora inicio...</option>
                            <option value="1">08:15</option>
                            <option value="2">09:45</option>
                            <option value="3">11:15</option>
                            <option value="4">12:45</option>
                            <option value="5">14:15</option>
                            <option value="6">15:45</option>
                            <option value="7">17:15</option>
                            <option hidden value="8">18:45</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12" id="TituloHoraFin">
                        <label for="hora_fin">Hora fin:</label>
                        <select class="form-control" name="hora_fin" id="hora_fin">
                            <option value="">Primero seleccione hora inicio...</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Capacidad estudiantes:</label>
                        <input type="number" id="capEstudiantes" class="form-control" placeholder="">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Detalle:</label>
                        <input type="text" id="detalle" class="form-control" placeholder="">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="ConfirmarAgregar" class="btn btn-info">AGREGAR</button>
                    <button type="button" class="btn btn-danger cancelModal" data-dismiss="modal">CANCELAR</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var nro_solicitud = '<?php echo $nro ?>';
    var id_doc = '<?php echo $id_docente ?>';
</script>
<script src="http://asignaciondeaulas/vista/DataTables/datatables.min.js"></script>
<script src="http://asignaciondeaulas/vista/DataTables/datatables.js"></script>
<script src="http://asignaciondeaulas/vista/scrip.js"></script>
<script src="http://asignaciondeaulas/controlador/controladorReserva.js"></script>

<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>