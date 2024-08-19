<!-- MODULO DE VENTA / SOLO VISTA -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Ventas</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Formulario y tabla de ventas -->
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header" style="background: #db6a00 ;color: #ffffff; ">
                        
                            <h3 class="card-title">Productos</h3>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="cliente">Cliente</label>
                                    <input type="text" class="form-control form-control-sm" id="cliente" placeholder="Nombre del cliente">
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-sm" placeholder="Ingrese el nombre del producto">
                                </div>
                            </form>
                            <div class="total-venta">Total Venta: S/ 8.73</div>
                            <table class="table table-bordered table-hover table-sm">
                                <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <!-- Ejemplo de fila -->
                                <tr>
                                    <td>0005</td>
                                    <td>Queso Duro</td>
                                    <td>0.500kg</td>
                                    <td>S/ 4.5</td>
                                    <td>S/ 2.25</td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-action"><i class="fas fa-shopping-cart"></i></button>
                                        <button class="btn btn-danger btn-sm btn-action"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Más filas de ejemplo aquí -->
                                <tr>
                                    <td>0009</td>
                                    <td>Jamon de espalda alibal</td>
                                    <td>0.250kg</td>
                                    <td>S/ 7</td>
                                    <td>S/ 1.75</td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-action"><i class="fas fa-shopping-cart"></i></button>
                                        <button class="btn btn-danger btn-sm btn-action"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>0012</td>
                                    <td>Queso amarillo</td>
                                    <td>0.310kg</td>
                                    <td>S/ 12.4</td>
                                    <td>S/ 3.84</td>
                                    <td>
                                        <button class="btn btn-success btn-sm btn-action"><i class="fas fa-shopping-cart"></i></button>
                                        <button class="btn btn-danger btn-sm btn-action"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-primary btn-sm">Realizar Venta</button>
                            <button class="btn btn-danger btn-sm">Vaciar Listado</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header" style="background-color: #db6a00 ; color:#ffffff">
                            <h3 class="card-title">Resumen de Venta</h3>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label for="cliente-resumen">Cliente</label>
                                    <input type="text" class="form-control form-control-sm" id="cliente-resumen" value="" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="documento">Documento</label>
                                    <select class="form-control form-control-sm" id="documento">
                                        <option>Boleta</option>
                                        <option>Factura</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <label for="serie">Serie</label>
                                        <input type="text" class="form-control form-control-sm" id="serie" value="001" readonly>
                                    </div>
                                    <div class="col">
                                        <label for="numero">Nro Venta</label>
                                        <input type="text" class="form-control form-control-sm" id="numero" value="0000005" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tipo-pago">Tipo Pago</label>
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
                                        <label class="form-check-label" for="efectivo-exacto">
                                            Efectivo Exacto
                                        </label>
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
                        </div>
                        <div class="card-footer text-right">
                            <div class="total-venta">
                                <p>Subtotal: S/ 7.84</p>
                                <p>Excento: S/ 2.25</p>
                                <p>Base imponible: S/ 5.59</p>
                                <p>IVA (16%): S/ 0.89</p>
                                <p>TOTAL: S/ 8.73</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Copiar el valor del cliente al resumen
    document.getElementById("cliente").addEventListener("input", function() {
        document.getElementById("cliente-resumen").value = this.value;
    });
});
</script>

