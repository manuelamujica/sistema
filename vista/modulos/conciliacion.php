<?php require_once "controlador/conciliacion.php"; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <br>
                    <h2>Conciliación bancaria</h2>
                </div>
            </div>
        </div>
    </section>

    <div style=" text-align-last: right;
    margin-right: 20px; margin-bottom: 20px">
    
      
<button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalHistorialConciliaciones">
    <i class="fas fa-history mr-1"></i> Historial de conciliaciones
</button>
</div>


<div class="modal fade" id="modalHistorialConciliaciones" tabindex="-1" role="dialog" aria-labelledby="modalHistorialConciliacionesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalHistorialConciliacionesLabel">Historial de Conciliaciones</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead class="thead-light">
              <tr>
                <th>Fecha</th>
                <th>Cuenta Bancaria</th>
                <th>Documento</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo estático -->
              <tr>
                <td>10-05-2025</td>
                <td>Banesco - 0134XXXXXXXX1234</td>
                <td><a href="uploads/conciliaciones/extracto_mayo_2025.pdf" target="_blank">Ver documento</a></td>
              </tr>
              <tr>
                <td>05-04-2025</td>
                <td>Mercantil - 0105XXXXXXXX5678</td>
                <td><a href="uploads/conciliaciones/extracto_abril_2025.pdf" target="_blank">Ver documento</a></td>
              </tr>
              <!-- Puedes repetir más filas aquí -->
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


  

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Subir extracto</h3>
                        </div>
                        <div class="card-body">
                            <form id="formSubirExtracto" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="pdfFile">Seleccionar archivo PDF</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="pdfFile" name="pdfFile" accept="application/pdf" required>
                                            <label class="custom-file-label" for="pdfFile">Buscar archivo...</label>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Solo se aceptan archivos en formato PDF</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="cuentaBancaria">Cuenta bancaria asociada</label>
                                    <select class="form-control" id="cuentaBancaria" name="cuentaBancaria" required>
                                        <option value="" selected disabled>Seleccione una cuenta</option>
                                        <?php foreach($cuentas as $cuenta): ?>
                                            <option value="<?= $cuenta['cod_cuenta_bancaria'] ?>">
                                                <?= $cuenta['nombre_banco'] ?> - <?= $cuenta['numero_cuenta'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer">
                            <button type="submit" form="formSubirExtracto" class="btn btn-primary">
                                <i class="fas fa-upload mr-2"></i> Subir extracto
                            </button>
                        </div>
                    </div>
                    
                    <?php if (!empty($transactions)): ?>
                    <div class="card card-success mt-4">
                        <div class="card-header">
                            <h3 class="card-title">Transacciones detectadas</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%">#</th>
                                            <th>Referencia</th>
                                            <th style="width: 20%">Monto</th>
                                            <th style="width: 15%">Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($transactions as $index => $transaction): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $transaction['Referencia'] ?></td>
                                            <td><?= number_format($transaction['Monto'], 2, ',', '.') ?></td>
                                            <td>
                                                <span class="badge <?= $transaction['Tipo'] == 'CREDITO' ? 'bg-success' : 'bg-danger' ?>">
                                                    <?= $transaction['Tipo'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success" id="btnConfirmarImportacion">
                                <i class="fas fa-check mr-2"></i> Confirmar importación
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>