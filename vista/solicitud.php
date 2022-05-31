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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="sweetalert2.min.css">
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

$consulta = "SELECT fecha_reserva, grupo, periodos, capEstudiantes, detalle FROM reserva r WHERE r.id_solicitudes = $nro";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data = $resultado->fetchAll(PDO::FETCH_ASSOC);

/*echo "<pre>";
print_r($prueba);

echo "</pre>";*/
?>

<body>
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
    <div class="container">
        <div class="col-md-12">
            <div class="col-md-4">
                <label for="nombre_docente">Solicitado por Docente:</label>
                <h5><?php echo $nombre_docente['0']['nombre_docente'] ?></h5>
                <button id="BotonAgregar" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#FormularioArticulo" data-whatever="@mdo">NUEVA RESERVA</button>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
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
            <div class="form-group" style="width:100%">    
                <div class="col-12">
                    <button type="button" id="btnReserva" class="btn btn-dark text-center btn-block mt-2 mb-8 btnReserva">ENVIAR Y GUARDAR</button>
                </div>
            </div>
        </div>

        <!-- Formulario (Agregar, Modificar) -->

        <div class="modal fade" id="FormularioArticulo" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title col-11 text-center" id="FormularioArticulo">NUEVA RESERVA</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id_reserva">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <!--<label>Materia:</label>
                                    <input type="number" id="id_materia" class="form-control" placeholder="">-->
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
                                <select class="form-control" name="grupo" id="grupo">
                                    <option value="">Primero seleccione materia...</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Fecha:</label>
                                <input type="date" id="fecha_reserva" class="form-control" name="fecha" required>
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
                            <button type="button" id="ConfirmarAgregar" class="btn btn-success">AGREGAR</button>
                            <button type="button" class="btn btn-success" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                var nro_solicitud = '<?php echo $nro ?>';
                var id_doc = '<?php echo $id_docente ?>';
            </script>
            <script src="../controlador/controladorReserva.js"></script>
            <script src="./scrip.js"></script>
        </div>
    </div>
</body>

</html>