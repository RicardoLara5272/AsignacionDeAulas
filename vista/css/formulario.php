<?php
        $nombre=$_POST['Nombre'];
        $materia=$_POST['Materia'];
        $grupo=$_POST['Grupo'];
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
function enviar2(destino){
  document.formulario.action=destino;
  document.formulario.submit();
 }
function validar(texto){
    for(var j=0;j<69;j++){
      var res=true;
      var  letras = " ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijgklmnopqrstuvwqyz1234567890,.:;";
           console.log(letras[j]);
            if(texto==letras[j]){
              res=false;
            }   
    }
    return res;
}

function enviar(destino){
      let motivo=document.getElementById("experiencia").value;
      var res=true;
      if(!motivo=='' && !motivo==' '){ 
       for(var i=0;i<motivo.length;i++){
         
        if(res==true){
          if(motivo[i]!='A' && motivo[i]!='B' && motivo[i]!='C' && motivo[i]!='D' && motivo[i]!='E' && motivo[i]!='F' && motivo[i]!='G' && motivo[i]!='H'
          && motivo[i]!='I' && motivo[i]!='J' && motivo[i]!='K' && motivo[i]!='L' && motivo[i]!='M' && motivo[i]!='N' && motivo[i]!='O' && motivo[i]!='P'
          && motivo[i]!='Q' && motivo[i]!='R' && motivo[i]!='S' && motivo[i]!='T' && motivo[i]!='V' && motivo[i]!='W' && motivo[i]!='X' && motivo[i]!='Y'
          && motivo[i]!='Z' && motivo[i]!='a' && motivo[i]!='b' && motivo[i]!='c' && motivo[i]!='d' && motivo[i]!='e' && motivo[i]!='f' && motivo[i]!='g' && motivo[i]!='h'
          && motivo[i]!='i' && motivo[i]!='j' && motivo[i]!='k' && motivo[i]!='l' && motivo[i]!='m' && motivo[i]!='n' && motivo[i]!='o' && motivo[i]!='p'
          && motivo[i]!='q' && motivo[i]!='r' && motivo[i]!='s' && motivo[i]!='t' && motivo[i]!='v' && motivo[i]!='w' && motivo[i]!='x' && motivo[i]!='y'
          && motivo[i]!='z'&& motivo[i]!='1' && motivo[i]!='2' && motivo[i]!='3' && motivo[i]!='4' && motivo[i]!='5' && motivo[i]!='6' && motivo[i]!='7' 
          && motivo[i]!='8' && motivo[i]!='9' && motivo[i]!='0' && motivo[i]!=',' && motivo[i]!='.' && motivo[i]!=':' && motivo[i]!=';' && motivo[i]!=' '  ){
             res=false;
             alert('No se permiten caraecteres especiales');
          }
        }

           if(res==true && i+1<motivo.length && motivo[i]==' ' && motivo[i+1]==' '){
           res=false;
           alert('Demasiados espacios vacios'); 
           }
          
          

           else if(res==true && i==motivo.length-1){ 
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
              document.formulario.action=destino;
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
    }
    else{
         alert('Rellene los espacios vacios');
    }
 }

   
  </script>

  <div>
    <h1 id="titulo">Formulario de Rechazo</h1>

    <br />
  </div>
  <form id="formluario" name="formulario" method="post" >
  <p>Docente: </p>
    <input type="text" name='prueba' value='<?php echo $nombre ?>'>
     <p>Materia: </p> 
     <input type="text" name='materia' value='<?php echo $materia ?>'>
      <p>Grupo: </p>
     <h3 for=""  id="grupo">
     <input type="text" name='grupo' value='<?php echo $grupo ?>'>
     </h3>

    <div class="campo">
      <br />
      <label for="experiencia"><strong>Motivo para el rechazo de la Solicitud </strong></label>
      <textarea rows="6" style="width: 26em" id="experiencia" name="experiencia"
        onkeypress="return sololetras(event)"></textarea>
        <button id="btn1" type="button" onClick="enviar2('recolectarDatos.php')" >Atras</button>
        <button id="btn2" type="button" onClick="enviar('recibir.php')">Rechazar</button>
    </div>
  </form>
  <script src="Nueva carpeta/jquery/jquery-3.3.1.min.js"></s>
  <script src="Nueva carpeta/popper/popper.min.js"></script>
  <script src="Nueva carpeta/plugins/sweetAlert2/sweetalert2.all.min.js"></script>
</body>

</html>