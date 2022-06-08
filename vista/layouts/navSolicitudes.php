<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaciones</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap" rel="stylesheet">

</head>

<body>
    <header>
        <div class="pull-left" style="width: 50%;">
            <h1>Sistema de asignación de aulas</h1>
        </div>
        <div class="pull-right" style= "width: 50%;">
            <ul class="nav nav-tabs nav-pills" style="float:right;">
                <li class="nav-item dropdown active">
                    <div class="btn-group dropleft">
                        <a class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><ion-icon name="person-circle-outline" style="color: #fff; font-size: 30px; float: right;"></ion-icon></a>
                    <div class="dropdown-menu">
                        <p class="dropdown-item"><b><?php echo $_SESSION['nombre_docente'] ?></b></a>
                        <div class="dropdown-divider"></div>
                        <?php if ($_SESSION["is_admin"] == 1) { ?>
                            <p class="dropdown-item"><i><small>Administrativo de la Facultad de Ciencias y Tecnologia</small></i></a>
                        <?php } 
                        else {?>
                            <p class="dropdown-item"><i><small>Docente de la Facultad de Ciencias y Tecnologia</small></i></a>
                        <?php }?>
                        <div class="dropdown-divider"></div>
                        <?php if ($_SESSION["is_admin"] == 1) { ?>
                        <a class="dropdown-item" href="../vista/homeAdministrativo.php">Cambiar a Administrativo</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../vista/destroy.php">Cerrar Sesion</a>
                    </div>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <nav>
        <section id="menu-nav">
            <ul class="menu">
                <li class="reserva">Reservas
                    <ul class="ul-second">
                        <li><a class="individual" href="../vista/solicitud.php">Individual</a></li>
                        <li><a class="compartida" href="../vista/solicitudesCompartidas.php">Compartida</a>
                            <!--<ul class="ul-third">
                                <li>semestre-1</li>
                                <li>semestre-2</li>
                            </ul>-->
                        </li>
                    </ul>
                </li>
                <li>Seguimientos</li>
                <li>Otros</li>
            </ul>
        </section>
    </nav>
</body>

</html>