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
                    <li class="breadcrumb-item active">Venta</li>
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
                            <!-- Botón para abrir el modal -->
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ventaModal"> Ver Ventas </button>
                             
                            <!-- Botones PDF y Excel 
                            <div class="card-tools">
                                <div class="float-right">
                                    <button type="button" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Excel
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </button>
                                </div>
                            </div>-->
                        </div>
                    <div class="card-body">
                    <div class="row mb-2 ">
                    
                    <!-- Tabla de productos -->
                    <div class="table-responsive">
                    <table id="producto" class="table table-bordered table-striped datatable" style="width: 100%;">
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
                                    <td>2024-04-6 12:05:32</td>
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
                                    <td>2023-12-18 12:05:32</td>
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
        

<!-- Modal de Venta con búsqueda interactiva -->
<div class="modal fade" id="ventaModal" tabindex="-1" aria-labelledby="ventaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                <h5 class="modal-title" id="ventaModalLabel">Registrar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Información del Cliente y Nro de Venta -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="nombre-cliente">Nombre del Cliente</label>
                                <input type="text" class="form-control form-control-sm" id="nombre-cliente" placeholder="Nombre del cliente">
                            </div>
                            <div class="form-group">
                                <label for="cedula-rif">Cédula/RIF</label>
                                <input type="text" class="form-control form-control-sm" id="cedula-rif" placeholder="Cédula o RIF">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero">Nro Venta</label>
                                <input type="text" class="form-control form-control-sm" id="numero" value="0000005" readonly>
                            </div>
                        </div>
                    </div>

                        <!-- Tabla para agregar productos -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="ventaProductosBody">
                                    <!-- Se generan dinámicamente filas de productos -->
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="codigo1" placeholder="Código del producto">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="producto1" placeholder="Nombre del producto">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="cantidad1" value="1" min="1" onchange="calcularTotal(1)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="precio1" placeholder="Precio" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total1" placeholder="Total" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="codigo2" placeholder="Código del producto">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="producto2" placeholder="Nombre del producto">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="cantidad2" value="1" min="1" onchange="calcularTotal(2)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="precio2" placeholder="Precio" >
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total2" placeholder="Total" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="codigo3" placeholder="Código del producto">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="producto3" placeholder="Nombre del producto">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="cantidad3" value="1" min="1" onchange="calcularTotal(3)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="precio3" placeholder="Precio" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total3" placeholder="Total" readonly>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" id="codigo4" placeholder="Código del producto">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="producto4" placeholder="Nombre del producto">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="mostrarProductos()">+</button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="cantidad4" value="1" min="1" onchange="calcularTotal(4)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="precio4" placeholder="Precio" readonly>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" id="total4" placeholder="Total" readonly>
                                        </td>
                                    </tr>
                                    <!-- Más filas pueden ser generadas aquí -->
                                </tbody>
                            </table>
                        </div>
                        <!-- Botón para agregar más filas de productos -->
                        <button type="button" class="btn btn-success" onclick="agregarFila()">Agregar Producto</button>
                        <div class="card card-outline card-primary float-right" style="width: 300px;">
                            <div class="card-body">
                                <div class="total-venta text-right">
                                    <p>Subtotal: S/ <span id="subtotal" class="text-bold">0.00</span></p>
                                    <p>Exento: S/ <span id="exento" class="text-bold">0.00</span></p>
                                    <p>Base imponible: S/ <span id="base-imponible" class="text-bold">0.00</span></p>
                                    <p>IVA (16%): S/ <span id="iva" class="text-bold">0.00</span></p>
                                    <p class="text-bold">TOTAL: S/ <span id="total-general" class="text-bold">0.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="realizarVentaBtn">Realizar Venta</button>
                </div>
            </div>
        </div>
    </div>

        <!-- Modal de Resumen de Venta (este modal se abre desde el modal de "Ver Venta") -->
        <div class="modal fade" id="resumenVentaModal" tabindex="-1" aria-labelledby="resumenVentaLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background: #db6a00; color: #ffffff;">
                        <h5 class="modal-title" id="resumenVentaLabel">Resumen de Venta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="resumenVentaForm">
                            
                            <div class="form-group">
                                <label for="tipo-pago">Tipo de Pago</label>
                                <select class="form-control form-control-sm" id="tipo-pago">
                                    <option>Efectivo</option>
                                    <option>Tarjeta</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="efectivo">Efectivo recibido</label>
                                <input type="text" class="form-control form-control-sm" id="efectivo">
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="efectivo-exacto">
                                    <label class="form-check-label" for="efectivo-exacto">Efectivo Exacto</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="monto-efectivo">Monto Efectivo</label>
                                <input type="text" class="form-control form-control-sm" id="monto-efectivo" value="S/ 8.73" readonly>
                            </div>
                            <div class="form-group">
                                <label for="vuelto">Vuelto</label>
                                <input type="text" class="form-control form-control-sm" id="vuelto" value="S/ 0.00" readonly>
                            </div>
                        </form>
                        <div class="total-venta">
                            <p>Subtotal: S/ 7.84</p>
                            <p>Exento: S/ 2.25</p>
                            <p>Base imponible: S/ 5.59</p>
                            <p>IVA (16%): S/ 0.89</p>
                            <p><strong>TOTAL: S/ 8.73</strong></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-success" id="finalizarPagoBtn">Finalizar Pago</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>

<script src="vista/dist/js/modulos-js/ventas.js"> </script>
