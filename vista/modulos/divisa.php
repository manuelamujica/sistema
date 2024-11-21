<?php require_once 'controlador/divisa.php'; ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
        <h1>Gestión de Divisas</h1>
        </div><!-- /.col -->
    </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarDivisa">
                            Registrar Divisa
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="paymentTypesTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Símbolo/Abreviatura</th>
                                        <th>Tasa</th>
                                        <th>Última actualización</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($consulta as $divisa) { ?>
                                    <?php if ($divisa['divisa_status'] != 2): ?>
                                    <tr>
                                        <td><?php echo $divisa['cod_divisa']?></td>
                                        <td><?php echo $divisa['nombre']?></td>
                                        <td><?php echo $divisa['abreviatura'] ?></td>
                                        <td><?php echo $divisa['tasa']."  Bs" ?></td>
                                        <td><?php echo $divisa['fecha'] ?></td>
                                        <td>
                                            <?php if ($divisa['divisa_status']==1):?>
                                                <span class="badge bg-success">Activo</span>
                                            <?php else:?>
                                                <span class="badge bg-danger">Inactivo</span>
                                            <?php endif;?>
                                        </td>
                                        <td>
                                        <?php if($divisa['cod_divisa']==1): ?>

                                        <?php else:?>
                                            <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal" 
                                            data-codigo="<?php echo $divisa["cod_divisa"]; ?>" 
                                            data-nombre="<?php echo $divisa["nombre"]; ?>" 
                                            data-abreviatura="<?php echo $divisa["abreviatura"]; ?>"
                                            data-tasa="<?php echo $divisa["tasa"]; ?>"
                                            data-status="<?php echo $divisa["divisa_status"]; ?>" >
                                            <i class="fas fa-pencil-alt"></i>
                                            </button>
                                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminardivisa"
                                            data-codigo="<?php echo $divisa["cod_divisa"]; ?>" 
                                            data-nombre="<?php echo $divisa["nombre"]; ?>" >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif;?>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
    
    <!-- registrar Divisa Modal -->
<div class="modal fade" id="modalregistrarDivisa">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
        <h4 class="modal-title">Registrar Divisa</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <form role="form" method="post">
        <div class="modal-body">
            <div class="form-group">
                <label for="nombre">Nombre de la Divisa<span class="text-danger" style="font-size: 15px;"> *</span></label>
                <input type="text" class="form-control" id="nombre" placeholder="Ej: Dólares" name="nombre" required>
                <div class="invalid-feedback" style="display: none;"></div>
            </div>
            <div class="form-group">
                <label for="simbolo">Símbolo o Abreviatura<span class="text-danger" style="font-size: 15px;"> *</span></label>
                <!-- TOOLTIPS-->
                <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Selecciona el simbolo abreviatura de la divisa, por ejemplo: USD ó $">
                    <i class="fas fa-info-circle"></i>
                </button>
                <script>
                    $(function () {
                        $('[data-toggle="tooltip"]').tooltip();
                    });
                </script>
                <input type="text" class="form-control" id="simbolo" name="simbolo" placeholder="Ej: USD ó $" required>
                <div class="invalid-feedback" style="display: none;"></div>
            </div>
            <div class="form-group row">
                <div class="col-6">
                    <label for="tasa">Tasa de la Divisa<span class="text-danger" style="font-size: 15px;"> *</span></label>
                    <div class="input-group">
                        <input type="number" step="0.01" class="form-control" min="0" id="tasa" name="tasa" placeholder="Tasa en Bolívares" required>
                        <div class="invalid-feedback" style="display: none; position: absolute; top: 100%; margin-top: 2px; width: calc(100% - 2px); font-size: 0.875em; text-align: left;"></div>
                        <div class="input-group-append">
                            <span class="input-group-text">Bs</span>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <label for="fecha">Fecha<span class="text-danger" style="font-size: 15px;"> *</span></label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
            </div>
            <br>
            <!-- Alert Message -->
            <div class="alert alert-light d-flex align-items-center" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span>Todos los campos marcados con (*) son obligatorios</span>
            </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary" name="registrar">Guardar</button>
        </div>
        </form>
    </div>
    <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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
                window.location = 'divisa';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL EDITAR DIVISA
============================= -->

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Editar Información</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post">
                    <div class="form-group">
                        <label for="codigo">Código</label>
                        <input type="text" class="form-control" id="codigo" name="codigo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nombre">Nombre de la Divisa</label>
                        <input type="text" class="form-control" id="nombre1" name="nombre" required>
                        <div class="invalid-feedback" style="display: none;"></div>
                        <input type="hidden" id="origin" name="origin"> 
                        
                    </div>
                    <div class="form-group">
                        <label for="abreviatura">Símbolo o Abreviatura</label>
                        <input type="text" class="form-control" id="abreviatura" name="abreviatura" required>
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-md-7">
                            <label for="tasa">Tasa de la Divisa</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="tasa1" name="tasa" required>
                                <div class="invalid-feedback" style="display: none; position: absolute; top: 100%; margin-top: 2px; width: calc(100% - 2px); font-size: 0.875em; text-align: left;"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="editForm" class="btn btn-primary" name="actualizar">Guardar cambios</button>
            </div>
        </div>
    </div>
</div>
<?php 
if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'divisa';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->

<div class="modal fade" id="eliminardivisa" tabindex="-1" aria-labelledby="eliminardivisaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="eliminardivisaLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="elimodal" method="post"> 
                <p>¿Está seguro que desea eliminar la divisa: <b><span id="divisaNombre"></b></span>?</p>
                <input type="hidden" id="divisaCodigo" name="divisaCodigo"> 
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" form="elimodal" class="btn btn-danger" id="confirmDelete" name="borrar">Eliminar</button>
        </div>
        </div>
    </div>
</div>
<?php if (isset($eliminar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $eliminar["title"]; ?>',
            text: '<?php echo $eliminar["message"]; ?>',
            icon: '<?php echo $eliminar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'divisa';
            }
        });
    </script>
<?php endif; ?>

<script src='vista/dist/js/modulos-js/divisa.js'></script>