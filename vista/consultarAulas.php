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
$id_reserva = $_REQUEST['id_solicitud_Pend'];
$id_solicitud = '';
$boolean = "pendiente";
$conexion = $db;
$mostrar_mensaje='';
$tipo_solicitud;


$result5= $conexion->prepare("SELECT * FROM `reservas_atendidas`");
$result5->execute();
$mostrarb=$result5->fetchAll(PDO::FETCH_ASSOC);

foreach($mostrarb as $mostrar5) {
  if($boolean=="pendiente"){
    if ($id_reserva == $mostrar5['id_reserva']) {
      $color_emergente='';
      $boolean=$mostrar5['estado'];
      if(strtolower($boolean)=='rechazado'){
        $color_emergente ='danger';
      }elseif (strtolower($boolean)=='aceptado') {
        $color_emergente ='success';
      }else{
        $color_emergente ='info';
      }
      $mostrar_mensaje =
        '<div class="alert alert-'.$color_emergente.' d-flex align-items-center" role="alert">
          <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
            <use xlink:href="#info-fill" />
          </svg>
          <div>
            La reserva ha sido ' . $boolean . ' anteriormente!!
          </div>
        </div>';
    }
  }
}

$sql = "SELECT * FROM `reserva` where id_reserva=" . trim($id_reserva);
$query = $conexion->prepare($sql);
$query->execute();
$result_consultar = $query->fetchAll(PDO::FETCH_ASSOC);
$data_consultar = [];
foreach ($result_consultar as $key => $value) {
  $data_consultar = $value;
  $id_solicitud = $value['id_solicitudes'];
  break;
}
$grupos = json_decode($data_consultar['grupo']);
if (count($grupos) > 1) {
  $tipo_solicitud = "Compartido";
} else {
  $tipo_solicitud = "Individual";
}
//materia
$materia = "SELECT * FROM `materias` where id_materia=" . $data_consultar['id_materia'];
$query_a = $conexion->prepare($materia);
$query_a->execute();
$resultado_materia = $query_a->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_materia as $key => $value) {
  $data_consultar['materia'] = $value;
  break;
}
//docente
$docente = "SELECT doc.* FROM solicitudes d INNER JOIN docentes doc ON d.id_docente=doc.id_docente WHERE d.id_solicitudes=" . $data_consultar['id_solicitudes'];
$query_sql = $conexion->prepare($docente);
$query_sql->execute();
$resultado_docente = $query_sql->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_docente as $key => $value) {
  $data_consultar['docente'] = $value;
  break;
}
$result3 = $conexion->prepare("SELECT * FROM `solicitudes`");
$result3->execute();
$mostrard = $result3->fetchAll(PDO::FETCH_ASSOC);
foreach ($mostrard as $mostrar3) {
  if ($id_solicitud == $mostrar3['id_solicitudes']) {
    $id_docente = $mostrar3['id_docente'];
    $fecha_solicitud = $mostrar3['fecha_solicitud'];
  }
}
$id_materia = $data_consultar['id_materia'];
$fecha_reserva = $data_consultar['fecha_reserva'];
$grupo = $data_consultar['grupo'];
$hora_inicio = $data_consultar['hora_inicio'];
$hora_fin = $data_consultar['hora_fin'];
$cap_est = $data_consultar['capEstudiantes'];
$detalle = $data_consultar['detalle'];
$codigo_materia = $data_consultar['materia']['codigo_materia'];
$nom_materia = $data_consultar['materia']['nombre_materia'];
$nivel = $data_consultar['materia']['nivel'];
$nombre_docente = $data_consultar['docente']['nombre_docente'];
$fecha_solicitud = $fecha_solicitud;
?>
<main class="content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <br>
        <div class="card-header text-center">
          <h2>Consultar Aulas</h2>
          <h3>Solicitud <?php echo $tipo_solicitud ?></h3>
        </div>
        <div class="card-body">
          <div>
            <ul>
              <li><strong>ID Reserva:</strong> <span><?php echo ($data_consultar['id_reserva']); ?></span></li>
              <li><strong>Docente:</strong> <span><?php echo ($data_consultar['docente']['nombre_docente']); ?></span></li>
              <li><strong>Materia:</strong> <span><?php echo ($data_consultar['materia']['nombre_materia']); ?></span></li>
              <li><strong>Fecha de Reserva:</strong> <span><?php echo ($data_consultar['fecha_reserva']); ?></span></li>
              <li><strong>Hora de Reserva:</strong> <span><?php echo ($data_consultar['hora_inicio'] . ' - ' . $data_consultar['hora_fin']); ?></span></li>
              <li><strong>Capacidad de estudiantes:</strong> <span><?php echo ($data_consultar['capEstudiantes']); ?></span></li>
              <li><strong>Detalle:</strong> <span><?php echo ($data_consultar['detalle']); ?></span></li>
            </ul>
          </div>
          
        </div>
        <div class="card-body">
          <div class="aulas">
            <!-- hhhhhh -->
            <?php
            $contador = 0;
            $cap_est = $data_consultar['capEstudiantes'];
            $result6 = $conexion->prepare("SELECT * FROM `aulas`");
            $result6->execute();
            $mostrarf = $result6->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($mostrarf as $mostrar6) {
              $valor = "si";
              if ($boolean == "pendiente") {
                $result7 = $conexion->prepare("SELECT * FROM `reservas_atendidas`");
                $result7->execute();
                $mostrarg = $result7->fetchAll(PDO::FETCH_ASSOC);
                foreach ($mostrarg as $mostrar7) {
                  if ($mostrar6['id_aula'] == $mostrar7['id_aula']) {
                    $result8 = $conexion->prepare("SELECT * FROM `reserva`");
                    $result8->execute();
                    $mostrarh = $result8->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($mostrarh as $mostrar8) {
                      if ($mostrar7['id_reserva'] == $mostrar8['id_reserva']) {
                        if ($fecha_reserva == $mostrar8['fecha_reserva']) {
                          if ($hora_inicio == $mostrar8['hora_inicio']) {
                            $valor = "no";
                          } else if ($hora_fin == $mostrar8['hora_fin']) {
                            $valor = "no";
                          }
                        }
                      }
                    }
                  }
                }
                if ($valor == "si") {
                  if ((int)$mostrar6['capacidad'] == (int)$cap_est) {
                    echo "<div class='reserva'><form action='car2.php' method='post'>
                    <input type='hidden' name='id_solicitud_Pend' value='{$id_solicitud}'>
                    <input type='hidden' name='id_reserva2' value='{$id_reserva}'>
                    <input type='hidden' name='fecha_solicitud' value='{$fecha_solicitud}'>
                    <input type='hidden' name='fecha_reserva' value='{$fecha_reserva}'>
                    <input type='hidden' name='capEstudiantes' value='{$cap_est}'>
                    <input type='hidden' name='id_docente' value='{$id_docente}'>
                    <input type='hidden' name='id_materia' value='{$id_materia}'>
                    <input type='hidden' name='grupo' value='{$grupo}'>
                    <input type='hidden' name='hora_inicio' value='{$hora_inicio}'>
                    <input type='hidden' name='hora_fin' value='{$hora_fin}'>
                    <input type='hidden' name='id_aula' style='background: blue;' value='{$mostrar6['id_aula']}'>
                    <input type='submit' id='asignar' name='asignar' value='{$mostrar6['codigo_aula']}-Cap{$mostrar6['capacidad']}'>
                    </form> </div>";
                    $contador++;
                  }
                }
              }
            }
            $result6 = $conexion->prepare("SELECT * FROM `aulas`");
            $result6->execute();
            $mostrarf = $result6->fetchAll(PDO::FETCH_ASSOC);
            foreach ($mostrarf as $mostrar6) {
              $valor = "si";
              if ($boolean == "pendiente") {
                $result7 = $conexion->prepare("SELECT * FROM `reservas_atendidas`");
                $result7->execute();
                $mostrarg = $result7->fetchAll(PDO::FETCH_ASSOC);
                foreach ($mostrarg as $mostrar7) {
                  if ($mostrar6['id_aula'] == $mostrar7['id_aula']) {
                    $result8 = $conexion->prepare("SELECT * FROM `reserva`");
                    $result8->execute();
                    $mostrarh = $result8->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($mostrarh as $mostrar8) {
                      if ($mostrar7['id_reserva'] == $mostrar8['id_reserva']) {
                        if ($fecha_reserva == $mostrar8['fecha_reserva']) {
                          if ($hora_inicio == $mostrar8['hora_inicio']) {
                            $valor = "no";
                          } else if ($hora_fin == $mostrar8['hora_fin']) {
                            $valor = "no";
                          }
                        }
                      }
                    }
                  }
                }
                if ($valor == "si") {
                  if ($mostrar6['capacidad'] > $cap_est) {
                    $contador++;
                    echo "<div class='reserva1'><form action='car2.php' method='post'>
                    <input type='hidden' name='id_solicitud_Pend' value='{$id_solicitud}'>
                    <input type='hidden' name='id_reserva2' value='{$id_reserva}'>
                    <input type='hidden' name='fecha_solicitud' value='{$fecha_solicitud}'>
                    <input type='hidden' name='fecha_reserva' value='{$fecha_reserva}'>
                    <input type='hidden' name='capEstudiantes' value='{$cap_est}'>
                    <input type='hidden' name='id_docente' value='{$id_docente}'>
                    <input type='hidden' name='id_materia' value='{$id_materia}'>
                    <input type='hidden' name='grupo' value='{$grupo}'>
                    <input type='hidden' name='hora_inicio' value='{$hora_inicio}'>
                    <input type='hidden' name='hora_fin' value='{$hora_fin}'>
                    <input type='hidden' name='id_aula' style='background: blue;' value='{$mostrar6['id_aula']}'>
                    <input type='submit' id='asignar' name='asignar' value='{$mostrar6['codigo_aula']}-Cap{$mostrar6['capacidad']}'>
                    </form> </div>";
                  }
                }
              }
            }
            ?>
          </div>
          <?php if ($contador == 0 && $boolean == "pendiente") {
            echo "<form name='envia' method='POST' action='asignaciones_Conjutas.php'>
            <input type=hidden name=id_solicitud_Pend value=$id_reserva>
            </form>
            <script language=" . "JavaScript" . ">
            document.envia.submit()
            </script>";
          } ?>
          <?php 
            echo $mostrar_mensaje;
          ?>
          <div class="col-12">
            <div style="text-align:right">
              <br>
              <form action="formulario_Rechazar.php" method="post">
                <input type="hidden" name="id_solicitud_Pend" value="<?php echo $id_reserva; ?>">
                <button type="submit" name="enviar" class="btn btn-secondary" style='position: absolute; bottom: auto; right:231px;' >RECHAZAR</button>
              </form>
              <form action="vistaDetRese.php" method="post">
                <input type="hidden" name="id_solicitud_Pend" value="<?php echo $id_solicitud ?>">
                <button id="btn1" class="btn btn-danger" style='position: absolute; bottom: auto; right:140px;' type="submit" >ATRAS</button>
              </form>
            </div>
          </div>
        </div>
        
        <!-- ggdhahdah -->
      </div>
    </div>
  </div>
</main>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>