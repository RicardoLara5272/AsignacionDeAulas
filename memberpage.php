<?php require('config/config.php'); 

//if not logged in redirect to login page
if(!$user->is_logged_in()){ header('Location: login.php'); exit(); }

//define page title
$title = 'Docentes Page';

//include header template
require('layout/header.php'); 
?>
<main style="background-image: url('../computer.jpg');background-position: center;background-repeat: no-repeat;background-size: cover;height: 100vh;    opacity: 0.4;">
	<div class="container">
	
		<div class="row">
	
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				
					<h2>Bienvenido <?php echo htmlspecialchars($_SESSION['usuario'], ENT_QUOTES); ?></h2>
					<?php if ($_SESSION["is_admin"] == 1) { ?>
                        <span class="dropdown-item"><i></small>Administrativo de la Facultad <br> de Ciencias y Tecnologia</small></i></span>
                      <?php } else { ?>
                        <span class="dropdown-item"><i><small>Docente de la Facultad <br> de Ciencias y Tecnologia</small></i></span>
                      <?php } ?>
					<p><a href='logout.php'><i class="fa fa-sign-out"></i> Salir</a></p>
					<hr>
	
			</div>
		</div>
	
	
	</div>
</main>

<?php 
//include header template
require('layout/footer.php'); 
?>
