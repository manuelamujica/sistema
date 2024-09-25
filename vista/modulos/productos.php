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
                                        <!--<th>Categoría</th>-->
                                        <th>Stock</th>
                                        <th>Costo</th>
                                        <th>IVA</th>
                                        <th>Precio de venta</th>
                                        <!--<th>Fecha de creación</th>-->
                                        <th>Status</th>
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
                                            <td> <?php echo $producto["codigo"] ?></td>
                                            <td> <?php echo $producto["nombre"] ?></td>
                                            <td> <?php echo $producto["marca"] ?></td>
                                            <td> <?php echo $producto["nombre"] ?></td>
                                            <td>
                                                <?php if ($producto['status']==1):?>
                                                    <span class="badge bg-success">Activo</span>
                                                <?php else:?>
                                                    <span class="badge bg-danger">Inactivo</span>
                                                <?php endif;?>
                                            </td>

                                            <!-- Botones -->
                                            <td>
                                                <button name="editar" title="Editar" class="btn btn-primary btn-sm editar" data-toggle="modal" data-target="#editModal"
                                                data-codigo="<?php echo $producto["cod_categoria"]; ?>"
                                                data-nombre="<?php echo $producto["nombre"]; ?>"
                                                
                                                data-status="<?php echo $producto["status"]; ?>">
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
                                        <div class="form-group">
                                            <label for="nombre">Nombre del producto</label>
                                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingresa el nombre del producto" required>
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
            </div>  
        </div>      
    </section>
</div>