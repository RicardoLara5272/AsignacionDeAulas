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
$conexion = $db;
$_POST["fecha"] = date("Y-m-d");
$id_docente = $_SESSION['id_docente'];
$id_reserva = $_REQUEST['id_solicitud_Pend'];

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
//solicitudes
$result3 = $conexion->prepare("SELECT * FROM `solicitudes`");
$result3->execute();
$mostrard = $result3->fetchAll(PDO::FETCH_ASSOC);
foreach ($mostrard as $mostrar3) {
  if ($id_solicitud == $mostrar3['id_solicitudes']) {
    $id_docente = $mostrar3['id_docente'];
    $fecha_solicitud = $mostrar3['fecha_solicitud'];
  }
}
$id_materia=$data_consultar['materia']['id_materia'];
$fecha_reserva=$data_consultar['fecha_reserva'];
$hora_inicio = $data_consultar['hora_inicio'];
$hora_fin = $data_consultar['hora_fin'];
(int)$suma = 0;
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
          <?php
          $boolean = "pendiente";
          $cap_est = $data_consultar['capEstudiantes'];
          if ($boolean == "pendiente") {
            echo "
            <div>
            <div class='alert alert-warning d-flex align-items-center' role='alert'>
              <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Warning:'><use xlink:href='#exclamation-triangle-fill'/></svg>
              <div>
              No existen aulas con esa capacidad en ese horario, seleccione aulas
              </div>
            </div>
            </div>";
          }
          ?>
          <div class="aulasReservar">
            <?php
            $contador = 0;

            $result6 = $conexion->prepare("SELECT * FROM `aulas`");
            $result6->execute();
            $mostrari = $result6->fetchAll(PDO::FETCH_ASSOC);
            foreach ($mostrari as $mostrar6) {
              $valor = "si";
              $result9 = $conexion->prepare("SELECT * FROM `auxiliar`");
              $result9->execute();
              $mostrarj = $result9->fetchAll(PDO::FETCH_ASSOC);
              foreach ($mostrarj as $mostrar9) {
                if ($mostrar9['id_aula'] == $mostrar6['id_aula']) {
                  $valor = "no";
                  $result11 = $conexion->prepare("SELECT * FROM `aulas`");
                  $result11->execute();
                  $mostrark = $result11->fetchAll(PDO::FETCH_ASSOC);
                  foreach ($mostrark as $mostrar11) {
                    if ($mostrar9['id_aula'] == $mostrar11['id_aula']) {
                      $suma = $suma + (int)$mostrar11['capacidad'];
                    }
                  }
                }
              }
              if ($boolean == "pendiente") {
                $result7 = $conexion->prepare("SELECT * FROM `reservas_atendidas`");
                $result7->execute();
                $mostrarn = $result7->fetchAll(PDO::FETCH_ASSOC);
                foreach ($mostrarn as $mostrar7) {
                  if ($mostrar6['id_aula'] == $mostrar7['id_aula']) {
                    $result8 = $conexion->prepare("SELECT * FROM `reserva`");
                    $result8->execute();
                    $mostrarm = $result8->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($mostrarm as $mostrar8) {
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
                (int)$capa = $cap_est;
                if ($valor == "si") {
                  $texto = $mostrar6['codigo_aula'] . "-Cap" . $mostrar6['capacidad'];
                  echo "<div class='reserva'><form action='card.php' method='post'  >" .
                    "<input type='hidden' name='id_aula'  value=" . $mostrar6['id_aula'] . ">" .
                    "<input type='hidden' name='id_reserva'  value=" . $id_reserva . ">" .
                    "<input type='submit' value=" . $texto . ">" . "</form> </div>";
                }
              }
            }
            ?>
          </div>
          <?php if ($boolean == "pendiente") {
            echo "
              <div class='mt-4'>
              <div class='alert alert-primary d-flex align-items-center' role='alert'>
                <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Info:'><use xlink:href='#info-fill'/></svg>
                <div>
                La capacidad seleccionada hasta ahora es: $suma
                </div>
              </div>
              </div>";
          } ?>
          <div class="aulas">
            <?php
            $result12 = $conexion->prepare("SELECT * FROM `auxiliar`");
            $result12->execute();
            $mostraro = $result12->fetchAll(PDO::FETCH_ASSOC);
            foreach ($mostraro as $mostrar12) {
              $resultq = $conexion->prepare("SELECT * FROM `aulas`");
              $resultq->execute();
              $mostrarq = $resultq->fetchAll(PDO::FETCH_ASSOC);
              foreach ($mostrarq as $mostrar13) {
                if ($mostrar12['id_aula'] == $mostrar13['id_aula']) {
                  echo "<div class='reserva2'><form action='desmarcar_aulas.php' method='post'  >" .
                    "<input type='hidden' name='id_aula'  value=" . $mostrar12['id_aula'] . ">" .
                    "<input type='hidden' name='id_reserva'  value=" . $id_reserva . ">" .
                    "<input type='submit' value=" . $mostrar13['codigo_aula'] . "-Cap" . $mostrar13['capacidad'] . ">" . "</form> </div>";
                }
              }
            }
            if ($boolean == "pendiente") {
              echo "<div> </div>";
              if ($suma >= $capa) {
                echo
                "
                <style type='text/css'>
                .aulasReservar { display:none !important; }
                #alerta{
                  display:none !important;}
              
                  </style>
                ";
                echo "<form action='AsignacionDeAulas.php' method='post'><div class='btnAsig' class='row text-center'>
                  <input type='hidden' name='id_solicitud_Pend' value=" . $id_solicitud . ">
                  <input type='hidden' name='id_reserva2' value=" . $id_reserva . ">
                  <input type='hidden' name='fecha_solicitud' value=" . $fecha_solicitud . ">
                  <input type='hidden' name='fecha_reserva' value=" . $fecha_reserva . ">
                  <input type='hidden' name='capEstudiantes' value=" . $cap_est . ">
                  <input type='hidden' name='id_docente' value=" . $id_docente . ">
                  <input type='hidden' name='id_materia' value=" . $id_materia . ">
                  <input type='hidden' name='hora_inicio' value=" . $hora_inicio . ">
                  <input type='hidden' name='hora_fin' value=" . $hora_fin . ">
                  
                  </div>
                  <button type='submit' id='asignar' class='btn btn-primary' name='asignar' style='position: absolute; bottom: 0;right:360px;'>ASIGNAR</button>
                  </form>";
              }
            }
            ?>
          </div>
          <div class="col-12">
            <div style="text-align:right">
              <br>
              <form action="formulario_Rechazar.php" method="post">
                <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $id_reserva; ?> ">
                <button type="submit" name="enviar" class="btn btn-secondary" style='position: absolute; bottom: 0; right:231px;'>RECHAZAR</button>
              </form>
              <form action="vistaDetRese.php" method="post">
                <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $id_solicitud ?> ">
                <button id="btn1" class="btn btn-danger" style='position: absolute; bottom: 0; right:140px;' type="submit" >ATRAS</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>