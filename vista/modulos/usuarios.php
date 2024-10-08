<?php 
#Requerir al controlador
require_once "controlador/usuarios.php";
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Usuarios</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Usuarios</li>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarusuario">Registrar usuario</button>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table id="usuarios" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>User</th>
                                        <th>Rol</th>
                                        <th>Status</th>
                                        <th>Acciones</th>
                                    </tr>         
                                </thead>
                                <tbody>
                                <!-- Tabla con los datos que se muestren dinamicamente -->
                                    <?php
                                    foreach ($registro as $usuario){
                                        ?>
                                        <tr>
                                            <td> <?php echo $usuario["cod_usuario"] ?></td>
                                            <td> <?php echo $usuario["nombre"] ?></td>
                                            <td> <?php echo $usuario["user"] ?></td>
                                            <td> <?php echo $usuario["cod_tipo_usuario"] ?></td>
                                            <td>
                                                <?php if ($usuario['status']==1):?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else:?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif;?>
                                            </td>
                                            <!-- Botones -->
                                            <td>
                                                <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                data-codigo="<?php echo $usuario["cod_usuario"]; ?>"
                                                data-nombre="<?php echo $usuario["nombre"]; ?>"
                                                data-user="<?php echo $usuario["user"]; ?>"
                                                data-cod="<?php echo $usuario["cod_tipo_usuario"]; ?>"
                                                data-status="<?php echo $usuario["status"]; ?>">
                                                <i class="fas fa-pencil-alt"></i></button>

                                                <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarModal"
                                                data-codigo="<?php echo $usuario["cod_usuario"]; ?>"data-nombre="<?php echo $usuario["nombre"]; ?>">
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
    MODAL REGISTRAR USUARIO 
================================== -->
                    <div class="modal fade" id="modalregistrarusuario" tabindex="-1" aria-labelledby="modalregistrarusuariolabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registrarModalLabel">Registrar usuario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="formregistrarusuario" method="post">
                                        <div class="form-group">
                                            <label for="nombre">Nombre y apellido</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre y apellido"  required>

                                            <label for="user">Usuario</label>
                                            <input type="text" class="form-control" id="user" name="user" placeholder="Ingresa el nombre de usuario"  required>

                                            <label for="pass">Contraseña</label>
                                            <input type="password" class="form-control" id="pass" name="pass" placeholder="Ingresa la contraseña"   required>
                                        
                                                <label for="rol">Rol</label>
                                                <select class="form-control" id="rol" name="rol" required>
                                                <option value="" selected disabled>Seleccione un rol</option>
                                                    <?php foreach($roles as $role): ?>
                                                        <option value="<?php echo $role['cod_tipo_usuario']; ?>">
                                                            <?php echo $role['rol']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            
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
<!-- =============================
    MODAL EDITAR USUARIO 
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
                                            <label for="codigo">User</label>
                                            <input type="text" class="form-control" id="usuario" name="user">
                                        </div>
                                        <div class="form-group">
                                            <label for="codigo">Contraseña</label>
                                            <input type="password" class="form-control" id="password" name="pass" placeholder="Ingrese la nueva contraseña">
                                        </div>
                                        <label for="roles">Rol</label>
                                                <select class="form-control" id="roles" name="roles" required>
                                                    <?php foreach($roles as $role): ?>
                                                        <option value="<?php echo $role['cod_tipo_usuario']; ?>">
                                                            <?php echo $role['rol']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
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
<!-- ====================================
    MODAL CONFIRMAR ELIMINAR CATEGORÍA 
========================================= -->
<div class="modal fade" id="eliminarModal" tabindex="-1" aria-labelledby="eliminarModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger">
                                    <h5 class="modal-title" id="eliminarModalLabel">Eliminar Usuario</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="eliminarForm" method="post">
                                        <div class="form-group">
                                            <p>¿Estás seguro que deseas eliminar a <b><span id="username"></b></span>?</p>
                                            <input type="hidden" id="usercode" name="usercode">
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
    $('#user').blur(function (e){
        var buscar=$('#user').val();
        $.post('index.php?pagina=usuarios', {buscar}, function(response){
            if(response != ''){
                alert('El usuario ya se encuentra registrado');
            }
        },'json');
    });


    //Validar editar
    $('#usuario').blur(function (e){
        var buscar=$('#usuario').val();
        $.post('index.php?pagina=usuarios', {buscar}, function(response){
            if(response != ''){
                alert('El usuario ya se encuentra registrado');
            }
        },'json');
    });

    //Editar
    $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var codigo = button.data('codigo');
            var nombre = button.data('nombre');
            var user = button.data('user');
            var pass = button.data('password') //no muestra obviamente y falta que se actualice con HASH
            var rol = button.data('cod'); 
            var status = button.data('status');

            // Modal
            var modal = $(this); 
            modal.find('.modal-body #codigo').val(codigo);
            modal.find('.modal-body #name').val(nombre);
            modal.find('.modal-body #usuario').val(user);
            modal.find('.modal-body #password').val(pass);
            modal.find('.modal-body #roles').val(rol);            
            modal.find('.modal-body #status').val(status);
        });

    //Eliminar
    $('#eliminarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var codigo = button.data('codigo');
        var nombre = button.data('nombre');
        //var user = button.data('user');
        //var pass = button.data('password');
        //var rol = button.data('cod');        

        var modal = $(this);
        modal.find('#username').text(nombre);
        modal.find('.modal-body #usercode').val(codigo);
    });
</script>