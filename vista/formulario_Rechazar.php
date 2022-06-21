<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
//if not logged in redirect to login page
if (!$user->is_logged_in()) {
    header('Location: login.php');
    exit();
}

//capturamos datos de la tabla solicitud
$id= $_POST['id_solicitud_Pend'];
if(strlen($id)==3){
  $id_reserva=$id[1];
}
if(strlen($id)==4){
  $id_reserva=$id[1].$id[2];
}
if(strlen($id)==5){
  $id_reserva=$id[1].$id[2].$id[3];
}

//$id_reserva= $_POST['id_solicitud_Pend'];
//$id_reserva=$id[1];
$boolean="pendiente";
$db_host = "localhost";
$db_nombre = "asignacionaulas";
$db_usuario = "root";
$db_contra = "";

$id_solicitud="";
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

$conexion = mysqli_connect($db_host, $db_usuario, $db_contra, $db_nombre);

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
      
      $sql3 = "SELECT * FROM `solicitudes`";
      $result3 = mysqli_query($conexion, $sql3);
      while ($mostrar3 = mysqli_fetch_array($result3)) {
        if ($id_solicitud == $mostrar3['id_solicitudes']) {
              $id_docente = $mostrar3['id_docente'];
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

<!--<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="formulario.css">
  <link rel="stylesheet" href="librerias/plugins/sweetAlert2/sweetalert2.min.css" />
  <link rel="stylesheet" href="librerias/plugins/animate.css/animate.css" />
  <title>Formulario de Rechazo</title>
</head> -->

<body>
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
  </script>
  <script>
    function enviar2(destino) {
      document.formulario.action = destino;
      document.formulario.submit();
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
      let motivo = document.getElementById("experiencia").value;
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
              motivo[i] != '8' && motivo[i] != '9' && motivo[i] != '0' && motivo[i] != ',' && motivo[i] != '.' && motivo[i] != ':' && motivo[i] != ';' && motivo[i] != ' '
              && motivo[i] != 'U' && motivo[i] != 'u'
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
              confirmButtonText: 'Si, rechazar!'
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
//define page title
$title = 'Docentes Page';

//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
?>  
<main class="content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="card-header text-center">
                <h2>Rechazar Solicitud</h2>
              </div>
              <div id="for" class="row text-center">
                <form id="formluario" name="formulario" method="post">
                  <!-- <h3>ID: </h3>-->
                  <b><label for="ID">ID Reserva:</label></b>
                  <label><?php echo $id_reserva ?></label><br>
                  <b><label for="docente">Docente:</label></b><br/>
                  <label><?php echo $nombre_docente ?></label><br>
                  <b><label for="materia">Materia:</label></b><br/>
                  <label><?php echo $nom_materia ?></label><br>
                  <b><label for="fecha_solicitud">Fecha de Reserva:</label></b><br/>
                  <label for=""><?php echo $fecha_reserva ?></label><br>
                  <b><label for="fecha_reserva">Hora de Reserva:</label></b><br/>
                  <label for=""><?php echo $hora_inicio . " - ". $hora_fin ?></label><br>
                  <b><label for="detalle">Detalle:</label></b><br/>
                  <label for=""><?php echo $detalle ?></label><br>         
                  <label for="experiencia"><b>Motivo para el rechazo de la Solicitud</b></label><br/>
                  <textarea rows="4" style="width: 18em" id="experiencia" name="experiencia" onkeypress="return sololetras(event)"></textarea><br>
                    <?php
                      if($boolean=="pendiente"){
                        echo "<input type='hidden' id='fila' name='id_reserva' value='" . $id_reserva . "'>";
                      }
                      else if($boolean=="aceptado"){
                        echo "<input type='hidden' id='fila' name='id_reserva' value='" . $id_reserva . "'>";
                      }
                      else if($boolean=="rechazado"){
                        echo "<input type='hidden' id='fila' name='id_reserva' value='" . $id_reserva . "'>";
                      }
                    ?>
                  
                  <div id="botones"><br/>
                    <?php if($boolean=="pendiente"){
                      echo   "<button id='btn2' type='button' class='btn btn-danger' onClick="."enviar('recibir_Rechazar.php')>RECHAZAR</button>";
                    }?>
                    <button id="btn1" type="button" class="btn btn-primary" onClick="enviar2('vistaDetPend.php')">ATRAS</button>
                  </div>
                </form>
              </div>
            </div>
        </div>
    </div>
    <script src="librerias/jquery/jquery-3.3.1.min.js"></script>
    <script src="librerias/popper/popper.min.js"></script>
    <script src="librerias/plugins/sweetAlert2/sweetalert2.all.min.js"></script>
</main>
<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>




