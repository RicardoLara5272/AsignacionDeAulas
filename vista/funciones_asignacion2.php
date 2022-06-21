<?php
      ini_set("pcre.jit", "0");
      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\SMTP;
      use PHPMailer\PHPMailer\Exception;
    
      require 'librerias/phpmailer/src/PHPMailer.php';
      require 'librerias/phpmailer/src/SMTP.php';
      require 'librerias/phpmailer/src/Exception.php';

      if(isset($_POST["Enviar"])){
            $id_solicitud=$_POST["id_solicitud"];
            $id_reserva=(int)$_POST['id_reserva2'];
            $fecha_solicitud=$_POST["fecha_solicitud"];
            $fecha_reserva=$_POST["fecha_reserva"];
            $cantidad_estudiantes=$_POST["cantidad_estudiantes"];
            //$detalle=$_POST["detalle"];
            //$id_docente_materia=$_POST["id_docente_materia"];
            //$id_docente=$_POST["id_docente"];
            $grupo=unserialize($_POST["grupo"]);
            $nombre=unserialize($_POST["nombre"]);
            //$apellido=$_POST["apellido"];
            $correo=unserialize($_POST["correo"]);
            //$aula=$_POST["aula"];
            $mensaje=$_POST["mensaje"];
            //$hora_inicio=$_POST["hora_inicio"];
            //$hora_fin=$_POST["hora_fin"];

            for($i=0; $i<6; $i++){
                echo " grupo : $grupo[$i]  tamnaño del arreglo :" . count($grupo) . "<br>";
                echo "el nombre del docente es : $nombre[$i] su tamaño del arreglo " . count($nombre) . "<br>";
                echo " el correo del docente es : $correo[$i] su tamaño del arreglo " . count($correo) . "<br>";
            }

            $db_host="localhost";
            $db_nombre="asignacionaulas";
            $db_usuario="root";
            $db_contrasenia="";
            $conexion=mysqli_connect($db_host,$db_usuario,$db_contrasenia,$db_nombre);

            if(mysqli_connect_errno()){
                  echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
                  exit();
            }

            mysqli_set_charset($conexion, "utf8");

            $consulta_auxiliar = "SELECT * FROM auxiliar";

            $resultado_consulta = mysqli_query($conexion, $consulta_auxiliar);

            $aulasss=" - ";
            while(($fila = mysqli_fetch_row($resultado_consulta)) == true){
                  $id_aulaa=(int)$fila[0];
                  $aulasss=$aulasss . $fila[0];
                  $db_host2="localhost";
                  $db_nombre2="asignacionaulas";
                  $db_usuario2="root";
                  $db_contrasenia2="";

                  if(mysqli_connect_errno()){
                        echo '<script language="javascript">alert("fallo al conectar con la base de datos");</script>';
                        exit();
                  }
                  mysqli_set_charset($conexion, "utf8");

                  $conexion2=mysqli_connect($db_host2,$db_usuario2,$db_contrasenia2,$db_nombre2);
                  //gh
                  $guardar="INSERT INTO reservas_atendidas (id_reserva, estado, detalle, id_aula) VALUES ('$id_reserva', 'aceptado', '$mensaje', '$id_aulaa')"; 
                  $result=mysqli_query($conexion2,$guardar);

                  /*if($result == true){
                        echo "<form action='macanas.php' method='post'><input type='hidden' name='entro' value='entro'><input type='submit' name='prueba' value='prueba'></form>";
                  }else{
                        echo "no entre 1) $fila[0], el otro $id_aulaa 2 ) el id reserva es $id_reserva<br>";
                  }*/
                  mysqli_close($conexion2);
            }
            $query = mysqli_query($conexion, "SELECT * FROM auxiliar, aulas WHERE aulas.id_aula = auxiliar.id_aula");
            $result = mysqli_num_rows($query);
            $aula=array();
            if($result > 0) {
                  while($data = mysqli_fetch_array($query)) {    
                      array_push($aula, $data["codigo_aula"]);
                  }
            }
        $aulas = implode(", ", $aula);
      
            $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gerfsoftware.srl@gmail.com';
            $mail->Password = 'ozkerkuevtskural';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
    
            $mail->setFrom('gerfsoftware.srl@gmail.com', 'UMSS FCyT');
            $mail->addCC($correo);
            $mail->addAddress($correo, $nombre);
    
            $mail->isHTML(true);
            $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
            $mail->Body = '<h4>'. $nombre . '</h4><p>Se aprobó su solicitud de aula para la toma de examen.<br> Aulas: ' . $aulas . '<br>Fecha que se realizó la solicitud: ' . $fecha_solicitud .'<br>Cantidad de estudiantes: ' . $cantidad_estudiantes .'Mensaje: ' . $mensaje .'</p>';
            $mail->send();
        } catch(Exception $e) {
            echo '<script language="javascript">alert("error");</script>';
        }

            $borrar_auxiliar = "DELETE FROM auxiliar";
            $borrar = mysqli_query($conexion, $borrar_auxiliar);

            mysqli_close($conexion);
            //echo '<script language="javascript">alert("asignacion realizada exitosamente'. $correo .'");</script>';
           //echo "<script language='javascript'>window.location.replace('http://localhost/pruebasTis/AsignacionDeAulas/vista/vistaDetPend.php')</script>";
            
      }else if(isset($_POST["Cancelado"])){
            mysqli_close($conexion);
            echo "<a href='vistaDetPend.php'><script language='javascript'>alert('usted cancelo la asignacion');</script></a>";
      }

?>
