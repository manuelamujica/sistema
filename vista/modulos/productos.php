<?php 
#Requerir al controlador
require_once "controlador/productos.php";
?>

<div class="content-wrapper">
    <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Productos</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="inicio">Inicio</a></li>
                    <li class="breadcrumb-item active">Productos</li>
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
                            <!-- Boton registrar producto -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarProducto">Registrar producto</button>
                        </div>
                        <div class="card-body">
                        <div class="table-responsive">
                            <table id="productos" class="table table-bordered table-striped table-hover datatable" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Marca</th>
                                        <th>Presentacion</th>
                                        <th>Categoría</th>
                                        <th>Costo</th>
                                        <th>IVA</th>
                                        <th>Precio de venta</th>
                                        <th>Stock</th>
                                        <th>Detalle</th>
                                        <th>Acciones</th>
                                    </tr>         
                                </thead>
                                <tbody>
                                <!-- Tabla con los datos que se muestren dinamicamente -->
                                    <?php
                                    foreach ($registro as $producto){
                                        ?>
                                        <tr>
                                            <td> <?php echo $producto["cod_producto"] ?></td>
                                            <td> <?php echo $producto["nombre"] ?></td>
                                            <td> <?php echo $producto["marca"] ?></td>
                                            <td> <?php echo $producto["presentacion"] ?></td>
                                            <td> <?php echo $producto["cat_nombre"] ?></td>
                                            <td> <?php echo $producto["costo"] ?></td>
                                            <td> <?php echo $producto["excento"] ?></td>
                                            <td><?php $precioVenta = ($producto["porcen_venta"] / 100 + 1)*$producto["costo"]; echo $precioVenta?>
                                            </td>
                                            <td>Stock total</td>
                                            <!-- Detalle de producto -->
                                            <td class="text-center">
                                                <button class="btn btn-primary btn-sm" style="position: center;">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                                
                                            </td>
                                            <!-- Botones -->
                                            <td>
                                                <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                data-codigo="<?php echo $producto["cod_producto"];?>"
                                                data-nombre="<?php echo $producto["nombre"]; ?>"
                                                data-marca="<?php echo $producto["marca"]; ?>"
                                                data-present="<?php echo $producto["presentacion"]; ?>"
                                                data-categoria="<?php echo $producto["cat_nombre"]; ?>"
                                                data-costo="<?php echo $producto["costo"]; ?>"
                                                data-excento="<?php echo $producto["excento"]; ?>"
                                                data-porcen="<?php echo $producto["porcen_venta"]; ?>">
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>

                                                <button name="eliminar" title="Eliminar" class="btn btn-danger btn-sm eliminar" data-toggle="modal" data-target="#eliminarModal"
                                                data-codigo="<?php echo $producto["cod_producto"];?>"
                                                data-nombre="<?php echo $producto["nombre"]; ?>"
                                                >
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
    MODAL REGISTRAR PRODUCTO 
================================== -->
                    <div class="modal fade" id="modalRegistrarProducto" tabindex="-1" aria-labelledby="modalRegistrarProductoLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="registrarModalLabel">Registrar producto</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <form id="formRegistrarProducto" method="post">
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="nombre">Nombre del producto</label>
                                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="marca">Marca</label>
                                                <input type="text" class="form-control" id="marca" name="marca" placeholder="Ingresa la marca">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="categoria">Categoría</label>
                                                    <select class="form-control" id="categoria" name="categoria" required>
                                                        <option value="" selected disabled>Seleccione una opción</option>
                                                            <?php foreach($categoria as $cate): ?>
                                                                <option value="<?php echo $cate['cod_categoria']; ?>">
                                                                    <?php echo $cate['nombre']; ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                    </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="excento">¿Tiene IVA?</label>
                                                    <select class="form-control" id="iva" name="iva" required>
                                                        <option value="" selected disabled>Seleccione una opción</option>
                                                        <option value="si">Si</option>
                                                        <option value="no">No</option>
                                                    </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="unidad">Unidad de medida</label>
                                                <select class="form-control" id="unidad" name="unidad" required>
                                                    <option value="" selected disabled>Seleccione una opción</option>
                                                        <?php foreach($unidad as $u): ?>
                                                            <option value="<?php echo $u['cod_unidad']; ?>">
                                                                <?php echo $u['tipo_medida']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                </select>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="presentacion">Presentación</label>
                                                <input type="text" class="form-control" id="presentacion" name="presentacion" placeholder="Ingresa la presentación">
                                            </div>
                                            <div class="col-6">
                                                <label for="cant_presentacion">Cantidad de presentación</label>
                                                <input type="text" class="form-control" id="cant_presentacion" name="cant_presentacion" placeholder="Ingresa la cantidad">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <div class="col-6">
                                                <label for="costo">Costo</label>
                                                <input type="number" class="form-control" min="0" id="costo" name="costo" placeholder="Precio de compra" required>
                                            </div>
                                            <div class="col-6">
                                                <label for="precio">Precio</label>
                                                <input type="number" class="form-control" min="0" id="precio" placeholder="Precio de venta" readonly >
                                            </div>
                                        </div>
                                        <div class="input-group mb-2">
                                            <input type="number" class="form-control nuevoPorcentaje" min="0" placeholder="Porcentaje de ganancia" id="porcen" name="porcen" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i class="fas fa-percent"></i></span>
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
                window.location = 'productos';
            }
        });
    </script>
<?php endif; ?>

<!-- =============================
    MODAL EDITAR PRODUCTO 
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
                </div>  
            </div>      
        </section>
</div>



<script src="vista/dist/js/modulos-js/productos.js"></script>
