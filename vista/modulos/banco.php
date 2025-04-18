<?php require_once 'controlador/banco.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Banco</h1>
                <p>En esta sección se puede configurar los bancos.</p>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarBanco">Registrar Banco</button>
            </div>
            <div class="card-body">

            <!-- TABLA -->
            <div class="table-responsive">
            <table id="banco" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre de la entidad bancaria</th>
                            <th>Acciones</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($registro as $datos){ ?>
                   
                        <tr>
                            <td> <?php echo $datos["cod_banco"] ?></td>
                            <td> <?php echo $datos["nombre_banco"] ?></td>
                          
    
                            <td>
                            <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal" 
                            data-codigo="<?php echo $datos["cod_banco"]; ?>" 
                            data-nombre="<?php echo $datos["nombre_banco"]; ?>" 
                        >
                            <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarbanco"
                                data-codigo="<?php echo $datos["cod_banco"]; ?>" 
                                data-nombre="<?php echo $datos["nombre_banco"]; ?>">
                                <i class="fas fa-trash-alt" ></i>
                            </button>
                            </td>
                     
                        <?php } ?>
                </tbody>
            </table>
            </div>
            <!-- /TABLA -->
        </div>
    </div>

<!-- =======================
MODAL REGISTRAR BANCO
============================= -->
    <div class="modal fade" id="modalRegistrarBanco" tabindex="-1" aria-labelledby="modalRegistrarBancoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bancoModalLabel">Registrar cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formRegistrarBanco" method="post">
                        
                        <div class="form-group row">
                            <div class="col-6">
                                <label for="nombre">Nombre:<span class="text-danger" style="font-size: 15px;"> *</span></label>
                                <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese el nombre de la entidad bancaria" required>
                                <div class="invalid-feedback"></div>
                            </div>
                        
                    </form>
                    <!-- Alert Message -->
                    <div class="alert alert-light d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span>Todos los campos marcados con (*) son obligatorios</span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" name="guardar" form="formRegistrarBanco">Guardar</button>
                </div>
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
                window.location = 'banco';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL EDITAR BANCO
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
                 
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre1" name="nombre" required>
                            <div class="invalid-feedback"></div>
                        </div>
                      
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" name="actualizar" form="editForm">Guardar cambios</button>
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
                window.location = 'banco';
            }
        });
    </script>
<?php endif; ?>

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->

    <div class="modal fade" id="eliminarbanco" tabindex="-1" aria-labelledby="eliminarclienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title" id="eliminarbancoLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="elimodal" method="post"> 
                    <p>¿Está seguro que desea eliminar a <span id="bancoNombre"></span>?</p>
                    <input type="hidden" id="bancoCodigo" name="bancoCodigo">
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
            window.location = 'banco';
        }
    });
</script>

</div>

<?php endif; ?>
</div>