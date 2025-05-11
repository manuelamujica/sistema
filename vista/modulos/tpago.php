<?php require_once 'controlador/tpago.php'; ?>

<div class="content-wrapper">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Gestión de Tipos de Pago</h1>
                <p>Configura los tipos de pago utilizados en la empresa.</p>
            </div>
        </div>
    </div>
</div>

        <!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addPaymentTypeModal">
                            Registrar Tipo de Pago
                        </button>
                    </div>
                    <div class="card-body">
                    <div class="table-responsive">
                        <table id="paymentTypesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Metodo de pago</th>
                                    <th>Descripcion</th>
                                    <th>Divisa</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($registro as $dato) { ?>
                                <?php if ($dato['status_tipo_pago'] != 2): ?>
                                <tr>
                                    <td><?php echo $dato['cod_tipo_pago']?></td>
                                    <td><?php echo $dato['medio_pago']?></td>
                                    <td><?php 
                                            if(isset($dato['cod_cuenta_bancaria'])){
                                                echo $dato['nombre_banco'] . " - " . $dato['numero_cuenta'] . " - " . $dato['tipo_cuenta'];
                                            }else{
                                                echo $dato['nombre_caja'];
                                            }
                                    ?></td>
                                    <td><?php 
                                            if(isset($dato['cod_cuenta_bancaria'])){
                                                echo $dato['nombre_divisa_cuenta'];
                                            }else{
                                                echo $dato['nombre_divisa_caja'];
                                            } 
                                    ?></td>
                                    <td>
                                        <?php if ($dato['status_tipo_pago']==1):?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else:?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                    <?php if($dato['cod_tipo_pago']!=1): ?>
                                        <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal" 
                                        data-codigo="<?php //echo $dato["cod_tipo_pago"]; ?>" 
                                        data-medio="<?php //echo $dato["medio_pago"]; ?>" 
                                        data-divisa="<?php //echo $dato["abreviatura"]; ?>" 
                                        data-nombre="<?php //echo $dato["nombre"]; ?>"
                                        data-status="<?php //echo $dato["status_pago"]; ?>" >
                                        <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminartpago"
                                        data-codigo="<?php //echo $dato["cod_tipo_pago"]; ?>" 
                                        data-medio="<?php //echo $dato["medio_pago"]; ?>" >
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php else: ?>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.reponsive -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
        <!-- /.content -->
</div>
<!-- /.content-wrapper -->

    <!-- Modal para registrar tipo de pago -->
<div class="modal fade" id="addPaymentTypeModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addPaymentTypeModalLabel">Registrar Tipo de Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addPaymentTypeForm" method="post">
                <div class="modal-body">
                    <label for="nombre_tipo_pago">Nombre del Tipo de Pago<span class="text-danger" style="font-size: 15px;"> *</span></label>
                    <div class="input-group">
                        <select class="form-control" id="nombre_tipo_pago" name="cod_metodo" required>
                            <option value="" disabled selected>Seleccione un tipo de pago</option>
                            <?php foreach ($tipos_pago as $tipo): ?>
                                <option value="<?= $tipo['cod_metodo']; ?>"><?= $tipo['medio_pago']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#modalmpago">+</button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Tipo de Moneda<span class="text-danger" style="font-size: 15px;"> *</span></label>
                        <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                            <label class="btn btn-outline-primary active">
                                <input type="radio" name="tipo_moneda" id="digital" value="2" checked> Digital
                            </label>
                            <label class="btn btn-outline-primary">
                                <input type="radio" name="tipo_moneda" id="efectivo" value="1"> Efectivo
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group bancos-container">
                        <label for="banco">Seleccionar Banco<span class="text-danger" style="font-size: 15px;"> *</span></label>
                        <select class="form-control" id="banco" name="cod_cuenta_bancaria" required>
                            <option value="" disabled selected>Seleccione un banco</option>
                            <?php foreach ($bancos as $banco): ?>
                                <option value="<?= $banco['cod_cuenta_bancaria']; ?>"><?= $banco['nombre_banco']; ?> - <?= $banco['numero_cuenta']; ?> - <?= $banco['tipo_cuenta_nombre']; ?> - <?= $banco['divisa_nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group cajas-container" style="display: none;">
                        <label for="caja">Seleccionar Caja<span class="text-danger" style="font-size: 15px;"> *</span></label>
                        <select class="form-control" id="caja" name="cod_caja" required>
                            <option value="" disabled selected>Seleccione una caja</option>
                            <?php foreach ($cajas as $caja): ?>
                                <option value="<?= $caja['cod_caja']; ?>"><?= $caja['nombre']; ?> - <?= $caja['divisa_nombre']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
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
                window.location = 'tpago';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL EDITAR TIPO DE PAGO
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
                        <label for="tpago">Tipo de Pago</label>
                        <input type="text" class="form-control" id="tpago" name="tpago" required>
                        <div class="invalid-feedback" style="display: none;"></div>
                        <input type="hidden" id="origin" name="origin">
                    </div>
                    <div class="form-group">
                        <label for="divisa">Divisa</label>
                        <input type="text" class="form-control" id="divisa1" readonly>
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
                <button type="submit" form="editForm" class="btn btn-primary" name="editar">Guardar cambios</button>
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
                window.location = 'tpago';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->
<div class="modal fade" id="eliminartpago" tabindex="-1" aria-labelledby="eliminartpagoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title" id="eliminartpagoLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="elimodal" method="post"> 
                <p>¿Está seguro que desea eliminar a <b><span id="tpagonombre"></span>?</b></p>
                <input type="hidden" id="tpagoCodigo" name="tpagoCodigo"> 
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
                window.location = 'tpago';
            }
        });
    </script>
<?php endif; ?>

<!-- =============================
    MODAL NUEVO METODO DE PAGO
================================== -->
<div class="modal fade" id="modalmpago" tabindex="-1" aria-labelledby="modalmpagoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="exampleModalLabel">Registrar medio de pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formregistrarUnidad" action="index.php?pagina=tpago" method="post">
                    <div class="form-group">
                        <label for="tipo_medida">Medio de Pago</label>
                        <!-- TOOLTIPS-->
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa la unidad de medida para la venta de productos, por ejemplo: Kg">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function () {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" name="medio" id="medio" placeholder="Ej: Efectivo" required>
                        <!--<input type="hidden" name="vista" value="unidad">-->
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="guardarm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('medioModal', 'true');
                window.location='tpago';
            }
    });
</script> 
<?php endif; ?> 

<script>
    // NUEVA CATEGORIA DESDE PRODUCTO
//(Validar nombre)
$('#medio').blur(function (e){
        var buscar=$('#medio').val();
        $.post('index.php?pagina=tpago', {buscar}, function(response){
            if(response != ''){
                Swal.fire({
                    icon: 'warning',
                    title: 'Advertencia',
                    text: 'La categoria ya se encuentra registrada',
                    confirmButtonText: 'Aceptar'
                });
            }
        },'json');
    });

$(document).ready(function() {
    // Verifica si el valor 'categoriaModal' está en localStorage
    if (localStorage.getItem('medioModal') === 'true') {
        $('#addPaymentTypeModal').modal('show');
        localStorage.removeItem('medioModal');
    }
});
</script>


<script src="vista/dist/js/modulos-js/tpago.js"></script>

