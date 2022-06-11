<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="css/estiloSolicitud.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap" rel="stylesheet">
    
    <title>Asignaciones</title>
</head>
<body>
    <?php
    include_once("./layouts/navSolicitudes.php");
    include("../config/db.php"); 

    $sentenciaSQL= $conexion->prepare(" SELECT * FROM solicitudes WHERE id_docente='1' ");
    $sentenciaSQL->execute();
    $listaSolicitudes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);
    ?>
<div class="container">
    <div class="Seguimiento">
        <section>
            <div class="row text-center">
                <div class="col-lg-12">
                    <br>
                    <h2>Seguimento de solicitudes</h2>
                    <br><br>
                </div>
            </div>
        </section> 
<!-- comienzo de tabla-->
        <div class="row justify-content-center">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha Solicitud</th>
                            <th>Estado</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody action="vistaDetRese.php" method="post">
                    <?php foreach($listaSolicitudes as $solicitud) {?>
                        <tr>
                            <td> <?php echo $solicitud['id_solicitudes']; ?> </td>
                            <td> <?php echo $solicitud['fecha_solicitud']; ?></td>
                            <td> <?php echo $solicitud['estado']; ?></td>
                            <td> 
                                <form action="DetalleReserDocent.php" method="post" name="formulario">
                                    <input type="hidden" name="id_solicitud" value=" <?php echo $solicitud['id_solicitudes']; ?> ">
                                    <input type="submit" class="btn btn-success botton" value="Detalles" >
                                </form> 
                            </td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table> <!-- final de tabla-->
            </div>
        </div>
    </div>
</div>

    <div class="navfooter">
        <footer>
            <ul class="social_icon">
                <li><a href="#">
                        <ion-icon name="logo-facebook"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-twitter"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-linkedin"></ion-icon>
                    </a></li>
                <li><a href="#">
                        <ion-icon name="logo-instagram"></ion-icon>
                    </a></li>
            </ul>
            <p>@2022 Gerf Software S.R.L | Contactos: (+591) 70791322</p>
        </footer>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</body>
</html>