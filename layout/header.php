<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title><?php if (isset($title)) {
            echo $title;
          } ?></title>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="http://asignaciondeaulas/vista/css/login.css">
  <script src="http://asignaciondeaulas/vista/js/jquery-3.4.1.js"></script>
  <link rel="stylesheet" href="http://asignaciondeaulas/vista/DataTables/datatables.min.css">
  <link rel="stylesheet" href="http://asignaciondeaulas/vista/DataTables/datatables.css">
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  

  <script src="http://asignaciondeaulas/vista/sweet/dist/sweetalert2.all.min.js"></script>
  <link rel="stylesheet" href="http://asignaciondeaulas/vista/sweet/dist/sweetalert2.min.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Sistema de asignación de aulas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        if ($user->is_logged_in()) { ?>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link" href="/vista/vistaDetPend.php">Pendientes</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="/vista/vistaDetRevi.php">Revisadas</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/vista/homeDocente.php">Home docente</a>
              </li>
              <li class="nav-item"><a class="nav-link individual" href="../vista/solicitud.php">Individual</a></li>
              <li class="nav-item"><a class="nav-link compartida" href="../vista/solicitudCompartida.php">Compartida</a></li>
              <li class="nav-item"><a class="nav-link Segimiento" href="../vista/seguimienDocente.php">Seguimiento</a></li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 profile-menu">
              <div class="dropdown" style="margin-right: 9rem;">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="fas fa-user"></i> <?php echo $_SESSION['usuario'] ? $_SESSION['usuario'] : '' ?>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li><a class="dropdown-item" href="#"> <?php if ($_SESSION["is_admin"] == 1) { ?>
                        <span class="dropdown-item"><i></small>Administrativo de la Facultad <br> de Ciencias y Tecnologia</small></i></span>
                      <?php } else { ?>
                        <span class="dropdown-item"><i><small>Docente de la Facultad <br> de Ciencias y Tecnologia</small></i></span>
                      <?php } ?></a></li>
                  <li><a class="dropdown-item" href="../vista/homeDocente.php">Cambiar a Docent</a></li>
                  <li><a class="dropdown-item" href="../vista/destroy.php">Cerrar Sesion</a></li>
                </ul>
              </div>

            </ul>
          </div>
        <?php } ?>
      </div>
    </nav>
  </header>