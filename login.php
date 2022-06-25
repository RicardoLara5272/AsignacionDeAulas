<?php
//Declaramos la conexion con el servidor de base de datos
require_once('config/config.php');
//Si no existe la sesion, redirigir al index
if ($user->is_logged_in()) {
	header('Location: menberpage.php');
	exit();
}

//Verifica el formulario
if (isset($_POST['submit'])) {

	if (!isset($_POST['username'])) $error[] = "Por favor rellene el usuario";
	if (!isset($_POST['password'])) $error[] = "Por favor rellene la contraseña";
	$usuario = $_POST['username'];
	if ($user->isValidUsername($usuario)) {
		if (!isset($_POST['password'])) {
			$error[] = 'Se debe ingresar una contraseña';
		}
		$password = $_POST['password'];
		if ($user->login($usuario, $password)) {
			$_SESSION['usuario'] = $usuario;
			header('Location: memberpage.php');
			exit;
		} else {
			$error[] = 'Nombre de usuario o contraseña incorrectos o su cuenta no ha sido activada.';
		}
	} else {
		$error[] = 'Los nombres de usuario deben ser alfanuméricos y tener entre 3 y 16 caracteres de longitud.';
	}
} //end if submit

//define page title
$title = 'Login';

//include header template
require('layout/header.php');
?>
<main class="content">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
				<form role="form" method="post" action="" autocomplete="off">
					<h2>Por favor Iniciar sesion</h2>
					<p><a href='./'>Retornar al inicio</a></p>
					<hr>

					<?php
					//check for any errors
					if (isset($error)) {
						foreach ($error as $error) {
							echo '<p class="bg-danger">' . $error . '</p>';
						}
					}

					if (isset($_GET['action'])) {

						//check the action
						switch ($_GET['action']) {
							case 'active':
								echo "<h2 class='bg-success'>Su cuenta ya está activa, ahora puede iniciar sesión.</h2>";
								break;
							case 'reset':
								echo "<h2 class='bg-success'>Por favor revise su bandeja de entrada para un enlace de restablecimiento.</h2>";
								break;
							case 'resetAccount':
								echo "<h2 class='bg-success'>Contraseña cambiada, ahora puede iniciar sesión.</h2>";
								break;
						}
					}


					?>

					<div class="form-group">
						<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Usuario" value="<?php if (isset($error)) {
																																		echo htmlspecialchars($_POST['username'], ENT_QUOTES);
																																	} ?>" tabindex="1">
					</div>

					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
					</div>

					<div class="row">
						<div class="col-xs-9 col-sm-9 col-md-9">
							<a href='reset.php'>Recuperar contraseña?</a>
						</div>
					</div>

					<hr>
					<div class="row">
						<div class="col-xs-6 col-md-6"><input type="submit" name="submit" value="Iniciar Sesion" class="btn btn-primary btn-block btn-lg" tabindex="5"></div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-9 col-sm-9 col-md-9">
							<a href='register.php'>No tengo cuenta</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</main>
<?php
//include header template
require('layout/footer.php');
?>