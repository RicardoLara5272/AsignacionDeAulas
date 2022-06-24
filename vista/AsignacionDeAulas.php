<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
if (!$user->is_logged_in()) {
  header('Location: login.php');
  exit();
}

//define page title
$title = 'Asignacion de aulas Page';

//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
$_POST["fecha"] = date("Y-m-d");
$id_solicitud = $_POST["id_solicitud_Pend"];
$id_sol_DetPend = $_REQUEST['id_solicitud_Pend'];
$id_reserva = $_POST['id_reserva2'];
$fecha_solicitud = $_POST["fecha_solicitud"];
$fecha_reserva = $_POST["fecha_reserva"];
$cantidad_estudiantes = $_POST["capEstudiantes"];
$id_docente = $_POST['id_docente'];
//$id_materia = (int)$_POST["id_materia"];
$hora_inicio = $_POST["hora_inicio"];
$hora_fin = $_POST["hora_fin"];
$conexion = $db;
$nombre = "nom";
$correo = "corr";
$aulas_asignadas = array();

var_dump($_REQUEST);

$consultar_grupo = $conexion->prepare("SELECT * FROM reserva WHERE id_reserva ='" . $id_reserva . "' AND id_solicitudes =" . $id_solicitud);
$consultar_grupo->execute();
$grupo = $consultar_grupo->fetchAll(PDO::FETCH_ASSOC);
foreach ($grupo as $mostrar_grupo) {
  $grupos = json_decode($mostrar_grupo['grupo']);
  $id_materia = $mostrar_grupo['id_materia'];
}

$nom_materia=$conexion->prepare("SELECT * FROM `materias` WHERE id_materia = $id_materia");
$nom_materia->execute();
$la_materia=$nom_materia->fetchAll(PDO::FETCH_ASSOC);

