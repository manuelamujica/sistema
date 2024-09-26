<?php require_once 'controlador/clientes.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Clientes</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Clientes</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarClientes">Registrar cliente</button>
            </div>
            <div class="card-body">

            <!-- TABLA -->
            <!-- <div class="table-responsive">-->
            <table id="clientes" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                <thead>
                        <tr>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Apellido</th> 
                            <th>Cédula-Rif</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Dirección</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($registro as $datos){ ?>
                    <?php if ($datos['status'] != 2): ?>
                        <tr>
                            <td> <?php echo $datos["cod_cliente"] ?></td>
                            <td> <?php echo $datos["nombre"] ?></td>
                            <td> <?php echo $datos["apellido"] ?></td>
                            <td> <?php echo $datos["cedula_rif"] ?></td>
                            <td> <?php echo $datos["telefono"] ?></td>
                            <td> <?php echo $datos["email"] ?></td>
                            <td> <?php echo $datos["direccion"] ?></td>
                            <td>
                                <?php if ($datos['status']==1):?>
                                <span class="badge bg-success">Activo</span>
                                <?php else:?>
                                <span class="badge bg-danger">Inactivo</span>
                                <?php endif;?>
                            </td>
                            <td>
                            <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal" 
                            data-cedula_rif="<?php echo $datos["cedula_rif"]; ?>" 
                            data-codigo="<?php echo $datos["cod_cliente"]; ?>" 
                            data-nombre="<?php echo $datos["nombre"]; ?>" 
                            data-apellido="<?php echo $datos["apellido"]; ?>" 
                            data-telefono="<?php echo $datos["telefono"]; ?>" 
                            data-email="<?php echo $datos["email"]; ?>"
                            data-direccion="<?php echo $datos["direccion"]; ?>" 
                            data-status="<?php echo $datos["status"]; ?>">
                            <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarcliente"
                                data-codigo="<?php echo $datos["cod_cliente"]; ?>" 
                                data-nombre="<?php echo $datos["nombre"]; ?>">
                                <i class="fas fa-trash-alt" ></i>
                            </button>
                            </td>
                        <?php endif; ?>
                        <?php } ?>
                </tbody>
            </table>
            <!-- </div>-->
            <!-- /TABLA -->
        </div>
    </div>

<!-- =======================
MODAL REGISTRAR CLIENTES 
============================= -->

<div class="modal fade" id="modalRegistrarClientes" tabindex="-1" aria-labelledby="modalRegistrarClientesLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="clientesModalLabel">Registrar cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formRegistrarClientes" method="post">
                    <div class="form-group">
                        <label for="cedula_rif">Cédula o Rif:</label>
                        <input type="text" class="form-control" name="cedula_rif" id="cedula_rif" required>
                        
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" required>

                        <label for="apellido">Apellido:</label>
                        <input type="text" class="form-control" name="apellido" required>

                        <label for="telefono">Teléfono:</label>
                        <input type="tel" class="form-control" name="telefono">

                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email">

                        <label for="direccion">Direccion:</label>
                        <textarea class="form-control" name="direccion"></textarea>
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


<!-- =======================
MODAL EDITAR CLIENTES 
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
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="apellido">Apellido</label>
                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                    </div>
                    <div class="form-group">
                        <label for="cedula_rif">Cédula-Rif</label>
                        <input type="text" class="form-control" id="cedularif" name="cedula_rif" required>
                        <input type="hidden" class="form-control" id="origin" name="origin" >
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="form-group">
                        <label for="direccion">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion"></textarea>
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

<!-- =======================
MODAL CONFIRMAR ELIMINAR 
============================= -->

    <div class="modal fade" id="eliminarcliente" tabindex="-1" aria-labelledby="eliminarclienteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eliminarclienteLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form id="elimodal" method="post"> 
                    <p>¿Está seguro que desea eliminar a <span id="clienteNombre"></span>?</p>
                    <input type="hidden" id="clienteCodigo" name="clienteCodigo"> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" form="elimodal" class="btn btn-danger" id="confirmDelete" name="borrar">Eliminar</button>
            </div>
            </div>
        </div>
    </div>

</section>
</div>
<script>
    $('#cedula_rif').blur(function (e){
        var buscar=$('#cedula_rif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            if(response != ''){
                alert('El cliente ya se encuentra registrado');
            }
        },'json');
    });

    $('#cedularif').blur(function (e){
        var buscar=$('#cedularif').val();
        $.post('index.php?pagina=clientes', {buscar}, function(response){
            if(response != ''){
                alert('La cedula ya existe');
            }
        },'json');
    });


    $('#editModal').on('show.bs.modal', function (event) {
            var button=$(event.relatedTarget);
            var codigo=button.data('codigo');
            var nombre=button.data('nombre');
            var apellido=button.data('apellido');
            var cedula_rif=button.data('cedula_rif');
            var telefono=button.data('telefono');
            var email=button.data('email');
            var direccion=button.data('direccion');
            var status=button.data('status');
            var origin=button.data('cedula_rif');

            // Modal
            var modal = $(this);
            modal.find('.modal-body #codigo').val(codigo);
            modal.find('.modal-body #nombre').val(nombre);
            modal.find('.modal-body #apellido').val(apellido);
            modal.find('.modal-body #cedularif').val(cedula_rif);
            modal.find('.modal-body #telefono').val(telefono);
            modal.find('.modal-body #email').val(email);
            modal.find('.modal-body #direccion').val(direccion);
            modal.find('.modal-body #status').val(status);
            modal.find('.modal-body #origin').val(origin);
        });

    $('#eliminarcliente').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var nombre = button.data('nombre');
        var codigo = button.data('codigo');

        var modal = $(this);
        modal.find('#clienteNombre').text(nombre);
        modal.find('.modal-body #clienteCodigo').val(codigo);
    });
</script>