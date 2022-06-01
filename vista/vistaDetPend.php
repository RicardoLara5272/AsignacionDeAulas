<?php //include("../template/cabecera.php"); ?>

<?php 
include("./layouts/navAdministrativo.php");
include("../config/db.php"); 
?>

<?php 
$sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE Estado='Pendiente' ");
$sentenciaSQL->execute();
$listaSolicitudes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="jumbotron">
    <blockquote class="blockquote text-center">
        <p class="h2">Lista de solicitudes Pendientes</p>
    </blockquote>
    <br/>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Fecha Solicitud?</th>
            <th>Estado</th>
            <th>Urgencia</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody action="vistaDetRese.php" method="post">
    <?php foreach($listaSolicitudes as $solicitud) {?>
        <tr>
            <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
            <td> 
                <?php  
                 $id_docente = $solicitud['id_docente'];
                 $sentenciaSQL= $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente ");
                 $sentenciaSQL->execute();
                 $docente=$sentenciaSQL->fetchColumn(2);
                 echo $docente;
                ?> 
            </td>
            <td> <?php echo $solicitud['fecha_solicitud']; ?> </td>
            <td> <?php echo $solicitud['estado']; ?> </td>
            
            <td> 
                <?php
                //como sacar solo la primera fecha y hora de la tabla reserva solo con id_solicitudes
                $id_solicitudes = $solicitud['id_solicitudes'];
                $sentenciaSQL= $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_solicitudes ");
                $sentenciaSQL->execute();
                $fecha_reserva=$sentenciaSQL->fetchColumn(2);
                $hora_soli = $sentenciaSQL->fetchColumn(5);
                $fecha_soli_com = $fecha_reserva .' '. $hora_soli;
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
                            echo"menos de 12 hrs";
                        }
                    }
                }
                ?>     
            </td>

            <td>
                <form action="vistaDetRese.php" method="post" name="formulario">
                    <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                    <input type="submit" class="btn btn-success botton" value="Detalles" >
                </form>    
            </td>

        </tr>
    <?php }?>
    </tbody>
</table>

<?php include("../template/pie.php"); ?>