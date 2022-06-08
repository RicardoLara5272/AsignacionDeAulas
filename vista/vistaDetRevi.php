<?php //include("../template/cabecera.php"); ?>

<?php 
include("../config/db.php");
include("./layouts/navAdministrativo.php");
?>

<?php 
$sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='revisado' ");
$sentenciaSQL->execute();
$listaSolicitudes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
    <div class="row text-center">
        <div class="col-lg-12">
            <br>
            <h2>Lista de solicitudes revisadas</h2>
            <br><br>
        </div>
    </div>
</section> 

<!-- comienzo de tabla-->
<div class="row justify-content-center">
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
            <?php foreach($listaSolicitudes as $solicitud) {?>
                <tr>
                    <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
                    <td> 
                        <?php 
                            $id_docente = $solicitud['id_docente'];
                            $sentenciaSQL= $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente");
                            $sentenciaSQL->execute();
                            $docente=$sentenciaSQL->fetchColumn(2);
                            echo $docente; 
                        ?> 
                    </td>
                    <td> 
                        <?php 
                            echo 'En producción'; 
                        ?> 
                    </td>
                    <td> <?php echo $solicitud['fecha_solicitud']; ?> </td>
                    <td> 
                        <?php echo 'dato no existete' ;?> 
                    </td>
                    <td> <?php echo $solicitud['estado']; ?> </td>
                    <td> 
                        <form action="vistaDetReservaAtendi.php" method="post" name="formulario">
                            <input type="hidden" name="id_solicitud_Revi" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                            <input type="submit" class="btn btn-success botton" value="Detalles" >
                        </form>    
                    </td>

                </tr>
            <?php }?>
            </tbody>
        </table> <!-- final de tabla-->
<?php include("../template/pie.php"); ?>