<?php require_once 'controlador/tpago.php'; ?>

<div class="content-wrapper">
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Gestión de Tipos de Pago</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
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
                    <!-- /.card-header -->
                    <div class="card-body">
                    <div class="table-responsive">
                        <table id="paymentTypesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Metodo de pago</th>
                                    <th>Divisa</th>
                                    <th>Status</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Aquí se llenará la tabla dinámicamente con PHP -->
                                <?php foreach ($registro as $dato) { ?>
                                <?php if ($dato['status_pago'] != 2): ?>
                                <tr>
                                    <td><?php echo $dato['cod_tipo_pago']?></td>
                                    <td><?php echo $dato['medio_pago']?></td>
                                    <td><?php echo $dato['abreviatura']?></td>
                                    <td>
                                        <?php if ($dato['status_pago']==1):?>
                                            <span class="badge bg-success">Activo</span>
                                        <?php else:?>
                                            <span class="badge bg-danger">Inactivo</span>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                    <?php if($dato['cod_tipo_pago']!=1): ?>
                                        <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal" 
                                        data-codigo="<?php echo $dato["cod_tipo_pago"]; ?>" 
                                        data-medio="<?php echo $dato["medio_pago"]; ?>" 
                                        data-divisa="<?php echo $dato["abreviatura"]; ?>" 
                                        data-nombre="<?php echo $dato["nombre"]; ?>"
                                        data-status="<?php echo $dato["status_pago"]; ?>" >
                                        <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminartpago"
                                        data-codigo="<?php echo $dato["cod_tipo_pago"]; ?>" 
                                        data-medio="<?php echo $dato["medio_pago"]; ?>" >
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
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #db6a00 ;color: #ffffff; ">
                    <h5 class="modal-title" id="addPaymentTypeModalLabel">Registrar Tipo de Pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addPaymentTypeForm" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="paymentTypeName">Nombre del Tipo de Pago</label>
                            <input type="text" class="form-control" id="tipo_pago" name="tipo_pago" required>
                        </div>
                        <div class="form-group">
                            <label for="divisa">Seleccionar Divisa</label>
                            <select class="form-control" id="divisa" name="divisa" required>
                                <option value="" disabled selected>Seleccione una divisa</option>
                                <?php foreach ($divisas as $divisa): ?>
                                    <option value="<?= $divisa['cod_cambio']; ?>"><?= $divisa['nombre']." - ". $divisa['abreviatura']; ?></option>
                                <?php endforeach; ?>
                            </select>
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
            <div class="modal-header">
                <h5 class="modal-title" id="eliminartpagoLabel">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="elimodal" method="post"> 
                <p>¿Está seguro que desea eliminar a <span id="tpagonombre"></span>?</p>
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

<script>
$('#tipo_pago').blur(function (e){
    var buscar=$('#tipo_pago').val();
    $.post('index.php?pagina=tpago', {buscar}, function(response){
    if(response != ''){
        alert('Este tipo de pago ya existe');
    }
    },'json');
});

$('#tpago').blur(function (e){
    var buscar=$('#tpago').val();
    $.post('index.php?pagina=tpago', {buscar}, function(response){
    if(response != ''){
        alert('Este tipo de pago ya existe');
    }
    },'json');
});

$('#editModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var codigo = button.data('codigo');
    var tpago = button.data('medio');
    var status = button.data('status');
    var origin = button.data('medio');
    var nombre = button.data('nombre')+" - "+button.data('divisa');
    // Modal
    var modal = $(this);
    modal.find('.modal-body #codigo').val(codigo);
    modal.find('.modal-body #tpago').val(tpago);
    modal.find('.modal-body #status').val(status);
    modal.find('.modal-body #divisa1').val(nombre);
    modal.find('.modal-body #origin').val(origin);
});

$('#eliminartpago').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); 
    var medio = button.data('medio');
    var codigo = button.data('codigo');

    var modal = $(this);
    modal.find('#tpagonombre').text(medio);
    modal.find('.modal-body #tpagoCodigo').val(codigo);
});

</script>
