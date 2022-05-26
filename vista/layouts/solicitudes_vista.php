<!DOCTYPE html>

<?php
include('layouts/header.php');
include('layouts/navegacion.php');
?>

<section>
    <div class="row text-center">
        <div class="col-lg-12">
            <h2>Solicitud de Reserva de Aula # 1</h2>
        </div>
        <div class="col-lg-12">
            <h2>Fecha: <?php echo date('y-m-d') ?></h2>
        </div>
    </div>
</section>
<div class="container">
    <div class="solicitudReserva">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-4">
                    <label for="nombre_docente">Docente:</label>
                    <div class="form-group">
                        <input name="nombre_docente" type="text" class="form-control" id="nombre_docente" required pattern="[A-Za-z, ]{4,40}" title="Letras, Tamaño mínimo: 4. Tamaño máximo: 40">
                    </div>
                    <label for="id_materia">Materia:</label>
                    <div class="form-group">
                        <select class="form-control" name="id_materia" id="id_materia">
                            <option value="">Seleccionar materia...</option>
                            <?php
                            foreach ($materias as $materia) {
                            ?>
                                <option value="<?php echo $materia['id_materia'] ?>"><?php echo $materia['nombre_materia'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button id="btnNuevo" type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">NUEVO ITEMSS</button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title col-11 text-center" id="exampleModalLabel">Nuevo item</h5>
                                    <button type="button" class="close col-1" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="fecha" class="col-form-label">Fecha:</label>
                                            <input type="text" class="form-control" id="fecha">
                                        </div>
                                        <div class="form-group">
                                            <label for="materia" class="col-form-label">Materia:</label>
                                            <input type="text" class="form-control" id="materia">
                                        </div>
                                        <div class="form-group">
                                            <label for="docente" class="col-form-label">Docente:</label>
                                            <input type="text" class="form-control" id="docente">
                                        </div>
                                        <div class="form-group">
                                            <label for="periodos" class="col-form-label">Periodos:</label>
                                            <input type="text" class="form-control" id="periodos">
                                        </div>
                                        <div class="form-group">
                                            <label for="detalle" class="col-form-label">Detalle:</label>
                                            <textarea class="form-control" id="detalle"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="nroEstudiantes" class="col-form-label">Capacidad de estudiantes:</label>
                                            <input type="text" class="form-control" id="nroEstudiantes">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    <button type="button" class="btn btn-primary">Insertar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Nro</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Grupo</th>
                            <th scope="col">Periodos</th>
                            <th scope="col">Capacidad Estudiantes</th>
                            <th scope="col">Detalle</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>27/04/22</td>
                            <td>G1</td>
                            <td>8:15-9:45</td>
                            <td>50</td>
                            <td>examen primer parcial</td>
                            <td><input type="button" name='eliminar' value='eliminar' id="borrar"></td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>27/05/22</td>
                            <td>G1</td>
                            <td>8:15-9:45</td>
                            <td>50</td>
                            <td>examen segundo parcial</td>
                            <td><input type="button" name='eliminar' value='eliminar' id="borrar"></td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>07/06/22</td>
                            <td>G1</td>
                            <td>8:15-9:45</td>
                            <td>50</td>
                            <td>examen final</td>
                            <td><input type="button" name='eliminar' value='eliminar' id="borrar"></td>
                        </tr>
                        <tr>
                            <th scope="row">1</th>
                            <td>17/06/22</td>
                            <td>G1</td>
                            <td>8:15-9:45</td>
                            <td>50</td>
                            <td>examen instancia</td>
                            <td><input type="button" name='eliminar' value='eliminar' id="borrar"></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-secondary btn-lg btn-block" id="botonEnviarGuardar">ENVIAR Y GUARDAR</button>
                
                <div class="col-lg-12" style="text-align: center;">
                
                </div>
            </div>
        </div>
    </div>
</div>
</html>