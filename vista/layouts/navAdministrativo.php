<?php
include('../template/cabecera.php');
?>
<header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Sistema de asignación de aulas</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/vista/vistaDetPend.php">Pendientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/vista/vistaDetRevi.php">Revisadas</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <p class="dropdown-item"><b><?php echo $_SESSION['nombre_docente'] ?></b></a>
                            <div class="dropdown-divider"></div>
                            <?php if ($_SESSION["is_admin"] == 1) { ?>
                                <p class="dropdown-item"><i></small>Administrativo de la Facultad de Ciencias y Tecnologia</small></i></p>
                            <?php } else { ?>
                                <p class="dropdown-item"><i><small>Docente de la Facultad de Ciencias y Tecnologia</small></i></p>
                            <?php } ?>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../vista/homeDocente.php">Cambiar a Docente</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../vista/destroy.php">Cerrar Sesion</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>