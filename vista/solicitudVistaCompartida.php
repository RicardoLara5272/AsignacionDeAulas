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
</head>
<?php
include_once("layouts/head.php");
include_once("../conexiones/conexion.php");
$_POST["fecha"] = date("Y-m-d");
$id_docente = 1;

$objeto = new Conexion();
$conexion = $objeto->Conectar();

$consulta = "SELECT count(solicitudes.id_solicitudes) from solicitudes WHERE id_docente=$id_docente";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$nrosolicitudes = $resultado->fetchAll(PDO::FETCH_ASSOC);
$nro = $nrosolicitudes['0']['count(solicitudes.id_solicitudes)'] + 1;

$sql = "SELECT nombre_docente FROM docentes WHERE id_docente = $id_docente";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$nombre_docente = $resultado->fetchAll(PDO::FETCH_ASSOC);

$peticion=$_SERVER['REQUEST_METHOD'];
switch($peticion){
    case 'GET':
        break;
    case 'POST':
        if(isset($_POST["registroDoc"])){
            if(isset($_POST['nombre_materia']) && isset($_POST["grupo"])){
                $id_materia = $_POST['nombre_materia'];
                $docentes_compartidos=$_POST["grupo"]; 
                $aux=implode(',', $docentes_compartidos); //``
                $aux = str_replace(",","','",$aux);
                $queryDocente = "SELECT * from `docente_materia` WHERE `id_docente`=$id_docente AND `id_materia`=$id_materia AND `id_grupo` IN('$aux')";
                $resultadoqueryDocente = $conexion->prepare($queryDocente);
                $resultadoqueryDocente->execute();
                $arrayData = $resultadoqueryDocente->fetchAll(PDO::FETCH_ASSOC);
                if(count($arrayData) == 0){
                    header('Location:'.'./solicitudCompartida.php?Message=' . urlencode(0));
                }
            }else{
                header('Location:'.'./solicitudCompartida.php');
            }
        }
        break;
}

$sql = "SELECT nombre_materia FROM materias WHERE id_materia = $id_materia";
$resultado = $conexion->prepare($sql);
$resultado->execute();
$nombre_materia = $resultado->fetchAll(PDO::FETCH_ASSOC);

$auxDocCompartido=implode(',', $docentes_compartidos); //``
$auxDocCompartido = str_replace(",","','",$auxDocCompartido);
$queryDocenteCompartido = "SELECT DISTINCT m.nombre_docente, d.id_grupo FROM docente_materia d INNER JOIN docentes m ON d.id_docente=m.id_docente WHERE `id_grupo` IN('$auxDocCompartido') AND d.id_materia=$id_materia";
$resultadoDocenteCompartido = $conexion->prepare($queryDocenteCompartido);
$resultadoDocenteCompartido->execute();
$arrayDataCompartido = $resultadoDocenteCompartido->fetchAll(PDO::FETCH_ASSOC);

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
        <div class="col-md-12">
            <div class="col-md-4">
                <label for="nombre_docente">Solicitado por Docente:</label>
                <h5><?php echo $nombre_docente['0']['nombre_docente'] ?></h5>
                <label for="nombre_grupos">Solicitud compartida con los docentes:</label>
                <h5><?php for ($i=0;$i<count($arrayDataCompartido);$i++){     
                                echo ($arrayDataCompartido[$i]['nombre_docente']); echo (' - ' . $arrayDataCompartido[$i]['id_grupo']); ?><br>   
                    <?php } ?></h5>
                <button id="BotonAgregar" type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#FormularioArticulo" data-whatever="@mdo">NUEVA RESERVA COMPARTIDA</button>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
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
            <div class="row justify-content-around">        
                <div class="col-8">    
                    <button type="button" id="btnReserva" class="btn btn-primary text-center btnReserva">ENVIAR Y GUARDAR</button>
                </div>
                <div class="col-4">   
                    <button type="button" id="btnCancelReserva" class="btn btn-danger text-center btnCancelReserva" >CANCELAR</button>
                </div>
            </div>
        </div>

        <!-- Formulario (Agregar, Modificar) -->

        <div class="modal fade" id="FormularioArticulo" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title col-11 text-center" id="FormularioArticulo">NUEVA RESERVA MULTIPLE</h5>
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
                                <input type="date" id="fecha_reserva" class="form-control" name="fecha" min ='<?php echo date("Y-m-d",strtotime( date('Y-m-d')."- 1 days"));?>' required>
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
                            <button type="button" id="ConfirmarAgregar" class="btn btn-info cancelModal">AGREGAR</button>
                            <button type="button" class="btn btn-danger cancelModal" data-dismiss="modal">CANCELAR</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <script>
                var nro_solicitud = '<?php echo $nro ?>';
                var id_doc = '<?php echo $id_docente ?>';
            </script>
            <script src="../controlador/controladorCompartida.js"></script>
            <script src="./scrip.js"></script>
        </div>
    </div>
</body>



</html>