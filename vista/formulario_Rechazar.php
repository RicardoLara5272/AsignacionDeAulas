<?php
//  $motivo=$_POST[]
//capturamos datos de la tabla solicitud
$id_solicitud = $_GET["id_solicitud2"];
$fecha_solicitud = $_GET["fecha_solicitud2"];
$fecha_reserva = $_GET["fecha_reserva2"];
$cantidad_estudiantes = $_GET["cantidad_estudiantes2"];
$detalle = $_GET["detalle2"];
$id_docente_materia = $_GET["id_docente_materia2"];

$db_host = "localhost";
$db_nombre = "asignacionaulas";
$db_usuario = "root";
$db_contra = "";
$id_docente = "";
$id_materia = "";
$conexion = mysqli_connect($db_host, $db_usuario, $db_contra, $db_nombre);
$sql = "SELECT * FROM `docente_materia`";
$result = mysqli_query($conexion, $sql);
while ($mostrar = mysqli_fetch_array($result)) {
  if ($id_docente_materia == $mostrar['id_docente_materia']) {
    $id_docente = $mostrar['id_docente'];
    $id_materia = $mostrar['id_materia'];
  }
}
$sql2 = "SELECT * FROM `docente`";
$result2 = mysqli_query($conexion, $sql2);
$nombre_Docente = "";
$apellido = "";
while ($mostrar2 = mysqli_fetch_array($result2)) {
  if ($mostrar2['id_docente'] == $id_docente) {
    $nombre_Docente = $mostrar2['nombres'];
    $apellido = $mostrar2['apellidos'];
  }
}

$sql3 = "SELECT * FROM `materia`";
$result3 = mysqli_query($conexion, $sql3);
$materia = "";
while ($mostrar3 = mysqli_fetch_array($result3)) {
  if ($id_materia == $mostrar3['id_materia']) {
    $materia = $mostrar3['nombre_materia'];
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
  <title>Formulario de Rechazo</title>
</head>

<body>
  <script>
    function sololetras(e) {
      key = e.keyCode || e.which;
      teclado = String.fromCharCode(key).toLowerCase();
      letras = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijgklmnopqrstuvwqyz1234567890,.:;";
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
        var letras = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijgklmnopqrstuvwqyz1234567890,.:;";
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
              motivo[i] != '8' && motivo[i] != '9' && motivo[i] != '0' && motivo[i] != ',' && motivo[i] != '.' && motivo[i] != ':' && motivo[i] != ';' && motivo[i] != ' ') {
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
  include('layouts/header.php');
  include('layouts/navegacion.php');
  ?>

  <section>
    <div class="row text-center">
      <div class="col-lg-12">
        <h2 id="titulo">Rechazar Solicitud</h2>
      </div>
    </div>
  </section>
  <br />
  <div class="row text-center">
    <form id="formluario" name="formulario" method="post">
      <!-- <h3>ID: </h3>-->
      <b><label for="ID">ID Solicitud:</label></b><br/>
      <label><?php echo $id_solicitud ?></label><br>
      <b><label for="docente">Docente:</label></b><br/>
      <label><?php echo $nombre_Docente . $apellido ?></label><br>
      <b><label for="materia">Materia:</label></b><br/>
      <label><?php echo $materia ?></label><br>
      <b><label for="fecha_solicitud">Fecha de solicitud:</label></b><br/>
      <label for=""><?php echo $fecha_solicitud ?></label><br>
      <b><label for="fecha_reserva">Fecha de reserva:</label></b><br/>
      <label for=""><?php echo $fecha_reserva ?></label><br>
      <b><label for="detalle">Detalle:</label></b><br/>
      <label for=""><?php echo $detalle ?></label><br>
      <div class="campo">
        
        <label for="experiencia"><b>Motivo para el rechazo de la Solicitud</b></label>
        <textarea rows="6" style="width: 26em" id="experiencia" name="experiencia" onkeypress="return sololetras(event)"></textarea>
        <?php
        echo "<input type='hidden' id='fila' name='id_solicitud' value='" . $id_solicitud . "'>";
        ?>
        <button id="btn1" type="button" onClick="enviar2('aceptar_rechazar.php')">Atras</button>
        <button id="btn2" type="button" onClick="enviar('recibir_Rechazar.php')">Rechazar</button>
      </div>
    </form>
    </div>

    <script src="librerias/jquery/jquery-3.3.1.min.js"></script>
    <script src="librerias/popper/popper.min.js"></script>
    <script src="librerias/plugins/sweetAlert2/sweetalert2.all.min.js"></script>
</body>

</html>