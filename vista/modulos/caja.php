
<?php require_once 'controlador/caja.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Gestión de Cajas</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalAbrirCaja">
                                <i class="fas fa-plus"></i> Abrir Caja
                            </button>
                        </div>
                        <div class="card-body">
                            <table id="tablaCajas" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Usuario</th>
                                        <th>Divisa</th>
                                        <th>Fecha Apertura</th>
                                        <th>Fecha Cierre</th>
                                        <th>Monto Apertura</th>
                                        <th>Monto Cierre</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($registro as $caja): ?>
                                    <tr>
                                        <td><?= $caja['cod_caja'] ?></td>
                                        <td><?= $caja['nombre'] ?></td>
                                        <td><?= $caja['nombre_usuario'] ?></td>
                                        <td><?= $caja['abreviatura'] ?></td>
                                        <td><?= $caja['fecha_apertura'] ?></td>
                                        <td><?= $caja['fecha_cierre'] ?? '--' ?></td>
                                        <td><?= number_format($caja['monto_apertura'], 2) ?></td>
                                        <td><?= $caja['monto_cierre'] ? number_format($caja['monto_cierre'], 2) : '--' ?></td>
                                        <td>
                                            <span class="badge badge-<?= $caja['estado'] == 'abierta' ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($caja['estado']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm ver-detalle" data-id="<?= $caja['cod_caja'] ?>">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm editar-caja" 
                                                data-id="<?= $caja['cod_caja'] ?>"
                                                data-nombre="<?= $caja['nombre'] ?>"
                                                data-divisa="<?= $caja['cod_divisa'] ?>"
                                                data-monto="<?= $caja['monto_apertura'] ?>"
                                                data-estado="<?= $caja['estado'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm eliminar-caja" data-id="<?= $caja['cod_caja'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Abrir Caja -->
<div class="modal fade" id="modalAbrirCaja">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Abrir Nueva Caja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAbrirCaja" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre de la Caja</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Divisa</label>
                        <select name="cod_divisa" class="form-control" required>
                            <?php foreach ($divisas as $divisa): ?>
                            <option value="<?= $divisa['cod_divisa'] ?>"><?= $divisa['nombre'] ?> (<?= $divisa['abreviatura'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Fecha Apertura</label>
                        <input type="datetime-local" name="fecha_apertura" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Monto Apertura</label>
                        <input type="number" step="0.01" name="monto_apertura" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="abrir" class="btn btn-primary">Abrir Caja</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Caja -->
<div class="modal fade" id="modalEditarCaja">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Caja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarCaja" method="post">
                <input type="hidden" name="cod_caja">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre de la Caja</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Divisa</label>
                        <select name="cod_divisa" class="form-control" required>
                            <?php foreach ($divisas as $divisa): ?>
                            <option value="<?= $divisa['cod_divisa'] ?>"><?= $divisa['nombre'] ?> (<?= $divisa['abreviatura'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Monto Apertura</label>
                        <input type="number" step="0.01" name="monto_apertura" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="estado" class="form-control" required>
                            <option value="abierta">Abierta</option>
                            <option value="cerrada">Cerrada</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="editar" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Caja -->
<div class="modal fade" id="modalEliminarCaja">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Eliminar Caja</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEliminarCaja" method="post">
                <input type="hidden" name="cod_caja">
                <div class="modal-body">
                    <p>¿Está seguro de eliminar esta caja? Todos los movimientos asociados también serán eliminados.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" name="eliminar" class="btn btn-danger">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ver Detalles -->
<div class="modal fade" id="modalDetalleCaja">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Movimientos de Caja <span id="tituloCaja"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaDetalle">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Aquí se cargarán los detalles via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Configurar DataTable
    $('#tablaCajas').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });

    // Configurar fecha actual en modal abrir caja
    $('#modalAbrirCaja').on('show.bs.modal', function() {
        let now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        $('[name="fecha_apertura"]').val(now.toISOString().slice(0, 16));
    });

    // Editar caja
    $('.editar-caja').click(function() {
        let id = $(this).data('id');
        let nombre = $(this).data('nombre');
        let divisa = $(this).data('divisa');
        let monto = $(this).data('monto');
        let estado = $(this).data('estado');
        
        $('#formEditarCaja [name="cod_caja"]').val(id);
        $('#formEditarCaja [name="nombre"]').val(nombre);
        $('#formEditarCaja [name="cod_divisa"]').val(divisa);
        $('#formEditarCaja [name="monto_apertura"]').val(monto);
        $('#formEditarCaja [name="estado"]').val(estado);
        
        $('#modalEditarCaja').modal('show');
    });

    // Eliminar caja
    $('.eliminar-caja').click(function() {
        let id = $(this).data('id');
        $('#formEliminarCaja [name="cod_caja"]').val(id);
        $('#modalEliminarCaja').modal('show');
    });

    // Ver detalles
    $('.ver-detalle').click(function() {
        let id = $(this).data('id');
        $('#tituloCaja').text('#' + id);
        
        $.post('caja', {detalle_caja: id}, function(response) {
            let detalles = JSON.parse(response);
            let tabla = $('#tablaDetalle tbody');
            tabla.empty();
            
            if (detalles.length > 0) {
                detalles.forEach(function(movimiento) {
                    let fila = $('<tr>');
                    fila.append($('<td>').text(movimiento.fecha));
                    fila.append($('<td>').text(movimiento.descripcion));
                    fila.append($('<td>').html(
                        movimiento.tipo === 'ingreso' ? 
                        '<span class="badge badge-success">Ingreso</span>' : 
                        '<span class="badge badge-danger">Egreso</span>'
                    ));
                    fila.append($('<td>').text(parseFloat(movimiento.monto).toFixed(2)));
                    tabla.append(fila);
                });
            } else {
                tabla.append('<tr><td colspan="4" class="text-center">No hay movimientos registrados</td></tr>');
            }
            
            $('#modalDetalleCaja').modal('show');
        });
    });
});
</script>