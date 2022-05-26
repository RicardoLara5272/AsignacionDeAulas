<?php include("../template/cabecera.php"); ?>
<?php include("../config/db.php"); ?>

<?php 
$sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='revisado' ");
$sentenciaSQL->execute();
$listaSolicitudes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="jumbotron">
    <blockquote class="blockquote text-center">
        <p class="h2">Lista de solicitudes Revisadas</p>
    </blockquote>
    <br/>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre del docente</th>
            <th>Revisado por</th>
            <th>Fecha Solicitud?</th>
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
                    echo 'Ricardo Lara Veizaga ?'; 
                ?> 
            </td>
            <td> <?php echo $solicitud['fecha_solicitud']; //de donde sale fecha solicitud ?> </td>
            <td> 
                <?php 
                    echo time(); 
                    echo '?';
                ?> 
            </td>
            <td> <?php echo $solicitud['estado']; ?> </td>
            <td> 
                <input type="button" class="btn btn-success botton" value="Detalles" data-toggle="modal" data-target="#exampleModal">
            </td>

        </tr>
    <?php }?>
    </tbody>
</table>

<?php include("../template/pie.php"); ?>