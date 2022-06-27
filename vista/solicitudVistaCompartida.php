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

$consulta = "SELECT count(solicitudes.id_solicitudes) from solicitudes WHERE id_docente=$id_docente";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$nrosolicitudes = $resultado->fetchAll(PDO::FETCH_ASSOC);
$nro = $nrosolicitudes['0']['count(solicitudes.id_solicitudes)'] + 1;

$sql = "SELECT nombre_docente FROM docentes WHERE id_docente = $id_docente";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$nombre_docente = $resultado->fetchAll(PDO::FETCH_ASSOC);

$peticion = $_SERVER['REQUEST_METHOD'];
switch ($peticion) {
    case 'GET':
        break;
    case 'POST':
        if (isset($_POST["registroDoc"])) {
            if (isset($_POST['nombre_materia']) && isset($_POST["grupo"])) {
                $id_materia = $_POST['nombre_materia'];
                $docentes_compartidos = $_POST["grupo"];
                $aux = implode(',', $docentes_compartidos); //``
                $aux = str_replace(",", "','", $aux);
                $queryDocente = "SELECT * from `docente_materia` WHERE `id_docente`=$id_docente AND `id_materia`=$id_materia AND `id_grupo` IN('$aux')";
                $resultadoqueryDocente = $conexion->prepare($queryDocente);
                $resultadoqueryDocente->execute();
                $arrayData = $resultadoqueryDocente->fetchAll(PDO::FETCH_ASSOC);
                if (count($arrayData) == 0) {
                    header('Location:' . './solicitudCompartida.php?Message=' . urlencode(0));
                }
            } else {
                header('Location:' . './solicitudCompartida.php');
            }
        }
        break;
}

$sql = "SELECT nombre_materia FROM materias WHERE id_materia = $id_materia";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$nombre_materia = $resultado->fetchAll(PDO::FETCH_ASSOC);

$auxDocCompartido = implode(',', $docentes_compartidos); //``
$auxDocCompartido = str_replace(",", "','", $auxDocCompartido);
$queryDocenteCompartido = "SELECT DISTINCT m.nombre_docente, d.id_grupo FROM docente_materia d INNER JOIN docentes m ON d.id_docente=m.id_docente WHERE `id_grupo` IN('$auxDocCompartido') AND d.id_materia=$id_materia";
$resultadoDocenteCompartido = $conexion->prepare($queryDocenteCompartido);
$resultadoDocenteCompartido->execute();
$arrayDataCompartido = $resultadoDocenteCompartido->fetchAll(PDO::FETCH_ASSOC);
?>
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <section>
                <br>
                <div class="card-header text-center">
                    <div class="col-lg-12">
                        <h3>Solicitud Compartida Reserva de Aula: # <?php echo $nro ?></h3>
                    </div>
                    <div class="col-lg-12">
                        <h4><?php echo $_POST["fecha"] ?></h4>
                    </div>
                </div>
                <br>
            </section>
            <div class="row justify-content-left" >
                <div class="col-md-6">
                    <strong><label for="nombre_docente">Solicitado por Docente:</label></strong><br>
                    <?php echo $nombre_docente['0']['nombre_docente'] ?><br>
                    <strong><label for="nombre_grupos">Solicitud compartida con los docentes:</label></strong><br>
                    <?php for ($i = 0; $i < count($arrayDataCompartido); $i++) {
                            echo ($arrayDataCompartido[$i]['nombre_docente']);
                            echo (' - ' . $arrayDataCompartido[$i]['id_grupo']); ?><br>
                        <?php } ?><br>
                    <button id="BotonAgregar" type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#FormularioArticulo" data-whatever="@mdo">NUEVA RESERVA COMPARTIDA</button>
                </div>
            </div>
            <br>
            <div class="row justify-content-center">
                <div class="col">
                    <div class="table-responsive">
                    <br>
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
                <div class="row justify-content-end">
                    <div class="col-4" style="text-align:right">
                        <br>
                        <button type="button" id="btnReserva" class="btn btn-primary text-center btnReserva">ENVIAR Y GUARDAR</button>
                        <button type="button" id="btnCancelReserva" class="btn btn-danger text-center btnCancelReserva">CANCELAR</button>
                    </div>
                </div>
            </div>
            <!-- Formulario (Agregar, Modificar) -->
        </div>
    </div>
</main>
<div class="modal fade" id="FormularioArticulo" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title col-11 text-center" id="FormularioArticulo">NUEVA RESERVA COMPARTIDA</h5>
                <button type="button" class="close cancelModal" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_reserva">
                <input type="hidden" value="<?php echo $id_materia ?>" id="id_materia">
                <input type="hidden" value="<?php echo implode(",", $docentes_compartidos); ?>" name="grupo[]" id="grupo">
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
    <script src="http://asignaciondeaulas/controlador/controladorCompartida.js"></script>
    <script src="http://asignaciondeaulas/vista/scrip.js"></script>

<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>