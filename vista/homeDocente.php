<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');
if (!$user->is_logged_in()) {
    header('Location: login.php');
    exit();
}
//define page title
$title = 'Asignaciones';
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/header.php');
$_POST["fecha"] = date("Y-m-d");
?>
<main class="content" style="background-image: url('../img.jpg');background-position: center;background-repeat: no-repeat;background-size: cover;height: 100vh;">
    <div class="container">
        <div class="row justify-content-center align-items-center">
            <div class="col-12">
                <!-- <img src="../img.jpg" alt="" style="object-fit: cover;" srcset=""> -->
            </div>
        </div>
    </div>
</main>

<?php
//include header template
require($_SERVER['DOCUMENT_ROOT'] . '/layout/footer.php');
?>