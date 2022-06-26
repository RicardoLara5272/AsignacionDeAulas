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
$id_solicitud_Pend = $_REQUEST['id_solicitud_Pend'];
$estado = $_REQUEST['enviar'];
$mostrar_mensaje = '';
$tipo_solicitud;

$sqlIdSolicitudes = "SELECT * FROM `reserva` where id_reserva=" . $id_solicitud_Pend;
$query = $conexion->prepare($sqlIdSolicitudes);
$query->execute();
$result_reserva_rechazar = $query->fetchAll(PDO::FETCH_ASSOC);
$data_reserva_rechazar = [];
foreach ($result_reserva_rechazar as $key => $value) {
  $data_reserva_rechazar = $value;
  break;
}
$grupos = json_decode($data_reserva_rechazar['grupo']);
if (count($grupos) > 1) {
  $tipo_solicitud = "Compartido";
} else {
  $tipo_solicitud = "Individual";
}

//materia
$materia = "SELECT * FROM `materias` where id_materia=" . $data_reserva_rechazar['id_materia'];
$query = $conexion->prepare($materia);
$query->execute();
$resultado_materia = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_materia as $key => $value) {
  $data_reserva_rechazar['materia'] = $value;
  break;
}
//docente
$docente = "SELECT doc.* FROM solicitudes d INNER JOIN docentes doc ON d.id_docente=doc.id_docente WHERE d.id_solicitudes=" . $data_reserva_rechazar['id_solicitudes'];
$query = $conexion->prepare($docente);
$query->execute();
$resultado_docente = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($resultado_docente as $key => $value) {
  $data_reserva_rechazar['docente'] = $value;
  break;
}
$reservas_atendidas = "SELECT * FROM `reservas_atendidas` where id_reserva=" . trim($data_reserva_rechazar['id_reserva']);
$query2 = $conexion->prepare($reservas_atendidas);
$query2->execute();
$existe = false;
$resultado_reservas_atendidas = $query2->fetchAll(PDO::FETCH_ASSOC);

