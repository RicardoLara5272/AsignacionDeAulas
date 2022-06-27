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
		<?php
		//check for any errors

		if (isset($_GET['action'])) {

			//check the action
			switch ($_GET['action']) {
				case 'active':
					echo "<span class=alert alert-success'>Su cuenta ya está activa, ahora puede iniciar sesión.</span> <br>";
					break;
				case 'reset':
					echo "<span class='alert alert-success'>Por favor revise su bandeja de entrada para un enlace de restablecimiento.</span><br>";
					break;
				case 'resetAccount':
					echo "<span class='alert alert-success'>Contraseña cambiada, ahora puede iniciar sesión.</span><br>";
					break;
			}
		}


		?>
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

				<div class="user-card">
					<div class="login-box">
						<div class="login-form">
							<form role="form" method="post" action="" autocomplete="off">
								<div class="text-center">
									<h5>Por favor Iniciar sesion</h5>
								</div>
								<p><a href='./' style=" text-decoration: none;">Ir a <i class="fa fa-home"></i></a></p>



								<div class="form-group mt-5">
									<label for="username">Usuario</label>
									<span class="icon-user"><i class="fa-solid fa-user"></i></span>
									<input type="text" name="username" id="username" class="form-control input-lg" placeholder="Usuario" value="<?php if (isset($error)) {
																																				} ?>" tabindex="1">
								</div>

								<div class="form-group">
									<label for="password">Password</label>
									<span class="icon-eye"><i class="fa-solid fa-eye-slash"></i></span>
									<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" tabindex="3">
								</div>

								<div class="row justify-content-center">
									<div class="col-xs-9 col-sm-9 col-md-9  text-center">
										<a href='reset.php'>Recuperar contraseña?</a>
									</div>
								</div>
								<div class="row justify-content-center">
									<div class="col-xs-8 col-md-8"><input style="text-transform: uppercase;padding: inherit;" type="submit" name="submit" value="Iniciar Sesion" class="btn btn-primary btn-block btn-lg" tabindex="5" style="padding: inherit;"></div>
								</div>
								<div class="row justify-content-center mb-4">
									<div class="col-xs-9 col-sm-9 col-md-9 text-center">
										<a href='register.php'>No tengo cuenta</a>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

			</div>
			<?php
			//check for any errors
			if (isset($error)) {
				$mes = '';
				foreach ($error as $errore) {
					$mes = $mes . $errore;
				}
				echo '<span class="alert alert-danger">' . $mes . '</span> <br>';
			}
			?>
		</div>
	</div>
</main>
<?php
//include header template
require('layout/footer.php');
?>