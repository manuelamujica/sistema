<?php 
#Requerir al controlador
require_once "controlador/categorias.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Categorías</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Categorías</li>
                    </ol>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarCategoria">Registrar categoría</button>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table id="categorias" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Categoría</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>         
                                </thead>
                                <tbody>
                                <!-- Tabla con los datos que se muestren dinamicamente -->
                                    <?php
                                    foreach ($registro as $categoria){
                                        ?>
                                        <tr>
                                            <td> <?php echo $categoria["cod_categoria"] ?></td>
                                            <td> <?php echo $categoria["nombre"] ?></td>
                                            <td>
                                                <?php if ($categoria['status']==1):?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else:?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif;?>
                                            </td>
                                            <!-- Botones -->
                                            <td>
                                                <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                data-codigo="<?php echo $categoria["cod_categoria"]; ?>"
                                                data-nombre="<?php echo $categoria["nombre"]; ?>"
                                                data-status="<?php echo $categoria["status"]; ?>">
                                                <i class="fas fa-pencil-alt"></i></button>

                                                <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarModal"
                                                data-codigo="<?php echo $categoria["cod_categoria"]; ?>"
                                                data-nombre="<?php echo $categoria["nombre"]; ?>">
                                                <i class="fas fa-trash-alt"></i></button>
                                                
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        </div>
                    </div>
<!-- =============================
    MODAL REGISTRAR CATEGORÍA 
================================== -->
                    <div class="modal fade" id="modalRegistrarCategoria" tabindex="-1" aria-labelledby="modalRegistrarCategoriaLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registrarModalLabel">Registrar categoría</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formRegistrarCategoria" method="post">
                                        <div class="form-group">
                                            <label for="nombre">Nombre de la categoría</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre de la categoría" required>
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
                window.location = 'categorias';
            }
        });
    </script>
<?php endif; ?>
<!-- =============================
    MODAL EDITAR CATEGORÍA 
================================== -->
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
                                            <label for="codigo">Nombre</label>
                                            <input type="text" class="form-control" id="name" name="nombre">
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
                window.location = 'categorias';
            }
        });
    </script>
<?php endif; ?>

<!-- ====================================
    MODAL CONFIRMAR ELIMINAR CATEGORÍA 
========================================= -->
                    <div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="eliminarModalLabel">Eliminar categoría</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="eliminarForm" method="post">
                                        <div class="form-group">
                                            <p>¿Estás seguro que deseas eliminar a <b><span id="catnombre"></b></span>?</p>
                                            <input type="hidden" id="catcodigo" name="catcodigo">
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                    <button type="submit" form="eliminarForm" class="btn btn-danger" id="confimDelete" name="borrar">Eliminar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>      
    </section>
</div>
<script>
    //Validar registro
    $('#nombre').blur(function (e){
        var buscar=$('#nombre').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                alert('La categoria ya se encuentra registrada');
            }
        },'json');
    });
    //Validar editar
    $('#name').blur(function (e){
        var buscar=$('#name').val();
        $.post('index.php?pagina=categorias', {buscar}, function(response){
            if(response != ''){
                alert('La categoria ya se encuentra registrada');
            }
        },'json');
    });
    //Editar
    $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var codigo = button.data('codigo');
            var nombre = button.data('nombre');
            var status = button.data('status');

            // Modal
            var modal = $(this); 
            modal.find('.modal-body #codigo').val(codigo);
            modal.find('.modal-body #name').val(nombre);
            modal.find('.modal-body #status').val(status);
        });
    //Eliminar
    $('#eliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nombre = button.data('nombre');
        var codigo = button.data('codigo');

        var modal = $(this);
        modal.find('#catnombre').text(nombre);
        modal.find('.modal-body #catcodigo').val(codigo);
    });
</script>