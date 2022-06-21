<?php
//  $motivo=$_POST[]
//capturamos datos de la tabla solicitud
$id= $_POST['id_solicitud_Pend'];
$id_enviar=$id;
if(strlen($id)==3){
  $id_reserva=$id[1];
}
if(strlen($id)==4){
  $id_reserva=$id[1].$id[2];
}
if(strlen($id)==5){
  $id_reserva=$id[1].$id[2].$id[3];
}

$boolean="pendiente";
$db_host = "localhost";
$db_nombre = "asignacionaulas";
$db_usuario = "root";
$db_contra = "";
$conexion = mysqli_connect($db_host, $db_usuario, $db_contra, $db_nombre);
$id_solicitud="";
$sqlv = "SELECT * FROM `reserva`";
$resultv = mysqli_query($conexion, $sqlv);
while ($mostrarv= mysqli_fetch_array($resultv)) {
        if($mostrarv['id_reserva']==$id_reserva){
            $id_solicitud=$mostrarv['id_solicitudes'];
        }
      }
$id_materia = "";
$fecha_reserva="";
$grupo="";
$hora_inicio="";
$hora_fin="";
$cap_est="";
$detalle="";

$codigo_materia="";
$nom_materia="";
$nivel="";

$id_docente="";
$nombre_docente="";


