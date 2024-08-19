<!-- MÓDULO TRANSACCIONAL, SOLO VISTA-->
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
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalRegistrarProducto">Registrar producto</button>
                            <!-- Botones PDF y Excel -->
                            <div class="card-tools">
                                <div class="float-right">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
            <div class="card-body">
                <!-- Barra de búsqueda -->
                <div class="input-group input-group-sm">
                                <input type="text" name="table_search" class="form-control float-right" placeholder="Buscar">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <br>
            
            <!-- Tabla de productos -->
            <div class="table-responsive">
            <table id="producto" class="table table-bordered table-striped">
                <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Marca</th>
                            <th>Categoría</th>
                            <th>Stock</th>
                            <th>Costo</th>
                            <th>Precio de venta</th>
                            <th>Fecha de creación</th>
                            <th>IVA</th>
                            <th>Acciones</th>
                        </tr> 
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Jamón de espalda</td>
                            <td>Purolomo</td>
                            <td>Charcutería</td>
                            <td>20</td>
                            <td>$ 8.00</td>
                            <td>$ 10.00</td>
                            <td>2024-09-06 12:05:32</td>
                            <td>E</td>
                            <td>
                                <form method="post">
                                    <button name="editar" class="btn btn-primary btn-sm editar" title="Editar" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button name="eliminar" class="btn btn-danger btn-sm eliminar" title="Anular" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Queso blanco de res</td>
                            <td></td>
                            <td>Lácteos</td>
                            <td>12</td>
                            <td>$ 5.00</td>
                            <td>$ 10.00</td>
                            <td>2024-09-06 12:05:32</td>
                            <td>G</td>
                            <td>
                                <form method="post">
                                    <button name="editar" class="btn btn-primary btn-sm editar" title="Editar" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button name="eliminar" class="btn btn-danger btn-sm eliminar" title="Anular" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Harina de maíz</td>
                            <td>PAN</td>
                            <td>Víveres</td>
                            <td>3</td>
                            <td>$ 6.00</td>
                            <td>$ 10.00</td>
                            <td>2024-09-06 12:05:32</td>
                            <td>E</td>
                            <td>
                                <form method="post">
                                    <button name="editar" class="btn btn-primary btn-sm editar" title="Editar" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-pencil-alt"></i></button>
                                    <button name="eliminar" class="btn btn-danger btn-sm eliminar" title="Anular" value="<?php echo $dato["nombre"] ?>"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    </div>  
                </tbody>
            </table>
            </div>
        </div>
    </div>
</section>
</div>