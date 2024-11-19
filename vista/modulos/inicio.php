<?php require_once 'controlador/divisa.php'; ?>
<!-- Preloader-->
<div class="preloader flex-column justify-content-center align-items-center">
<?php 
        if(isset($_SESSION["logo"])): ?>
            <img src="<?php echo $_SESSION["logo"];?>" alt="Quesera Don Pedro" class="" height="200" width="200">
        <?php else: ?>
            <img src="vista/dist/img/logo_generico.png" alt="Quesera Don Pedro"  class="" height="200" width="200">
        <?php endif; ?>
</div>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>BIENVENIDOS</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sistema para la comercialización de productos</h3>
            </div>
        <div class="card-body">
            Esta plataforma te brinda las herramientas necesarias para gestionar tu inventario, controlar las ventas y optimizar la comercialización de tus productos. Simplifica y agiliza tus operaciones en un solo lugar. ¡Empieza a explorar todas sus funciones y lleva tu negocio al siguiente nivel!
        </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->




<div class="modal fade" id="modalregistrarempresa" tabindex="-1" aria-labelledby="modalregistrarempresaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar informacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formGeneral" action="index.php?pagina=general" method="post" enctype="multipart/form-data">
                    <!--   RIF DE LA empresa     -->
                    <div class="form-group">
                        <label for="rif">Rif de la empresa <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el rif de la empresa, por ejemplo: J-010523">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" id="rif" name="rif" maxlength="15" placeholder="Ej: J123456789">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!--   NOMBRE DE LA empresa     -->
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger" style="font-size: 20px;"> *</span> </label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el nombre o razón social de la empresa, por ejemplo: Lacteos los Andes">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Ej: Inversiones SAVYC">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!-- DIRECCION-->
                    <div class="form-group row">
                        <div class="col-6">
                            <label for="direccion">Dirección <span class="text-danger" style="font-size: 20px;"> *</span></label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa la dirección de la empresa, por ejemplo: Avenida Los Horcones">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="text" class="form-control" name="direccion" id="direccion" maxlength="100" placeholder="Ej: Av. ejemplo con calle 1">
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>
                    <!--   TELEFONO     -->
                        <div class="col-6">
                            <label for="telefono">Teléfono<span class="text-danger" style="font-size: 20px;"> *</span></label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el telefono de la empresa, por ejemplo: 0424-555-21-23">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="tel" class="form-control" name="telefono" id="telefono" maxlength="12" placeholder="Ej: 0412-1234567">
                            <div class="invalid-feedback" style="display: none;"></div>
                        </div>
                    </div>
                    <!--   EMAIL     -->
                    <div class="form-group ">
                            <label for="email">Correo:</label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa el correo de la empresa, por ejemplo: savyc@gmail.com">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <input type="hidden" name="inicio" value="inicio">
                            <input type="email" class="form-control" name="email" id="email" maxlength="70" placeholder="Ej: savyc@gmail.com">
                            <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <div class="form-group">
                            <!--   DESCRIPCION    -->
                            <label for="descripcion">Descripción</label>
                            <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa una descripción breve de la empresa, por ejemplo: Comercio para la venta de alimentos">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <script>
                                $(function() {
                                    $('[data-toggle="tooltip"]').tooltip();
                                });
                            </script>
                            <textarea class="form-control" name="descripcion" id="descripcion" maxlength="100" placeholder="Ej: Comercio para la venta de alimentos"></textarea>
                            <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!--   LOGO    -->
                    <div class="form-group">
                        <label for="logo">Ingrese el logo<span class="text-danger" style="font-size: 20px;"> *</span></label>
                        <button class="btn btn-xs" data-toggle="tooltip" data-placement="top" title="Ingresa un logo representativo de la empresa">
                            <i class="fas fa-info-circle"></i>
                        </button>
                        <script>
                            $(function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        </script>
                        <input type="file" class="form-control" name="logo" id="logo">
                        <div class="invalid-feedback" style="display: none;"></div>
                    </div>
                    <!-- Alert Message -->
                    <div class="alert alert-light d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span>Todos los campos marcados con (*) son obligatorios</span>
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
if (isset($registrar)){ ?>
    <script>
        Swal.fire({
            title: '<?php echo $registrar["title"]; ?>',
            text: '<?php echo $registrar["message"]; ?>',
            icon: '<?php echo $registrar["icon"]; ?>',
            confirmButtonText: 'Ok'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location = 'inicio';
            }
        });
    </script>
<?php } ?>
<?php if(empty($_SESSION["rif"]) && $_SESSION["cod_usuario"] != 1):?>
    <script>
        console.log("pasa la primera condicion");
        $(document).ready(function() {           
            if (!localStorage.getItem("modalMostrado")) {
                $('#modalregistrarempresa').modal('show');
                localStorage.setItem("modalMostrado", "true");
            }
        });
    </script>
<?php endif; ?>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Actualizar Tasas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post">
                    <?php foreach($consulta as $index=>$divisa): 
                        if($divisa['cod_divisa']!=1):?>
                    <div class="form-group">
                        <input type="hidden" class="form-control" id="codigo" name="tasa[<?= $index ?>][cod_divisa]" value="<?= $divisa['cod_divisa'];?>">
                    </div>
                    <div class="form-group">
                        <label for="nombre">Divisa</label>
                        <input type="text" class="form-control" value="<?= $divisa['nombre'].' - '.$divisa['abreviatura']; ?>" readonly>
                    </div>
                    <div class="form-group row justify-content-center">
                        <div class="col-md-7">
                            <label for="tasa">Tasa de la Divisa</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" value="<?= $divisa['tasa'];?>" name="tasa[<?= $index ?>][tasa]" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Bs</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="fecha">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="tasa[<?= $index ?>][fecha]" value="<?= $divisa['fecha'];?>" required>
                        </div>
                    </div>
                    <hr>
                    <?php endif; 
                    endforeach; ?>
                    <input type="hidden" name="inicio">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" form="editForm" class="btn btn-primary" name="r_tasa">Guardar cambios</button>
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
                window.location = 'inicio';
            }
        });
    </script>
<?php endif; ?>

<?php 
    $ultimo=end($consulta);
    if($ultimo['cod_divisa']!=1):
        if($ultimo['fecha'] != date('Y-m-d') && $_SESSION["cod_usuario"] != 1): 
?>
    <script>
        console.log("pasa la primera condicion");
        $(document).ready(function() {           
                $('#editModal').modal('show');
        });
    </script>
<?php   endif; 
    endif; ?>