<?php require_once 'controlador/proveedores.php'; ?>

<!-- FUNCIONAL TODO LO DE PROVEEDOR FALTA ES AGREGAR BIEN LA VISTA DE REPESENTANTE -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Proveedores</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                        <li class="breadcrumb-item active">Proveedores</li>
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalProv">Registrar proveedor</button>

                        </div>
                        <div class="card-body">

                            <!-- Tabla de proveedores -->
                            <div class="table-responsive">
                                <table id="proveedores" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Rif</th>
                                            <th>Razon social</th>
                                            <th>Correo electronico</th>
                                            <th>Dirección</th>
                                            <th>Status</th>
                                            <th>Representante</th>
                                            <th>Telefonos</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($registro as $datos) { ?>
                                            <?php if ($datos['status'] != 2): ?>
                                                <tr>
                                                    <td><?php echo $datos["cod_prov"] ?></td>
                                                    <td><?php echo $datos["rif"] ?></td>
                                                    <td><?php echo $datos["razon_social"] ?></td>
                                                    <td><?php echo $datos["email"] ?></td>
                                                    <td><?php echo $datos["direccion"] ?></td>

                                                    <td>
                                                        <?php if ($datos['status'] == 1): ?>
                                                            <span class="badge bg-success">Activo</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Inactivo</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <!-- regidtro de solo el nombre del represeye proveeedor
                                                -->
                                                    <td>
                                                        <button name="mas" class="btn btn-sm btn-primary mas" title="mas" data-toggle="modal" data-target="#myModal"
                                                            data-cod1="<?php echo $datos['cod_prov']; ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <?php echo $datos["nombre"] ?>

                                                        <!-- mostrar representante del proveeedo-->


                                                        <button name="mostrar" class="btn btn-danger btn-sm  mostrar" title="mostrar" data-toggle="modal" data-target="#myModalr">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </td>

                                                    <!-- regidtro de telefono del proveeed-->
                                                    <td>
                                                        <button name="telef" class="btn btn-sm btn-info telef" title="telef" data-toggle="modal" data-target="#myModalt"
                                                            data-cod1="<?php echo $datos['cod_prov']; ?>" data-rif="<?php echo $datos['rif']; ?>">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                        <?php echo $datos["telefono"]; ?>



                                                    </td>

                                                    <td>

                                                        <button name="editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#modalProvedit" title="editar"
                                                            data-cod="<?php echo $datos["cod_prov"]; ?>"
                                                            data-rif="<?php echo $datos['rif']; ?>"
                                                            data-razon="<?php echo $datos["razon_social"]; ?>"
                                                            data-email="<?php echo $datos['email']; ?>"
                                                            data-dire="<?php echo $datos['direccion']; ?>"
                                                            data-status="<?php echo $datos['status']; ?>"
                                                            data-telefono="<?php echo $datos['telefono']; ?>">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </button>


                                                        <button name="eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" id="eliminar" data-target="#Modale" title="eliminar"
                                                            data-codigo="<?php echo $datos["cod_prov"]; ?>"
                                                            data-rif="<?php echo $datos["rif"]; ?>">

                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>


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


<!-- Modal de registro de proveedor -->
<div class="modal fade" id="modalProv" tabindex="-1" role="dialog" aria-labelledby="provModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModalLabel">Registrar proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedores" method="post">
                    <!--<div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">Rif</label>
                                        <input type="text" class="form-control" id="rif" name="rif" placeholder="Ingrese el Rif" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon_social">Razon Social</label>
                                        <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Ingrese Razon Social" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electronico:</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Ingrese  Correo Electronico" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="direccion">Direccion:</label>
                                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder=" Ingrese Direccion" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
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
</div>

<?php
if (isset($registrar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>

<!-- Modal de registro deproveedor   peo no sale el aler -->



<!-- Modal de registro de representante del proveedor -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Registrar representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cod_prov" id="cod_oculto" value="<?php echo $datos['cod_prov'] ?>" readonly>
                <form action="index.php?pagina=representantes" method="post">
                    <!--<div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">-->
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod1" value="<?php echo $datos['cod_prov'] ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" placeholder=" Ingrese Cédula" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder=" Ingrese Nombre" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" placeholder=" Ingrese Apellido">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder=" Ingrese Teléfono">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="ok">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>


<!-- Fin Modal de registro de representante del proveedor -->


<!-- Mostrar representante del proveedor -->
<div class="modal fade" id="myModalr" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Datos Del representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="cod_prov" id="cod_oculto" value="<?php echo $datos['cod_prov'] ?>" readonly>
                <form action="index.php?pagina=representantes" method="post">
                    <!--<div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">-->
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod1" value="<?php echo $datos['cod_prov'] ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">

                                <button type="button" name="modificar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#modalProveditr" title="editar">

                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <button type="button" name="eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#Modalel" title="eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<!-- Modal de editar representante -->
<div class="modal fade" id="modalProveditr" tabindex="-1" role="dialog" aria-labelledby="provModaledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModaledit">Editar representante</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formrepresentantesedit" method="post">
                    <input type="hidden" name="cod_representante" id="cod_oculto" value="<?php echo $datos['cod_representante']; ?>" readonly>
                    <input type="hidden" class="form-control" id="origin" name="origin" value="<?php echo $datos['cedula']; ?>">

                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del representante</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código</label>
                                <input type="text" class="form-control" name="cod_representante" id="cod" value="<?php echo $datos['cod_representante']; ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cedula">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula" name="cedula" value="<?php echo $datos['cedula']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo $datos['nombre']; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="apellido">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required value="<?php echo $datos['apellido']; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" required value="<?php echo $datos['telefono']; ?>">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="status">Estatus</label>
                                    <select name="status" id="status">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="editar">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>



<!-- Modal de editar representante-->

<!-- Modal de eliminar  representante-->
<div class="modal fade" id="Modalel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedoreselim" method="post">
                    <p>¿Desea eliminar al representante <span id="repreNombre"></span>?</p>
                    <input type="hidden" id="reprCodigo" name="reprCodigo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" name="eliminar" id="confirmDelete" class="btn btn-danger" value="eliminar">Sí</button>
                </form> <!-- Cerrando el form aquí -->
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!-- Modal de eliminar -->




<div class="modal fade" id="myModalt" tabindex="-1" role="dialog" aria-labelledby="pprovModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="pprovModalLabel">Registrar teléfono</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formTelefono" action="index.php?pagina=tproveedores" method="post">
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod1">Código</label>
                                <input type="text" class="form-control" name="cod_prov" id="cod1" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">RIF:</label>
                                        <input type="text" class="form-control" id="rif" name="rif" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" placeholder=" Ingrese Teléfono">
                                        <div class="invalid-feedback" style="color: red;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="okk">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!-- Fin Modal telefono del proveedor -->


<!-- Modal editar proveedor  -->
<div class="modal fade" id="modalProvedit" tabindex="-1" role="dialog" aria-labelledby="provModaledit" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff">
                <h5 class="modal-title" id="provModaledit">Editar proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedoresedit" method="post">
                    <input type="hidden" name="cod_prov" id="cod_oculto" value="<?php echo $datos['cod_prov'] ?>" readonly>
                    <div class="card card-default">
                        <div class="card-header" style="background: #E89005; color: #ffffff">
                            <h3 class="card-title">Información del proveedor</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="cod">Código </label>
                                <input type="text" class="form-control" name="cod_prov" id="cod" value="<?php echo $datos['cod_prov'] ?>" readonly>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="rif">RIF</label>
                                        <input type="text" class="form-control" id="rif" name="rif" value="<?php echo $datos['rif'] ?>" required>
                                        <input type="hidden" class="form-control" id="origin" name="origin">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="razon">Razón Social</label>
                                        <input type="text" class="form-control" id="razon" name="razon_social" value="<?php echo $datos['razon_social'] ?>" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Correo Electrónico:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $datos['email'] ?>" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dire">Dirección:</label>
                                        <input type="text" class="form-control" id="dire" name="direccion" value="<?php echo $datos['direccion'] ?>">
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Estatus</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="1" <?php echo ($datos['status'] == 1) ? 'selected' : ''; ?>>Activo</option>
                                            <option value="0" <?php echo ($datos['status'] == 0) ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo $datos['telefono'] ?>" required>
                                        <div class="invalid-feedback" style="display: none;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary" name="editar">Editar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php if (isset($editar)): ?>
    <script>
        Swal.fire({
            title: '<?php echo $editar["title"]; ?>',
            text: '<?php echo $editar["message"]; ?>',
            icon: '<?php echo $editar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>

<!-- Modal editar proveedor  e-->




<!-- Modal de eliminar -->

<div class="modal fade" id="Modale" tabindex="-1" role="dialog" aria-labelledby="exampaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: #f72e2e">
                <h5 class="modal-title" id="exampaleModalLabel">Confirmar Eliminar </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formproveedoreselim" method="post">
                    <p>¿Desea eliminar a el proveedor ?</p>
                    <input type="hidden" id="provCodigo" name="provCodigo">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="submit" class="btn btn-danger" id="confirmDelete" name="eliminar">Sí</button>
                </form>
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
        }).then((resul) => {
            if (resul.isConfirmed) {
                window.location = 'proveedores';
            }
        });
    </script>
<?php endif; ?>
<!-- Modal de eliminar proveedor  -->

<!-- EDITARe proveedores  -->

<script>
    $('#modalProveditr').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var cod = button.data('cod');
        var cedula = button.data('cedula');
        var nombre = button.data('nombre');
        var apellido = button.data('apellido');
        var telefono = button.data('telefono');
        var status = button.data('status');

        var origin = button.data('cedula');

        // Modal
        var modal = $(this);
        modal.find('.modal-body #cod').val(cod);
        modal.find('.modal-body #cedula').val(cedula);
        modal.find('.modal-body #nombre').val(nombre);
        modal.find('.modal-body #apellido').val(apellido);
        modal.find('.modal-body #telefono').val(telefono);
        modal.find('.modal-body #status').val(status);
        modal.find('.modal-body #origin').val(origin);


    });
</script>


<!-- VALIDACIONES DE 2 REGISTRO Y DE 1 EDITAR QUE ES PROVEEDOR   -->

<!-- validar tu  refistro de proveedores  -->
<script>
    $(document).ready(function() {
        // Validación por cada campo cuando se pierde el foco
        $('#rif').on('blur', function() {
            var rif = $(this).val();
            if (!/^[a-zA-Z0-9]+$/.test(rif)) {
                showError('#rif', 'SOLO LETRAS Y NÚMEROS');
            } else {
                hideError('#rif');
            }
        });

        $('#razon_social').on('blur', function() {
            var razon_social = $(this).val();
            if (!/^[a-zA-Z0-9\s\.,]+$/.test(razon_social)) {
                showError('#razon_social', 'NÚMEROS Y SIGNOS');
            } else {
                hideError('#razon_social');
            }
        });

        $('#direccion').on('blur', function() {
            var direccion = $(this).val();
            if (!/^[a-zA-Z0-9\s\.,]+$/.test(direccion)) {
                showError('#direccion', 'SOLO LETRAS, NÚMEROS Y SIGNOS');
            } else {
                hideError('#direccion');
            }
        });

        $('#email').on('blur', function() {
            var email = $(this).val();
            if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                showError('#email', 'EMAIL INVÁLIDO');
            } else {
                hideError('#email');
            }
        });

        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });
