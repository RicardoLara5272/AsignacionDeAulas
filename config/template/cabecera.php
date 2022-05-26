<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitio web</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />

</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#">Nav 1</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Nav 2</a>
            </li>
            <li class_="nav-item">
            <div class="form-group">
                <label for=""></label>
                <select class="custom-select" name="" onchange="url(this.value)" id="">
                    <option value = "hide" style = 'display: none' selected>Mostrar</option>
                    <option value="../vista/vistaDetRevi.php">Revisadas</option>
                    <!--<option value="vistaDetRech.php">Rechazadas</option> -->
                    <option value="../vista/vistaDetPend.php">Pendientes</option>
                </select>
            </div>
            </li>
        
        </ul>
    </nav>

    <div class="container">
    <br/>
        <div class="row">