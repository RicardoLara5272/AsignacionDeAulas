<?php include("../template/cabeDetRes.php"); ?>
<?php include("../config/db.php"); ?>

<?php 
$id_sol_DetPend = $_POST['id_solicitud_Pend'];

$sentenciaSQL= $conexion->prepare(" SELECT * FROM reserva WHERE id_solicitudes = $id_sol_DetPend");
$sentenciaSQL->execute();
$listaReservas=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="jumbotron">
    <blockquote class="blockquote text-center">
        <p class="h2">Solicitud nro # <?php echo $id_sol_DetPend;?> </p>
    </blockquote>
        <p class="h4">
            <?php 
            $sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE id_solicitudes = $id_sol_DetPend");
            $sentenciaSQL->execute();
            $id_docente = $sentenciaSQL->fetchColumn(3);

            $sentenciaSQL= $conexion->prepare(" SELECT * FROM docentes WHERE id_docente = $id_docente ");
            $sentenciaSQL->execute();
            $nom_docente = $sentenciaSQL->fetchColumn(2);
            echo $nom_docente; 
            ?> 
        </p>
    <blockquote class="blockquote text-center">
        <p class="h4">Reservas</p>
    </blockquote>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Num</th>
            <th>Materia</th>
            <th>Grupo</th>
            <th>Estudiantes</th>
            <th>Fecha Reserva</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    <?php $indice = 1 ; ?>
    <?php foreach($listaReservas as $reserva) {?>
        <tr>
            <td> <?php echo $indice++; ?> </td>
            <td>
                <?php 
                $id_materia = $reserva['id_materia'];
                $sentenciaSQL= $conexion->prepare(" SELECT * FROM materias WHERE id_materia = $id_materia ");
                $sentenciaSQL->execute();
                $nom_materia = $sentenciaSQL->fetchColumn(2);
                echo $nom_materia; 
                ?> 
            </td>
            <td> <?php echo $reserva['grupo']; ?> </td>
            <th> <?php echo $reserva['capEstudiantes']; ?></th>
            <td> <?php echo $reserva['fecha_reserva']; ?> </td>
            <td> 
                <?php 
                    //es unico el id_reserva?
                    $indiceRev = 0;
                    $id_reserva = $reserva['id_reserva'];
                    $sentenciaSQL= $conexion->prepare(" SELECT * FROM reservas_atendidas WHERE id_reserva = $id_reserva ");
                    $sentenciaSQL->execute();
                    $estado_reserva= $sentenciaSQL->fetchColumn(3);
                    if(empty($estado_reserva)){
                        echo 'pendiente';
                    }
                    else{
                        echo $estado_reserva;
                        $indiceRev++;
                    }
                ?> 
            </td>
            <td> 
            <!--redirigr mariscal y ricardo-->
                <div class="btn-group">
                    <form action="formulario_Rechazar.php" method="post">
                        <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $reserva['id_reserva']; ?> ">
                        <input type="submit" name="enviar"class="btn btn-success btn-space" value="Rechazar" >
                    </form>
                    <form action="ricardo.php" method="post">
                        <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $id_sol_DetPend; ?> ">
                        <input type="submit" class="btn btn-success" class="btn-block" value="Asignar" >
                    </form>
                </div>
            </td>
        </tr>
    <?php }?>
    </tbody>
</table>

<div class="col-12">
    <div style="text-align:right">
        <br><br>
        <input id="num_total_rese" type="hidden" value="<?php echo $indice; ?>">
        <input id="num_rese_ate" type="hidden" value="<?php echo $indiceRev; ?>">
        <button type="submit" class="btn btn-primary" href="funciono.php" id="boton" onclick="comprobacion()">Revisado</button>
        
        <script>
            function comprobacion(){
                var  numReservas = document.getElementById('num_total_rese').value - 1;
                var  numResAten = document.getElementById('num_rese_ate').value;
                console.log(numReservas);
                console.log(numResAten);
                if (numReservas == numResAten) {
                    document.getElementById('boton').disabled=false;
                } else{
                    document.getElementById('boton').disabled=true;
                }
            }
        </script>

        <a class="btn btn-primary" href="vistaDetPend.php">Salir</a>
    </div>
</div>
<?php include("../template/pie.php"); ?>