$estado_reserva = "";
if (count($resultado_reservas_atendidas) > 0) {
  foreach ($resultado_reservas_atendidas as $key => $value) {
    if ($value['estado'] == 'Rechazado' || $value['estado'] == 'Aceptado') {
      $existe = true;
      $estado_reserva = $value['estado'];
    }
    break;
  }
}
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
?>
<!-- codigo -->
<main class="content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <br>
        <div class="card-header text-center">
          <h2>Rechazar Solicitud <?php echo $tipo_solicitud ?></h2>
        </div>
      
        <div class="card-body">
          <div class="center-list">
            <ul>
              <li><strong>ID Reserva:</strong> <span><?php echo ($data_reserva_rechazar['id_reserva']); ?></span></li>
              <li><strong>Docente:</strong> <span><?php echo ($data_reserva_rechazar['docente']['nombre_docente']); ?></span></li>
              <li><strong>Materia:</strong> <span><?php echo ($data_reserva_rechazar['materia']['nombre_materia']); ?></span></li>
              <li><strong>Fecha de Reserva:</strong> <span><?php echo ($data_reserva_rechazar['fecha_reserva']); ?></span></li>
              <li><strong>Hora de Reserva:</strong> <span><?php echo ($data_reserva_rechazar['hora_inicio'] . ' - ' . $data_reserva_rechazar['hora_fin']); ?></span></li>
              <li><strong>Capacidad de Estudiantes:</strong> <span><?php echo ($data_reserva_rechazar['capEstudiantes']); ?></span></li>
              <li><strong>Detalle:</strong> <span><?php echo ($data_reserva_rechazar['detalle']); ?></span></li>
            </ul>
          </div>
        </div>
        <div class="card-body">
          <div class="center-list">
            <form id="formluario" name="formulario" method="post">
                  <div class="form-group">
                    <strong><label for="motivo">Motivo para el rechazo de la solicitud:</label></strong>
                    <textarea style="width: 390px;" rows="4" class="form-control" name="motivo" id="motivo" cols="30" rows="10" onkeypress="return sololetras(event)"></textarea>
                  </div><br>
                  <div class="form-group">         
                    <div id="botones" class="col-12">
                      <div style="text-align:right">
                        <?php if ($existe == false) { ?>
                          <button id='btn2' type='button' class="btn btn-primary" onClick="enviar('recibir_Rechazar.php')">RECHAZAR</button>
                        <?php
                        } else { ?>
                        <?php
                          $color_emergente = '';
                          //var_dump($estado_reserva);
                          //$estado_reserva;
                          if (strtolower($estado_reserva) == 'rechazado') {
                            $color_emergente = 'danger';
                          } elseif (strtolower($estado_reserva) == 'aceptado') {
                            $color_emergente = 'success';
                          } else {
                            $color_emergente = 'info';
                          }
                          $mostrar_mensaje =
                            '<div class="alert alert-' . $color_emergente . ' d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                                  <use xlink:href="#info-fill" />
                                </svg>
                                <div>
                                  La reserva ha sido ' . $estado_reserva . ' anteriormente!!
                                </div>
                              </div>';
                          echo $mostrar_mensaje;
                        }
                        ?>
                        <input type="hidden" name="id_reserva" value="<?php echo ($data_reserva_rechazar['id_reserva']); ?>">
                        <input type="hidden" name="id_solicitud_Pend" value="<?php echo ($data_reserva_rechazar['id_solicitudes']); ?>">
                        <input type="hidden" name="estado" value="<?php echo ($estado); ?>">
                        <button id="btn1" type="button" class="btn btn-danger" onClick="enviar2('vistaDetRese.php')">ATRAS</button>
                      </div>
                    </div>
                  </div>
                
              
            </form>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script>
  function sololetras(e) {
    key = e.keyCode || e.which;
    teclado = String.fromCharCode(key).toLowerCase();
    letras = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijgklmnopqrstuvwxyz1234567890,.:;";
    especiales = "8-37-38-46-164";
    teclado_especial = false;
    for (var i in especiales) {
      if (key == especiales[i]) {
        teclado_especial = true;
        break;
      }
    }
    if (letras.indexOf(teclado) == -1 && !teclado_especial) {
      return false;
    }
  }

  function enviar2(destino) {
    document.formulario.action = destino;
    document.formulario.submit();
    //    alert('holaa');
  }

  function validar(texto) {
    for (var j = 0; j < 69; j++) {
      var res = true;
      var letras = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijgklmnopqrstuvwxyz1234567890,.:;";
      console.log(letras[j]);
      if (texto == letras[j]) {
        res = false;
      }
    }
    return res;
  }

  function enviar(destino) {
    let motivo = document.getElementById("motivo").value;
    var res = true;
    if (!motivo == '' && !motivo == ' ') {
      for (var i = 0; i < motivo.length; i++) {

        if (res == true) {
          if (motivo[i] != 'A' && motivo[i] != 'B' && motivo[i] != 'C' && motivo[i] != 'D' && motivo[i] != 'E' && motivo[i] != 'F' && motivo[i] != 'G' && motivo[i] != 'H' &&
            motivo[i] != 'I' && motivo[i] != 'J' && motivo[i] != 'K' && motivo[i] != 'L' && motivo[i] != 'M' && motivo[i] != 'N' && motivo[i] != 'O' && motivo[i] != 'P' &&
            motivo[i] != 'Q' && motivo[i] != 'R' && motivo[i] != 'S' && motivo[i] != 'T' && motivo[i] != 'V' && motivo[i] != 'W' && motivo[i] != 'X' && motivo[i] != 'Y' &&
            motivo[i] != 'Z' && motivo[i] != 'a' && motivo[i] != 'b' && motivo[i] != 'c' && motivo[i] != 'd' && motivo[i] != 'e' && motivo[i] != 'f' && motivo[i] != 'g' && motivo[i] != 'h' &&
            motivo[i] != 'i' && motivo[i] != 'j' && motivo[i] != 'k' && motivo[i] != 'l' && motivo[i] != 'm' && motivo[i] != 'n' && motivo[i] != 'o' && motivo[i] != 'p' &&
            motivo[i] != 'q' && motivo[i] != 'r' && motivo[i] != 's' && motivo[i] != 't' && motivo[i] != 'v' && motivo[i] != 'w' && motivo[i] != 'x' && motivo[i] != 'y' &&
            motivo[i] != 'z' && motivo[i] != '1' && motivo[i] != '2' && motivo[i] != '3' && motivo[i] != '4' && motivo[i] != '5' && motivo[i] != '6' && motivo[i] != '7' &&
            motivo[i] != '8' && motivo[i] != '9' && motivo[i] != '0' && motivo[i] != ',' && motivo[i] != '.' && motivo[i] != ':' && motivo[i] != ';' && motivo[i] != ' ' &&
            motivo[i] != 'U' && motivo[i] != 'u'
          ) {
            res = false;
            alert('No se permiten caraecteres especiales');
          }
        }
        if (res == true && i + 1 < motivo.length && motivo[i] == ' ' && motivo[i + 1] == ' ') {
          res = false;
          alert('Demasiados espacios vacios');
        } else if (res == true && i == motivo.length - 1) {
          Swal.fire({
            title: "¿Esta seguro en rechazar la solicitud?",
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'SI',
            cancelButtonText: 'NO'
          }).then((result) => {
            if (result.value) {
              document.formulario.action = destino;
              document.formulario.submit();

              Swal.fire(
                'Rechazado!',
                'Se envio el formulario',
                'success'
              )
            }
          });
        }
      }
    } else {
      alert('Rellene los espacios vacios');
    }
  }
</script>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>