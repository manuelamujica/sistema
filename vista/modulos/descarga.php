<?php 
require_once "controlador/descarga.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Descarga de productos</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <!-- Botón para ventana modal -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarDescarga">Registrar descarga</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="descarga" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descripcion</th>
                                            <th>Producto</th>
                                            <th>Fecha</th>
                                            <th>Cantidad descargada</th>
                                            <th>Status</th>
                                        </tr>         
                                    </thead>
                                    <tbody>
                                    <!-- Tabla con los datos que se muestren dinamicamente -->
                                        <?php
                                        foreach ($descarga as $d){
                                            ?>
                                            <tr>
                                                <td> <?php echo $d["cod_descarga"] ?></td>
                                                <td> <?php echo $d["descripcion"] ?></td>
                                                <td> <?php echo $d["nombre_producto"] ?> </td>
                                                <td> <?php echo $d["fecha"] ?></td>
                                                <td> <?php echo $d["cantidad"] ?></td>
                                                <td>
                                                    <?php if ($d['status'] == 1):?>
                                                        <span class="badge bg-success">Activo</span>
                                                    <?php else:?>
                                                        <span class="badge bg-danger">Inactivo</span>
                                                    <?php endif;?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
<!-- =============================
    MODAL REGISTRAR DESCARGA 
================================== -->
                <div class="modal fade" id="modalRegistrarDescarga" tabindex="-1" aria-labelledby="modalRegistrarDescargaLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registrarModalLabel">Registrar descarga</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formRegistrarDescarga" method="post">
                                        <div class="form-group">
                                            <label for="fecha">Fecha</label>
                                            <input type="datetime-local" class="form-control" id="fecha" name="fecha" required>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion">Descripcion</label>
                                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                                            <div class="invalid-feedback" style="display: none;"></div>
                                        </div>
                                        <!--SELECCIONAR PRODUCTOS (+ agregar productos)-->
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Codigo</th>
                                                        <th>Producto</th>
                                                        <th>Presentacion</th>
                                                        <th>Lote</th>
                                                        <th>Stock disponible</th>
                                                        <th>Cantidad</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="detdescargabody">
                                                <!--Se generan dinamicamente las filas de productos-->
                                                </tbody>
                                            </table>

                                            <!--Agregar otro producto-->
                                            <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar otro producto</button>
                                        </div>

                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>



<?php 
if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'descarga';
            }
        });
    </script>
<?php endif; ?>

            </div>
        </div>
    </section>
</div>

<script src='vista/dist/js/modulos-js/descarga.js'></script>



