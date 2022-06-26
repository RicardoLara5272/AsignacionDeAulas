<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
if (!$user->is_logged_in()) {
  header('Location: login.php');
  exit();
}
$conexion = $db;
//define page title
$title = 'Asignaciones';

//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');

$id_reserva = $_REQUEST['id_reserva2'];
$tipo_solicitud;

$sql = "SELECT * FROM `reserva` where id_reserva=" . $id_reserva;
$query = $conexion->prepare($sql);
$query->execute();
$result_reserva = $query->fetchAll(PDO::FETCH_ASSOC);
$data_consultar = [];
foreach ($result_reserva as $key => $value) {
  $data_consultar = $value;
  $id_solicitud = $value['id_solicitudes'];
  break;
}
$grupos = json_decode($result_reserva['grupo']);
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
$docente = "SELECT doc.*, d.fecha_solicitud FROM solicitudes d INNER JOIN docentes doc ON d.id_docente=doc.id_docente WHERE d.id_solicitudes=" . $data_consultar['id_solicitudes'];
$query = $conexion->prepare($docente);
$query->execute();
$resultado_docente = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_docente as $key => $value) {
  $data_consultar['docente'] = $value;
  break;
}
$id_materia = $data_consultar['id_materia'];
$grupos = json_decode($data_consultar['grupo']);


?>
<main class="content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <br>
        <div class="card-header text-center">
          <?php if (count($grupos) > 1) { ?>
            <h2>Asignacion a Solicitud Compartido</h2>
          <?php } else { ?>
            <h2>Asignacion a Solicitud Individual</h2>
          <?php } ?>
        </div>
        <div class="card-body">
          <div class="list">
            <ul>
              <li><strong>ID Solicitud:</strong> <span><?php echo ($data_consultar['id_solicitudes']); ?></span></li>
              <li><strong>ID Reserva:</strong> <span><?php echo ($data_consultar['id_reserva']); ?></span></li>
              <li><strong>Materia:</strong> <span><?php echo ($data_consultar['materia']['nombre_materia']); ?></span></li>
              <li><strong>Solicitud compartida para los docentes - grupo:</strong><span><br>
                  <?php for ($i = 0; $i < count($grupos); $i++) {
                    $sql = "SELECT doc.nombre_docente, d.id_grupo FROM docente_materia d INNER JOIN docentes doc ON d.id_docente=doc.id_docente WHERE d.id_materia=$id_materia AND d.id_grupo='$grupos[$i]'";
                    $query = $conexion->prepare($sql);
                    $query->execute();
                    $docente_grupo = $query->fetchAll(PDO::FETCH_ASSOC);
                    echo ($docente_grupo[0]['nombre_docente'] . ' - ' . $docente_grupo[0]['id_grupo']); ?> <br>
                  <?php } ?></span></li>
              <li><strong>Solicitado por el docente:</strong> <span><?php echo ($data_consultar['docente']['nombre_docente']); ?></span></li>
              <li><strong>Fecha de la solicitud:</strong> <span><?php echo ($data_consultar['docente']['fecha_solicitud']); ?></span></li>
              <li><strong>Fecha de Reserva:</strong> <span><?php echo ($data_consultar['fecha_reserva']); ?></span></li>
              <li><strong>Hora de Reserva:</strong> <span><?php echo ($data_consultar['hora_inicio'] . ' - ' . $data_consultar['hora_fin']); ?></span></li>
              <li><strong>Capacidad de estudiantes:</strong> <span><?php echo ($data_consultar['capEstudiantes']); ?></span></li>
              <li><strong>Detalle de la reserva:</strong> <span><?php echo ($data_consultar['detalle']); ?></span></li>
              <?php
              $sql = "SELECT aulas.codigo_aula FROM aulas INNER JOIN auxiliar ON aulas.id_aula=auxiliar.id_aula";
              $query = $conexion->prepare($sql);
              $query->execute();
              $aulas_grupo = $query->fetchAll(PDO::FETCH_ASSOC);
              if (count($aulas_grupo) > 1) { ?>
                <li><strong>Aulas asignadas:</strong> <span><br>
                  <?php } else { ?>
                <li><strong>Aula asignada:</strong> <span><br>
                  <?php }
                for ($i = 0; $i < count($aulas_grupo); $i++) {
                  echo ($aulas_grupo[$i]['codigo_aula']);
                  if (!($i == (count($aulas_grupo) - 1))) {
                    echo (' - ');
                  }
                } ?></span></li>
            </ul>
          </div>
          <form action='./funciones_asignacion_aceptar.php' method='post'>
            <div class="col-4">
              <div class="form-group">
                <label for='mensaje'><b>Respuesta de asignacion:</b></label>
                <textarea id='mensaje' name='mensaje' rows="4" class="form-control" cols="30" placeholder="Escribe tu mensaje aquí (Opcional)"></textarea><br>
                <input type="hidden" id="id_reserva" name="id_reserva" value="<?php echo $id_reserva ?>" >
                <input type="hidden" name="id_solicitud_Pend" value=" <?php echo $id_solicitud ?> ">
                <button class="btn btn-primary btnGuardar" type="submit" id="btnGuardar">ENVIAR Y GUARDAR</button>
                <button class="btn btn-danger text-center btnCancelar" type="submit" id="btnCancelar" formaction="./vistaDetRese.php">CANCELAR</button>
              </div>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</main>
<?php
//include header template
//<input type='hidden' name='id_solicitud_Pend' value=".$id_solicitud_Pend."'>
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>