</script>


<!-- validar tu  refistro de reprsentante  -->
<script>
    $(document).ready(function() {
        // Validación de Cédula
        $('#cedula').on('blur', function() {
            var cedula = $(this).val();
            // Permite 5 dígitos o 9 dígitos con puntos
            if (!/^\d{5}$|^(\d{1,3}\.){2}\d{3}$/.test(cedula)) {
                showError('#cedula', 'DEBE SER 5 DÍGITOS O 9 DÍGITOS CON PUNTOS');
            } else {
                hideError('#cedula');
            }
        });

        // Validación de Nombre
        $('#nombre').on('blur', function() {
            var nombre = $(this).val();
            if (!/^[a-zA-Z\s]+$/.test(nombre)) { // Solo letras y espacios
                showError('#nombre', 'SOLO LETRAS Y ESPACIOS');
            } else {
                hideError('#nombre');
            }
        });

        // Validación de Apellido
        $('#apellido').on('blur', function() {
            var apellido = $(this).val();
            if (!/^[a-zA-Z\s]+$/.test(apellido)) { // Solo letras y espacios
                showError('#apellido', 'SOLO LETRAS Y ESPACIOS');
            } else {
                hideError('#apellido');
            }
        });

        // Validación de Teléfono
        $('#telefono').on('blur', function() {
            var telefono = $(this).val();
            if (!/^[\d\s()+-]+$/.test(telefono)) { // Solo números y signos permitidos
                showError('#telefono', 'SOLO NÚMEROS Y SIGNOS PERMITIDOS');
            } else {
                hideError('#telefono');
            }
        });

        // Función para mostrar el error
        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        // Función para ocultar el error
        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });
