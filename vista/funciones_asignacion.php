<?php
      ini_set("pcre.jit", "0");
      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\SMTP;
      use PHPMailer\PHPMailer\Exception;
    
      require 'librerias/phpmailer/src/PHPMailer.php';
      require 'librerias/phpmailer/src/SMTP.php';
      require 'librerias/phpmailer/src/Exception.php';

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

      if(isset($_POST["Enviar"])){
            $id_solicitud=$_POST["id_solicitud"];
            $id_reserva=(int)$_POST['id_reserva2'];
            $fecha_solicitud=$_POST["fecha_solicitud"];
            $fecha_reserva=$_POST["fecha_reserva"];
            $cantidad_estudiantes=$_POST["cantidad_estudiantes"];
            $grupo=unserialize($_POST["grupo"]);
            $nombre=unserialize($_POST["nombre"]);
            $correo=unserialize($_POST["correo"]);
            $IDsDocente=unserialize($_POST["idCorrecto"]);
            $grupo_ordenado=unserialize($_POST["ordenado"]);
            $mensaje=$_POST["mensaje"];


            $consulta_auxiliar = "SELECT * FROM auxiliar";
            $resultado_consulta = mysqli_query($conexion, $consulta_auxiliar);

            while(($fila = mysqli_fetch_row($resultado_consulta)) == true){
                  $id_aulaa=(int)$fila[0];
                  $guardar="INSERT INTO reservas_atendidas (id_reserva, estado, detalle, id_aula) VALUES ('$id_reserva', 'aceptado', '$mensaje', '$id_aulaa')"; 
                  $result=mysqli_query($conexion,$guardar);
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
            $materiasql = "SELECT nombre_materia FROM reserva, materias WHERE reserva.id_reserva = $id_reserva AND reserva.id_materia = materias.id_materia";
            $nombreMateria = mysqli_query($conexion, $materiasql);
            $materia = mysqli_fetch_array($nombreMateria);

            $detallesql = "SELECT detalle FROM reserva WHERE reserva.id_reserva = $id_reserva";
            $mostrarDetalle = mysqli_query($conexion, $detallesql);
            $detalle = mysqli_fetch_array($mostrarDetalle);

            $horaIniciosql = "SELECT hora_inicio FROM reserva WHERE reserva.id_reserva = $id_reserva";
            $inicio = mysqli_query($conexion, $horaIniciosql);
            $horaInicio = mysqli_fetch_array($inicio);

            $horaFinsql = "SELECT hora_fin FROM reserva WHERE reserva.id_reserva = $id_reserva";
            $fin = mysqli_query($conexion, $horaFinsql);
            $horaFin = mysqli_fetch_array($fin);
            
            if(count($grupo)>1){
                  for($i=0; $i<count($grupo); $i++){
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
                              $mail->addCC($correo[$i]);
                              $mail->addAddress(trim($correo[$i]), trim($nombre[$i]));
                      
                              $mail->isHTML(true);
                              $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
                              $mail->Body = '<h4>'. $nombre[$i] . '</h4>
                                                <p>
                                                      <h3>Se aprobó su solicitud de aula para la toma de examen.</h3><br> 
                                                      Aula: ' . $aulas . '<br>
                                                      Mensaje: ' . $mensaje . '<br>
                                                      Materia: ' . $materia[0] . '<br>
                                                      Fecha de reserva: ' . $fecha_reserva . '<br
                                                      Hora Inicio: ' . $horaInicio[0] . '<br>
                                                      Hora Fin: ' . $horaFin[0] . '<br>
                                                      Cantidad de estudiantes: ' . $cantidad_estudiantes .'<br>
                                                      Detalle: ' . $detalle [0] .
                                                '</p>';
                              $mail->send();
                          } catch(Exception $e) {
                              //echo '<script language="javascript">alert("Error");</script>';
                          }
                  }
            }else{
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
                        $mail->addCC($correo[0]);
                        $mail->addAddress($correo[0], $nombre[0]);
                
                        $mail->isHTML(true);
                        $mail->Subject = 'Respuesta a solicitud de asignacion de aula.';
                        $mail->Body = '<h4>'. $nombre[0] . '</h4>
                                          <p>
                                                <h3>Se aprobó su solicitud de aula para la toma de examen.</h3><br> 
                                                Aula: ' . $aulas . '<br>
                                                Mensaje: ' . $mensaje . '<br
                                                Materia: ' . $materia[0] . '<br
                                                Fecha de reserva: ' . $fecha_reserva . '<br
                                                Hora Inicio: ' . $horaInicio[0] . '<br
                                                Hora Fin: ' . $horaFin[0] . '<br
                                                Cantidad de estudiantes: ' . $cantidad_estudiantes .'<br
                                                Detalle: ' . $detalle[0] .
                                          '</p>';
                        $mail->send();
                    } catch(Exception $e) {
                       // echo '<script language="javascript">alert("Error);</script>';
                    }
            }
            

            $borrar_auxiliar = "DELETE FROM auxiliar";
            $borrar = mysqli_query($conexion, $borrar_auxiliar);

            mysqli_close($conexion);
      echo '<script language="javascript">alert("Asignacion realizada exitosamente. Correo enviado exitosamente.");</script>';
      echo "<script language='javascript'>window.location.replace('http://localhost/AsignacionDeAulas-1/vista/vistaDetPend.php')</script>";
            
      }else if(isset($_POST["Cancelado"])){
            mysqli_close($conexion);
            echo "<a href='vistaDetPend.php'><script language='javascript'>alert('usted cancelo la asignacion');</script></a>";
      }

?>
