<header>
    <div class="pull-left" style="width: 50%;">
        <h1>Sistema de asignación de aulas</h1>
    </div>
    <div class="pull-right" style="width: 50%;">
        <ul class="nav nav-tabs nav-pills" style="float:right;">
            <li class="nav-item dropdown active">
                <div class="btn-group dropleft">
                    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <ion-icon name="person-circle-outline" style="color: #fff; font-size: 30px; float: right;"></ion-icon>
                    </a>
                    <div class="dropdown-menu">
                        <p class="dropdown-item"><b><?php echo $_SESSION['nombre_docente'] ?></b></a>
                        <div class="dropdown-divider"></div>
                        <?php if ($_SESSION["is_admin"] >= 1) { ?>
                            <p class="dropdown-item"><i><small>Administrativo de la Facultad de Ciencias y Tecnologia</small></i></a>
                            <?php } else { ?>
                            <p class="dropdown-item"><i><small>Docente de la Facultad de Ciencias y Tecnologia</small></i></a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <?php if ($_SESSION["is_admin"] >= 1) { ?>
                                <a class="dropdown-item" href="../vista/homeAdministrativo.php">Cambiar a Administrativo</a>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../vista/destroy.php">Cerrar Sesion</a>
                    </div>
                </div>
            </li>

            <li>Otros</li>
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
                    </li>
                    <li><a class="Segimiento" href="../vista/seguimienDocente.php">Seguimiento</a></li>
                </ul>
            </li>
        </ul>
    </section>
</nav>