foreach($la_materia as $mamateria) {
  $rempl=$mamateria['nombre_materia'];
  $detectamos=mb_detect_encoding($rempl, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
  $remplazo = iconv($detectamos, 'UTF-8', $rempl);
  $nombre_materia=$rempl;
}


$IDsDocente = array();
$nombres = array();
$correos = array();
$grupo_ordenado = array();
$IDs = array();

$IDdocente = $conexion->prepare("SELECT * FROM `docente_materia` WHERE id_materia = $id_materia");
$IDdocente->execute();
$el_id_docente = $IDdocente->fetchAll(PDO::FETCH_ASSOC);
//var_dump($el_id_docente);
foreach ($el_id_docente as $elDocente) {
  for ($g = 0; $g < count($grupos); $g++) {
    if ($elDocente['id_grupo'] == $grupos[$g]) {
      $IDs[] = $elDocente['id_docente'];
      $LosDocentes = $conexion->prepare("SELECT * FROM docentes");
      $LosDocentes->execute();
      $nombre_docente = $LosDocentes->fetchAll(PDO::FETCH_ASSOC);
      foreach ($nombre_docente as $nom_docente) {
        if ($nom_docente['id_docente'] == $elDocente['id_docente']) {
          $IDsDocente[]=$nom_docente['id_docente'];
          $remplazo=$nom_docente['nombre_docente'];
          $detectar=mb_detect_encoding($remplazo, 'UTF-8, ISO-8859-1, WINDOWS-1252', true);
          $remplazo = iconv($detectar, 'UTF-8', $remplazo);
          $nombres[]=$remplazo;
          $correos[]=$nom_docente['correo'];
          $grupo_ordenado[]=$grupos[$g];
        }
      }
    }
  }
}
?>
<main class="content">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12 col-md-12 col-sm-12">
        <br>
        <div class="card-header text-center">
          <?php if (count($grupos) > 1) { ?>
            <h2>ASIGNACION A SOLICITUD COMPARTIDA</h2>
          <?php } else { ?>
            <h2>ASIGNACION A SOLICITUD INDIVIDUAL</h2>
          <?php } ?>
        </div>
        <?php
        if (count($grupos) > 1) {
          echo "<form action='funciones_asignacion_aceptar.php' method='post'><br>";
          echo  "<div class='elem-group'>";
          echo  "<center><label for='Materia'><b>Solicitud para la materia:</b> <br/> " . $nombre_materia . "</label>  ";
          echo  "<input type='hidden' id='las_materia' name='la_materia' value='" . $nombre_materia . ">";
          echo "</div>";

          echo  "<div class='elem-group'>";
          echo  "<center><label for='id_solicitud'><b>ID Solicitud:</b> <br/> " . $id_solicitud . "</label>  ";
          echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . ">";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo  "<input type='hidden' id='materia' name='materia' value=" . $id_materia . ">";
          echo "</div>";

          echo  "<div class='elem-group'>";
          echo  "<center><label for='id_reserva2'><b>ID Reserva:</b> <br/> " . $id_reserva . "</label>  ";
          echo  "<input type='hidden' id='id_reserva2' name='id_reserva2' value='" . $id_reserva . "</center>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='nombre'> <b>Docentes:</b></label><br>";
          if (count($nombres) >= count($grupos)) {
            for ($n = 0; $n < count($grupos); $n++) {
              echo "<label>$nombres[$n] </label><br>";
            }
          }
          echo "</center><input type='hidden' id='docente' name='docente' value=" . htmlspecialchars(serialize($nombres)) . ">";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='nombre'> <b>Grupos:</b> </label><br>";
          for ($g = 0; $g < count($grupos); $g++) {
            echo "<label>$grupos[$g] </label><br>";
          }
          echo "<input type='hidden' id='grupo' name='grupo' value='" . htmlspecialchars(serialize($grupos)) . "'>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='correo'><b>Correos:</b></label><br>";
          for($c=0; $c<count($grupos); $c++){
            echo "<label>$correos[$c] </label><br>";
          }
          echo "<input type='hidden' id='correo' name='correo' value='" . htmlspecialchars(serialize($correos)) . "'>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<input type='hidden' id='idCorrecto' name='idCorrecto' value='" . htmlspecialchars(serialize($IDsDocente)) . "'>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<input type='hidden' id='ordenado' name='ordenado' value='" . htmlspecialchars(serialize($grupo_ordenado)) . "'>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='fecha_solicitud'><b>Fecha de Solicitud: </b><br/> " . $fecha_solicitud . "</label>";
          echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . "'></center>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='fecha_reserva'><b>Fecha de Reserva: </b><br/> " . $fecha_reserva . "</label>";
          echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . "'></center>";
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:<br></b> " . $cantidad_estudiantes . "</label>";
          echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . "'></center>";
          echo "</div>";

          echo "<div class='elem-group'>";
          $consultar_aula = $conexion->prepare("SELECT * FROM aulas ");
          $consultar_aula->execute();
          $codigo_aula = $consultar_aula->fetchAll(PDO::FETCH_ASSOC);
          $numero = 1;
          $numero2 = 0;
          foreach ($codigo_aula as $cod_aula) {

            $consultar_auxiliar = $conexion->prepare("SELECT * FROM auxiliar ");
            $consultar_auxiliar->execute();
            $codigo_auxiliar = $consultar_auxiliar->fetchAll(PDO::FETCH_ASSOC);
            foreach ($codigo_auxiliar as $cod_auxiliar) {
              if ($cod_auxiliar['id_aula'] == $cod_aula['id_aula']) {
                echo "<center><label for='aula'><b>Aula $numero:</b></label>";
                echo "<center><label for='aula'>" . $cod_aula['codigo_aula'] . "</label>";
                $aulas_asignadas[$numero2] = $cod_aula['codigo_aula'];
                $numero += 1;
                $numero2 += 1;
              }
            }
          }
          echo "</div>";

          echo "<div class='elem-group'>";
          echo "<center><label for='message'><b>Respuesta de asignacion</b></label></center>";
          echo "<center><textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí (Opcional)'> </textarea></center>";
          echo "</div>";
          echo "<div class='elem-group'>";

          echo "</div>";
          echo "<input type='hidden' id='las_aulas' name='las_aulas' value=" . htmlspecialchars(serialize($aulas_asignadas)) . ">";
          echo "<input type='hidden' id='id_materia' name='id_materia' value=".$id_materia." >";
          echo "<input id='id_solicitud_Pend' name='id_solicitud_Pend' type='hidden' value='$id_solicitud'>";
          echo "<center><input type='submit' name='Enviar' id='Enviar' value='GUARDAR/ENVIAR'></center>";
          echo "</form>";
          echo "<center><form action='vistaDetRese.php' method='post'>
          <input type='hidden' name='id_solicitud_Pend' value=". $id_solicitud .">
          <input type='submit' name='Cancelar' value='CANCELAR'>
          </form></center>";
        } else {
          echo "<form action='funciones_asignacion_aceptar.php' method='post'><br>";
          echo  "<div class='elem-group'>";
          echo  "<center><label for='Materia'><b>Solicitud para la materia:</b> <br/> " . $nombre_materia . "</label>  ";
          echo  "<input type='hidden' id='las_materia' name='la_materia' value='" . $nombre_materia . ">";
          echo "</div>";
          
          echo  "<div class='elem-group'>";
          echo  "<center><label for='id_solicitud'><b>ID Solicitud:</b> <br/> " . $id_solicitud . "</label>  ";
          echo  "<input type='hidden' id='id_solicitud' name='id_solicitud' value='" . $id_solicitud . "</center>";
          echo  "<input type='hidden' id='materia' name='materia' value='" . $id_materia . "</center>";
          echo "</div>";
          echo  "<div class='elem-group'>";
          echo  "<center><label for='id_reserva2'><b>ID Reserva:</b> <br/> " . $id_reserva . "</label>  ";
          echo  "<input type='hidden' id='id_reserva2' name='id_reserva2' value='" . $id_reserva . "</center>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='nombre'> <b>Docentes:</b></label><br>";
          for($n=0; $n<count($grupos); $n++){
            echo "<label>$nombres[$n] </label><br>";
          }
          echo "<input type='hidden' id='nombre' name='nombre' value='" . htmlspecialchars(serialize($nombres)) . "'>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='nombre'> <b>Grupos:</b> </label><br>";
          for ($g = 0; $g < count($grupos); $g++) {
            echo "<label>$grupos[$g] </label><br>";
          }
          echo "<input type='hidden' id='grupo' name='grupo' value='" . htmlspecialchars(serialize($grupos)) . "'>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='correo'><b>Correos:</b></label><br>";
          for ($c = 0; $c < count($grupos); $c++) {
            echo "<label>$correos[$c] </label><br>";
          }
          echo "<input type='hidden' id='correo' name='correo' value='" . htmlspecialchars(serialize($correos)) . "'>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<input type='hidden' id='idCorrecto' name='idCorrecto' value='" . htmlspecialchars(serialize($IDsDocente)) . "'>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<input type='hidden' id='ordenado' name='ordenado' value='" . htmlspecialchars(serialize($grupo_ordenado)) . "'>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='fecha_solicitud'><b>Fecha de Solicitud: </b><br/> " . $fecha_solicitud . "</label>";
          echo "<input type='hidden' id='fecha_solicitud' name='fecha_solicitud' value='" . $fecha_solicitud . "'></center>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='fecha_reserva'><b>Fecha de Reserva: </b><br/> " . $fecha_reserva . "</label>";
          echo "<input type='hidden' id='fecha_reserva' name='fecha_reserva' value='" . $fecha_reserva . "'></center>";
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='cantidad_estudiantes'><b>Capacidad de Estudiantes:<br></b> " . $cantidad_estudiantes . "</label>";
          echo "<input type='hidden' id='cantidad_estudiantes' name='cantidad_estudiantes' value='" . $cantidad_estudiantes . "'></center>";
          echo "</div>";
          echo "<div class='elem-group'>";
          $consultar_aula = $conexion->prepare("SELECT * FROM aulas ");
          $consultar_aula->execute();
          $codigo_aula = $consultar_aula->fetchAll(PDO::FETCH_ASSOC);
          $numero = 1;
          $numero2 = 0;
          foreach ($codigo_aula as $cod_aula) {
            $consultar_auxiliar = $conexion->prepare("SELECT * FROM auxiliar ");
            $consultar_auxiliar->execute();
            $codigo_auxiliar = $consultar_auxiliar->fetchAll(PDO::FETCH_ASSOC);
            foreach ($codigo_auxiliar as $cod_auxiliar) {
              if ($cod_auxiliar['id_aula'] == $cod_aula['id_aula']) {
                echo "<center><label for='aula'><b>Aula $numero:</b></label>";
                echo "<center><label for='aula'>" . $cod_aula['codigo_aula'] . "</label>";
                $aulas_asignadas[] = $cod_aula['codigo_aula'];
                $numero += 1;
                $numero2 += 1;
              }
            }
          }
          echo "</div>";
          echo "<div class='elem-group'>";
          echo "<center><label for='message'><b>Respuesta de asignacion</b></label></center>";
          echo "<center><textarea id='respuesta' name='mensaje' placeholder='Escribe tu mensaje aquí (Opcional)'> </textarea></center>";
          echo "</div>";

          echo "</div>";
          echo "<input type='hidden' id='las_aulas' name='las_aulas' value=" . htmlspecialchars(serialize($aulas_asignadas)) . "'>";
          echo "<div class='elem-group'>";
          echo "<input id='id_solicitud_Pend' name='id_solicitud_Pend' type='hidden' value='$id_solicitud'>";
          echo "<center><input type='submit'  class='btn btn-primary' name='Enviar' id='Enviar' value='GUARDAR/ENVIAR'></center>";
          echo "</form>";
          echo "<center><form action='vistaDetRese.php' method='post'>
                <input id='id_solicitud_Pend' name='id_solicitud_Pend' type='hidden' value='$id_solicitud'>
                <button type='submit' class='btn btn-danger' name='Cancelar'>CANCELAR</button>
              </form></center>";
    
        }
        ?>
      </div>
    </div>
  </div>
</main>
<?php
//include header template
//<input type='hidden' name='id_solicitud_Pend' value=".$id_solicitud_Pend."'>
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>