$sql5 = "SELECT * FROM `reservas_atendidas`";
$result5 = mysqli_query($conexion, $sql5);
while ($mostrar5 = mysqli_fetch_array($result5)) {
  if ($id_reserva == $mostrar5['id_reserva']) {
        if($mostrar5['estado']=="rechazado"){
          $boolean="rechazado";
          echo "
      <div>
      <h1   style='background:red; color:white; text-align: center;font-family:Verdana, sans-serif;'; > La Solicitud ha sido rechazada anteriormente  
      </div>  </h1>";
        }
        if($mostrar5['estado']=="aceptado"){
          $boolean="aceptado";
          echo "
      <div>
      <h1   style='background:red; color:white; text-align: center;font-family:Verdana, sans-serif;'; > La Solicitud ha sido aceptada anteriormente   
      </div>  </h1>";
        }
  }
} 
if($boolean=="pendiente"){
$grupo="";
$sqlP = "SELECT * FROM `reserva`";
$resultP = mysqli_query($conexion, $sqlP);
while ($mostrarP = mysqli_fetch_array($resultP)) {
        if($mostrarP['id_reserva']==$id_reserva){
            $id_solicitud=$mostrarP['id_solicitudes'];
            $id_materia=$mostrarP['id_materia'];
            $fecha_reserva=$mostrarP['fecha_reserva'];
            $grupo=$mostrarP['grupo'];
            $hora_inicio=$mostrarP['hora_inicio'];
            $hora_fin=$mostrarP['hora_fin'];
            $cap_est=$mostrarP['capEstudiantes'];
            $detalle=$mostrarP['detalle'];
            $grupo=$mostrarP['grupo'];
        }
      }
     $sql2 = "SELECT * FROM `materias`"; 
      $result2 = mysqli_query($conexion, $sql2);
      while ($mostrar2 = mysqli_fetch_array($result2)) {
        if ($mostrar2['id_materia'] == $id_materia) {
          $codigo_materia = $mostrar2['codigo_materia'];
          $nom_materia=$mostrar2['nombre_materia'];
          $nivel=$mostrar2['nivel'];
         }
      }
$fecha_solicitud="";      
      $sql3 = "SELECT * FROM `solicitudes`";
      $result3 = mysqli_query($conexion, $sql3);
      while ($mostrar3 = mysqli_fetch_array($result3)) {
        if ($id_solicitud == $mostrar3['id_solicitudes']) {
              $id_docente = $mostrar3['id_docente'];
              $fecha_solicitud=$mostrar3['fecha_solicitud'];
        }
      }        
      
      $sql4 = "SELECT * FROM `docentes`";
      $result4 = mysqli_query($conexion, $sql4);
      while ($mostrar4 = mysqli_fetch_array($result4)) {
        if ($id_docente == $mostrar4['id_docente']) {
              $nombre_docente = $mostrar4['nombre_docente'];
        }
      } 
  
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="formulario.css">
  <link rel="stylesheet" href="librerias/plugins/sweetAlert2/sweetalert2.min.css" />
  <link rel="stylesheet" href="librerias/plugins/animate.css/animate.css" />
  <title>Consultar Aulas</title>
</head>

<body>
  <?php
  include('layouts/header.php');
  include('layouts/navegacion.php');
  ?>

  <section>
    <div class="row text-center">
      <div class="col-lg-12">
        <h2 id="titulo">Consultar Aulas</h2>
      </div>
    </div>
  </section>
  <br />
  <div id="for" action="vistaDetRese.php" class="row text-center">
    <form id="formluario" name="formulario" method="post">
      <!-- <h3>ID: </h3>-->
      <b><label for="ID">ID Reserva:</label></b>
      <label><?php echo $id_reserva ?></label><br>
      <b><label for="docente">Docente:</label></b>
      <label><?php echo $nombre_docente ?></label><br>
      <b><label for="materia">Materia:</label></b>
      <label><?php echo $nom_materia ?></label><br>
      <b><label for="fecha_solicitud">Fecha de Reserva:</label></b>
      <label for=""><?php echo $fecha_reserva ?></label><br>
      <b><label for="fecha_reserva">Hora de Reserva:</label></b>
      <label for=""><?php echo $hora_inicio . " - ". $hora_fin ?></label><br>
      <b><label for="detalle">Capacidad de estudiantes:</label></b>
      <label for=""><?php echo $cap_est ?></label>
    </form>
    </div>
    <div >

    <?php
    $contador=0;
    $sql6 = "SELECT * FROM `aulas`";
$result6 = mysqli_query($conexion, $sql6);
while ($mostrar6=mysqli_fetch_array($result6)) {
  $valor="si";
  if($boolean=="pendiente"){ 
        
    $sql7 = "SELECT * FROM `reservas_atendidas`";
    $result7 = mysqli_query($conexion, $sql7);
    while ($mostrar7=mysqli_fetch_array($result7)) {
         if($mostrar6['id_aula']==$mostrar7['id_aula']){

              $sql8 = "SELECT * FROM `reserva`";
              $result8 = mysqli_query($conexion, $sql8);
              while ($mostrar8=mysqli_fetch_array($result8)) {  
                  if($mostrar7['id_reserva']==$mostrar8['id_reserva']){
                      if($fecha_reserva==$mostrar8['fecha_reserva']){
                              if($hora_inicio==$mostrar8['hora_inicio']){
                                $valor="no";
                              }
                              else if($hora_fin==$mostrar8['hora_fin']){
                                  $valor="no";
                              }
                      }
                  }
              }
         }
    }
      if($valor=="si"){      
      if((int)$mostrar6['capacidad']==(int)$cap_est){
        echo "<div id='reserva'><form action='car2.php' method='post'>
        <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
        <input type='hidden' name='id_reserva2' value=". $id_reserva .">
        <input type='hidden' name='fecha_solicitud' value=". $fecha_solicitud .">
        <input type='hidden' name='fecha_reserva' value=". $fecha_reserva .">
        <input type='hidden' name='capEstudiantes' value=". $cap_est .">
        <input type='hidden' name='id_docente' value=". $id_docente .">
        <input type='hidden' name='id_materia' value=". $id_materia .">
        <input type='hidden' name='grupo' value=". $grupo .">
        <input type='hidden' name='hora_inicio' value=". $hora_inicio .">
        <input type='hidden' name='hora_fin' value=". $hora_fin .">".
        "<input type='hidden' name='id_aula' style='background: blue;' value=".$mostrar6['id_aula'].">
        <input type='submit' id='asignar' name='asignar' value=".$mostrar6['codigo_aula']."-Cap".$mostrar6['capacidad'].">
        </form> </div>";
        $contador++;
      }

      }
    }
    }
    $sql6 = "SELECT * FROM `aulas`";
$result6 = mysqli_query($conexion, $sql6);
while ($mostrar6=mysqli_fetch_array($result6)) {
  $valor="si";
  if($boolean=="pendiente"){ 
        
    $sql7 = "SELECT * FROM `reservas_atendidas`";
    $result7 = mysqli_query($conexion, $sql7);
    while ($mostrar7=mysqli_fetch_array($result7)) {
         if($mostrar6['id_aula']==$mostrar7['id_aula']){

              $sql8 = "SELECT * FROM `reserva`";
              $result8 = mysqli_query($conexion, $sql8);
              while ($mostrar8=mysqli_fetch_array($result8)) {  
                  if($mostrar7['id_reserva']==$mostrar8['id_reserva']){
                      if($fecha_reserva==$mostrar8['fecha_reserva']){
                              if($hora_inicio==$mostrar8['hora_inicio']){
                                $valor="no";
                              }
                              else if($hora_fin==$mostrar8['hora_fin']){
                                  $valor="no";
                              }
                      }
                  }
              }
         }
    }
      if($valor=="si"){
        if($mostrar6['capacidad']>$cap_est){
      $contador++;
      echo "<div id='reserva1'><form action='car2.php' method='post'>
      <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
      <input type='hidden' name='id_reserva2' value=". $id_reserva .">
      <input type='hidden' name='fecha_solicitud' value=". $fecha_solicitud .">
      <input type='hidden' name='fecha_reserva' value=". $fecha_reserva .">
      <input type='hidden' name='capEstudiantes' value=". $cap_est .">
      <input type='hidden' name='id_docente' value=". $id_docente .">
      <input type='hidden' name='id_materia' value=". $id_materia .">
      <input type='hidden' name='grupo' value=". $grupo .">
      <input type='hidden' name='hora_inicio' value=". $hora_inicio .">
      <input type='hidden' name='hora_fin' value=". $hora_fin .">".
      "<input type='hidden' name='id_aula' style='background: blue;' value=".$mostrar6['id_aula'].">
      <input type='submit' id='asignar' name='asignar' value=".$mostrar6['codigo_aula']."-Cap".$mostrar6['capacidad'].">
      </form> </div>";
      }
        }  
  }
 }



    if($contador==0 && $boolean=="pendiente"){
      echo "<form name='envia' method='POST' action='asignaciones_Conjutas.php'>
      <input type=hidden name=id_solicitud_Pend value=$id_reserva>
      </form>
      <script language="."JavaScript".">
      document.envia.submit()
      </script>";
    }
    ?>
    <form action="vistaDetRese.php" method="post" >
                    <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $id_solicitud?> ">
                        <input id="btn1"  type="submit" value="Atras" >
    </form>
    <?php
     echo "</div>";
    ?>
     </div>              
    <script src="librerias/jquery/jquery-3.3.1.min.js"></script>
    <script src="librerias/popper/popper.min.js"></script>
    <script src="librerias/plugins/sweetAlert2/sweetalert2.all.min.js"></script>
</body>
</html>