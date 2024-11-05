<?php require_once 'controlador/roles.php';?>
<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Ajustar Roles</h1>
        </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Roles</li>
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalregistrarroles">Registrar Rol</button>
            </div>
            <div class="card-body">
            <div class="table-responsive">
            <table id="categorias" class="table table-bordered table-striped datatable">
                <thead>
                        <tr>
                            <th>Código</th>
                            <th>Rol</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr>
                </thead>
                <tbody>
                <?php foreach ($registro as $dato) {?>
                    <tr>
                        <td>
                            <?php echo $dato['cod_tipo_usuario']?>
                        </td>
                        <td>
                            <?php echo $dato['rol']?>
                        </td>
                        <td>
                            <?php if ($dato['status']==1): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                        <?php if($dato['cod_tipo_usuario']!=1): ?>
                            <button name="modificar" title="Editar" class="btn btn-primary btn-sm editar" value="<?php echo $dato['rol']; ?>">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['rol']; ?>">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        <?php endif;
                    } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>

<!-- =======================
MODAL REGISTRAR ROLES
============================= -->

        <div class="modal fade" id="modalregistrarroles" tabindex="-1" aria-labelledby="modalregistrarrolLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Registar Rol</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <form id="formAgregarRol" method="post">
                            <!--   NOMBRE DEL ROL     -->
                            <div class="form-group">
                                <label for="rol">Rol</label>
                                <input type="text" class="form-control" id="rol" name="rol" placeholder="Ingresa el nombre">
                            </div>
                            <div class="form-group">
                                <label for="permisos">Selecciona los permisos:</label><br>
                                <?php foreach ($permiso as $datos): ?>
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox" name="permisos[]" value="<?php echo $datos['cod_permiso']; ?>" id="categoria<?php echo $datos['cod_permiso']; ?>">
                                        <label for="categoria<?php echo $datos['cod_permiso']; ?>">
                                            <?php echo $datos['nombre'] ?>
                                        </label>
                                    </div>
                                    <br>
                                <?php endforeach; ?>
                            </div>

                    </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" name="guardar">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
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
                window.location = 'roles';
            }
        });
    </script>
<?php endif; ?>

<script>
    $('#rol').blur(function (e){
        var buscar=$('#rol').val();
        $.post('index.php?pagina=roles', {buscar}, function(response){
        if(response != ''){
            alert('Este rol ya se encuentra registrado');
        }
        },'json');
    });
</script>