</script>

<!-- validar tu  refistro de reprsentante  -->


<!-- validar  telefono de proveedor   -->


<script>
    $(document).ready(function() {
        $('#formTelefono').on('submit', function(e) {
            var telefono = $('#telefono').val();
            // Validar que el teléfono contenga solo números y signos permitidos
            if (!/^[\d\s()+-]*$/.test(telefono)) {
                e.preventDefault(); // Evitar el envío del formulario
                $('#telefono').addClass('is-invalid');
                $('.invalid-feedback').text('El teléfono solo puede contener números y los signos (+, -, (, )).').show();
            } else {
                $('#telefono').removeClass('is-invalid');
                $('.invalid-feedback').hide();
            }
        });
    });
</script>



<!-- validar  telefono de proveedor   -->



<!-- validar  editar proveedor   -->



<script>
    $(document).ready(function() {
        // Validación por cada campo cuando se pierde el foco
        $('#rif').on('blur', function() {
            var rif = $(this).val().trim();
            if (!/^[a-zA-Z0-9-]+$/.test(rif)) { // Permitir guiones para RIF
                showError('#rif', 'SOLO LETRAS, NÚMEROS Y GUIONES');
            } else {
                hideError('#rif');
            }
        });

        $('#razon').on('blur', function() {
            var razon_social = $(this).val().trim();
            if (!/^[a-zA-Z0-9\s\.,]+$/.test(razon_social)) {
                showError('#razon', 'SOLO LETRAS, NÚMEROS Y SIGNOS');
            } else {
                hideError('#razon');
            }
        });

        $('#dire').on('blur', function() {
            var direccion = $(this).val().trim();
            if (!/^[a-zA-Z0-9\s\.,]+$/.test(direccion)) {
                showError('#dire', 'SOLO LETRAS, NÚMEROS Y SIGNOS');
            } else {
                hideError('#dire');
            }
        });

        $('#email').on('blur', function() {
            var email = $(this).val().trim();
            if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                showError('#email', 'EMAIL INVÁLIDO');
            } else {
                hideError('#email');
            }
        });

        $('#telefono').on('blur', function() {
            var telefono = $(this).val().trim();
            if (!/^[0-9\s\+\-]*$/.test(telefono)) { // Permitir solo números, espacios, + y -
                showError('#telefono', 'SOLO NÚMEROS, ESPACIOS Y SIGNOS (+, -)');
            } else {
                hideError('#telefono');
            }
        });

        function showError(selector, message) {
            $(selector).addClass('is-invalid');
            $(selector).next('.invalid-feedback').html('<i class="fas fa-exclamation-triangle"></i> ' + message.toUpperCase()).css({
                'display': 'block',
                'color': 'red',
                'background-color': 'white'
            });
        }

        function hideError(selector) {
            $(selector).removeClass('is-invalid');
            $(selector).next('.invalid-feedback').css('display', 'none');
        }
    });
</script>


<!-- validar  editar proveedor   -->



<script src="vista/dist/js/modulos-js/proveedores.js"> </script><!-- fañta pasar ypdas las vañidaciones a las carpetas   -->
<script src="vista/dist/js/modulos-js/representantes.js"> </script>