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
                    <?php
                    foreach ($registro as $datos){
                        ?>
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
                                <form method="post" >
                                    <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-trash-alt" ></i></button>
                                </form>
                            </td>
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
</script>