<?php

if (!empty($_COOKIE['PHPSESSID']))  {
    unset($_COOKIE['PHPSESSID']);
}
if ($_COOKIE) {
    if (isset($_COOKIE[$_SESSION["usuario"]])) {
        unset( $_COOKIE[$_SESSION["usuario"]]);
    }
}
session_id();
session_start();
session_unset();
setcookie(session_name(),0,1,ini_get("session.cookie_path"));
session_destroy();

header('location: ../index.php');
?>
