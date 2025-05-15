<?php

require_once 'conexion.php';
require_once 'validaciones.php';
class Pagos extends Conexion
{
  use ValidadorTrait;
  private $errores = [];
  private $datos = [];
  private $cod_pago_emitido;
  private $cod_gasto;
  private $cod_compra;
  private $cod_tipo_pago;
  private $cod_vuelto_r;
  private $montototal;
  private $montopagado;
  private $vuelto;
  private $monto_vueltodet;
  private $fecha;
  private $status;
  private $tipo_pago;
  private $cod_caja;
  private $cod_cuenta_bancaria;
  private $monto_pagar;
  private $pago = [];

  public function __construct()
  {
    parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
  }

  //SETTER Y GETTER 
  public function setDatos(array $datos)
  {
    foreach ($datos as $key => $value) {
      switch ($key) {
        case 'cod_pago_emitido':
          if (is_numeric($value)) {
            $this->cod_pago_emitido = $value;
          } else {
            $this->errores[] = "El campo $key debe ser numérico.";
          }
          break;
        case 'cod_gasto':
          if (is_numeric($value)) {
            $this->cod_gasto = $value;
          } else {
            $this->errores[] = "El campo $key debe ser numérico.";
          }
          break;
        case 'cod_compra':
          if (is_numeric($value)) {
            $this->cod_compra = $value;
          } else {
            $this->errores[] = "El campo $key debe ser numérico.";
          }
          break;

        case 'cod_vuelto_r':
          if (is_numeric($value)) {
            $this->cod_vuelto_r = $value;
          }
          break;
        case 'montototal':
          if (!is_numeric($value) || $value >= 0) {
            $this->montototal = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;
        case 'monto_pagar':
          if (!is_numeric($value) || $value > 0) {
            $this->monto_pagar = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;
        case 'montopagado':
          if (!is_numeric($value) || $value > 0) {
            $this->montopagado = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;

        case 'vuelto':
          if (!is_numeric($value) || $value >= 0) {
            $this->vuelto = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;
        case 'monto_vueltodet':
          if (!is_numeric($value) || $value >= 0) {
            $this->monto_vueltodet = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;

        case 'fecha':
          if (!empty($value)) {
            $this->fecha = $value;
          } else {
            $this->errores[] = "El campo $key no puede estar vacío.";
          }
          break;

        case 'tipo_pago':
          $res = $this->validarTexto($value, $key, 2, 10);
          if ($res === true) {
            $this->tipo_pago = $value;
          } else {
            $this->errores[] = $res;
          }
          break;

        case 'pago':
          if (is_array($value)) {
            $this->pago = $value;
          } else {
            $this->errores[] = "El campo $key debe ser un arreglo.";
          }
          break;

        default:
          // Si no hay validación específica, simplemente asigna el valor
          break;
      }
      // Asignar el valor al arreglo dinámico
      $this->datos[$key] = $value;
    }
  }

  public function check()
  {
    if (!empty($this->errores)) {
      $mensajes = implode(" | ", $this->errores);
      throw new Exception("Errores de validación: $mensajes");
    }
  }

  public function getErrores()
  {
    return $this->errores;
  }

  public function consultar()
  {
    $registro = "SELECT
  tp.cod_tipo_pago,
  tp.cod_metodo,
  tp.tipo_moneda,
  t.medio_pago,
  cam2.tasa,
  cam1.tasa,
  d1.abreviatura,
  c.cod_cuenta_bancaria AS cod_cuenta_bancaria,
  COALESCE(c.cod_divisa, '') AS cod_divisa,
  COALESCE(cam1.cod_cambio, '') AS cod_cambio,
  COALESCE(d1.cod_divisa, '') AS divisa_cod,
  COALESCE(dc.cod_divisas, '') AS detcaja_cod,
  COALESCE(dc.cod_caja, '') AS detcaja_cod_caja,
  COALESCE(cam2.cod_cambio, '') AS cod_cambio_dtcaja,
  COALESCE(d2.cod_divisa, '') AS divisa_cod_dtcaja
FROM
  detalle_tipo_pago tp
LEFT JOIN
  tipo_pago t ON tp.cod_metodo = t.cod_metodo
LEFT JOIN
  cuenta_bancaria c ON tp.cod_cuenta_bancaria = c.cod_cuenta_bancaria
LEFT JOIN
  cambio_divisa cam1 ON c.cod_divisa = cam1.cod_cambio
LEFT JOIN
  divisas d1 ON cam1.cod_divisa = d1.cod_divisa
LEFT JOIN
  (
    SELECT
      dtcaja.cod_divisas,
      dtcaja.cod_caja
    FROM
      caja dtcaja
    GROUP BY
      dtcaja.cod_divisas,
      dtcaja.cod_caja
  ) dc ON tp.cod_caja = dc.cod_caja
LEFT JOIN
  cambio_divisa cam2 ON dc.cod_divisas = cam2.cod_cambio
LEFT JOIN
  divisas d2 ON cam2.cod_divisa = d2.cod_divisa
";
    parent::conectarBD();
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if ($resul) {
      return $datos;
    } else {
      return [];
    }
  }


  private function registrarPG() //LISTO COMPLETADO AL 100% NI LE MUEVAN JAJAJAJ (ES UNA OBRA DE ARTE) ME CUIDAN EL CÓDIGO JAJAJA (TODO FUNCIONAL NECESITO IMPLEMENTAR EN COMPRA 11/05/2025)
  {
    $cod_pago_emitido = 0;

    try {
      parent::conectarBD();
      $this->conex->beginTransaction();
    /*  var_dump($this->tipo_pago);
      var_dump($this->fecha);
      var_dump($this->cod_gasto);
      var_dump($this->montopagado."  ->monto total");
      var_dump($this->monto_pagar."  ->monto ya pagado");
      var_dump($this->vuelto);*/


      if ($this->tipo_pago == 'gasto') {
        $sql = "INSERT INTO pago_emitido(tipo_pago,fecha,cod_gasto, monto_total) VALUES(:tipo_pago,:fecha,:cod_gasto,:monto_total)";
        $gasto = $this->conex->prepare($sql);
        $gasto->bindParam(':tipo_pago', $this->tipo_pago);
        $gasto->bindParam(':fecha', $this->fecha);
        $gasto->bindParam(':cod_gasto', $this->cod_gasto);
        $gasto->bindParam(':monto_total', $this->montopagado);      
        if (!$gasto->execute()) {
          throw new Exception("Error al insertar en pago_emitido.");
          $resp_pago_emitido = false;
        }
        $cod_pago_emitido = $this->conex->lastInsertId();
        var_dump($cod_pago_emitido);
        $actual = "SELECT SUM(monto_total) AS montopago FROM pago_emitido WHERE cod_gasto = :cod_gasto";
        $actual = $this->conex->prepare($actual);
        $actual->bindParam(':cod_gasto', $this->cod_gasto);
        $res = $actual->execute();
        $resultado = $actual->fetch(PDO::FETCH_ASSOC);
        $montopg = $resultado['montopago'];
        $n = $montopg + $this->montopagado;
      } else {
        $sql = "INSERT INTO pago_emitido(tipo_pago,fecha,cod_compra, monto_total) VALUES(:tipo_pago,:fecha,:cod_gasto,:monto_total)";

        $compra = $this->conex->prepare($sql);
        $compra->bindParam(':tipo_pago', $this->tipo_pago);
        $compra->bindParam(':fecha', $this->fecha);
        $compra->bindParam(':cod_compra', $this->cod_compra);
        $compra->bindParam(':monto_total', $this->montopagado);
        if (!$compra->execute()) {
          throw new Exception("Error al insertar en pago_emitido.");
          $resp_pago_emitido = false;
        }
        $cod_pago_emitido = $this->conex->lastInsertId();
        $actualcom = "SELECT SUM(monto_total) AS montopago FROM pago_emitido WHERE cod_compra = :cod_compra";
        $actualcom = $this->conex->prepare($actualcom);
        $actualcom->bindParam(':cod_compra', $this->cod_compra);
        $res = $actualcom->execute();
        $resultado = $actualcom->fetch(PDO::FETCH_ASSOC);
        $montopc = $resultado['montopago'];
      }

      $resp_pago_emitido = true;
      

      if ($resp_pago_emitido) {
        foreach ($this->pago as $pagos) {
          if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
            var_dump($pagos['cod_tipo_pago']);
            $this->cod_tipo_pago = $pagos['cod_tipo_pago'];
            var_dump($this->montopagado);
            $registro = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pagoe,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
            $strExec = $this->conex->prepare($registro);
            $strExec->bindParam(':cod_pago_emitido', $cod_pago_emitido);
            $strExec->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
            $strExec->bindParam(':monto', $pagos['monto']);

            if (!$strExec->execute()) {
              throw new Exception("Error al insertar en detalle_pago_emitido.");
            }

            $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
            $consulta = $this->conex->prepare($consultaRelacion);
            $consulta->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
            if (!$consulta->execute()) {
              throw new Exception("Error al obtener la relación de tipo de pago.");
            }
            $relacion = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($relacion) {
              if (!empty($relacion['cod_cuenta_bancaria'])) {

                $this->cod_cuenta_bancaria = $relacion['cod_cuenta_bancaria'];

                $saldodisponible = $this->saldoBanco();
                if ((float)$saldodisponible < (float)$pagos['monto']) {
                  throw new Exception("Saldo insuficiente en la cuenta bancaria.");
                }
                $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo - :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
                $banco = $this->conex->prepare($actualizarSaldoCuenta);
                $banco->bindParam(':monto', $pagos['monto']);
                $banco->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
                $banco->execute();
              } else if (!empty($relacion['cod_caja'])) {
                $this->cod_caja = $relacion['cod_caja'];
                $saldodisponible = abs($this->saldoCaja());
                if ((float)$saldodisponible < (float)$pagos['monto']) {
                  throw new Exception("Saldo insuficiente en la caja.");
                }

                $actualizarSaldoCaja = "UPDATE caja SET saldo = saldo - :monto WHERE cod_caja = :cod_caja";
                $caja = $this->conex->prepare($actualizarSaldoCaja);
                $caja->bindParam(':monto', $pagos['monto']);
                $caja->bindParam(':cod_caja', $relacion['cod_caja']);
                $caja->execute();
              }
            }
          }
        }
        if ($this->tipo_pago == 'compra') {

          $montopc = $this->montopagado + $this->monto_pagar;
          var_dump($montopc);

          if ($this->montototal > $montopc) {
            $status = "UPDATE compra SET status = 2 WHERE cod_compra=:cod_compra";
            $editgasto = $this->conex->prepare($status);
            $editgasto->bindParam(':cod_compra', $this->cod_compra);
            if (!$editgasto->execute()) {
              throw new Exception("Error al actualizar el estado de la compra.");
            }

            $this->conex->commit();

            return $r = abs($this->montototal - $montopc);
          } else if ($this->montototal < $montopc) {
            if ($this->vuelto > 0) {

              $sql = "SELECT cod_vuelto_r FROM vuelto_recibido ORDER BY cod_vuelto_r DESC LIMIT 1";
              $consulta = $this->conex->prepare($sql);
              $consulta->execute();
              $vueltocod = $consulta->fetch(PDO::FETCH_ASSOC);

              $actualizargasto = "UPDATE pago_emitido SET cod_vuelto_r = :cod_vuelto_r WHERE cod_pago_emitido= :cod_pago_emitido";
              $insert = $this->conex->prepare($actualizargasto);
              $insert->bindParam(':cod_vuelto_r', $vueltocod);
              $insert->bindParam(':cod_pago_emitido', $cod_pago_emitido);
              if (!$insert->execute()) {
                throw new Exception("Error al actualizar el gasto con el vuelto.");
              }

              $status = "UPDATE compra SET status= 3 WHERE cod_compra=:cod_compra";
              $gastoxvuelto = $this->conex->prepare($status);
              $gastoxvuelto->bindParam(':cod_compra', $this->cod_compra);
              if (!$gastoxvuelto->execute()) {
                throw new Exception("Error al actualizar el estado de la compra.");
              }

              $this->conex->commit();

              return $r = 0;
            }
          } else if ($this->montototal == $montopc) {
            $status = "UPDATE compra SET status = 3 WHERE cod_compra=:cod_compra";
            $detgasto = $this->conex->prepare($status);
            $detgasto->bindParam(':cod_compra', $this->cod_gasto);
            if (!$detgasto->execute()) {
              throw new Exception("Error al actualizar el estado del gasto.");
            }
            $this->conex->commit();

            return $r = 0;
          }
        } else { //GASTOS
          $montopagado = (float)$this->montopagado;
          $monto_pagar = (float)$this->monto_pagar;
          $n = $montopagado + $monto_pagar; 
          if ($this->montototal > $n) {
            $status = "UPDATE gasto SET status = 2 WHERE cod_gasto=:cod_gasto";
            $editgasto = $this->conex->prepare($status);
            $editgasto->bindParam(':cod_gasto', $this->cod_gasto);
            if (!$editgasto->execute()) {
              throw new Exception("Error al actualizar el estado del gasto.");
            }

            $this->conex->commit();

            return $r = abs($this->montototal - $n);
          } else if ($this->montototal < $n) {
            if ($this->vuelto > 0) {

              $sql = "SELECT cod_vuelto_r FROM vuelto_recibido ORDER BY cod_vuelto_r DESC LIMIT 1";
              $consulta = $this->conex->prepare($sql);
              $consulta->execute();
              $vueltocod = $consulta->fetch(PDO::FETCH_ASSOC);
              var_dump($vueltocod['cod_vuelto_r']);

              $actualizargasto = "UPDATE pago_emitido SET cod_vuelto_r = :cod_vuelto_r WHERE cod_pago_emitido= :cod_pago_emitido";
              $insert = $this->conex->prepare($actualizargasto);
              $insert->bindParam(':cod_vuelto_r', $vueltocod['cod_vuelto_r']);
              $insert->bindParam(':cod_pago_emitido', $cod_pago_emitido);
              if (!$insert->execute()) {
                throw new Exception("Error al actualizar el gasto con el vuelto.");
              }

              $status = "UPDATE gasto SET status= 3 WHERE cod_gasto=:cod_gasto";
              $gastoxvuelto = $this->conex->prepare($status);
              $gastoxvuelto->bindParam(':cod_gasto', $this->cod_gasto);
              if (!$gastoxvuelto->execute()) {
                throw new Exception("Error al actualizar el estado del gasto.");
              }

              $this->conex->commit();

              return $r = 0;
            }
          } else if ($this->montototal == $n) {
            $status = "UPDATE gasto SET status = 3 WHERE cod_gasto=:cod_gasto";
            $detgasto = $this->conex->prepare($status);
            $detgasto->bindParam(':cod_gasto', $this->cod_gasto);
            if (!$detgasto->execute()) {
              throw new Exception("Error al actualizar el estado del gasto.");
            }
            $this->conex->commit();

            return $r = 0;
          }
        }
      }
    } catch (Exception $e) {
      error_log($e->getMessage());
      $this->conex->rollBack();
      $errores[] = throw new Exception("Error en la transacción: " . $e->getMessage());
    } finally {
      // Este bloque siempre se ejecuta
      parent::desconectarBD(); // // Se ejecuta antes de que el método termine SIEMPRE CERRARÁ LA CONEXIÓN
    }
  }
  public function registrarPgasto()
  {
    return $this->registrarPG();
  }

  private function vuelto() //FUNCIONA AL 100%
  {
    try {
      parent::conectarBD();
      $this->conex->beginTransaction();
      $sql = "INSERT INTO vuelto_recibido(vuelto_total) VALUES(:vuelto_total)";
      $strExec = $this->conex->prepare($sql);
      $strExec->bindParam(':vuelto_total', $this->vuelto);
      if (!$strExec->execute()) {
        throw new Exception("Error al insertar en vuelto_recibido.");
      }
      $res = 1;
      $vueltocod = $this->conex->lastInsertId();
      foreach ($this->pago as $pagos) {
        if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
          $this->cod_tipo_pago = $pagos['cod_tipo_pago'];
          $detvuelto = "INSERT INTO detalle_vueltor(cod_vuelto_r,cod_tipo_pago, monto) VALUES(:cod_vuelto_r,:cod_tipo_pago,:monto)";
          $strExec = $this->conex->prepare($detvuelto);
          $strExec->bindParam(':cod_vuelto_r', $vueltocod);
          $strExec->bindParam(':cod_tipo_pago', $pagos['cod_tipo_pago']);
          $strExec->bindParam(':monto', $pagos['monto']);
          if (!$strExec->execute()) {
            throw new Exception("Error al insertar en detalle_vuelto.");
          } 
          $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
          $consulta = $this->conex->prepare($consultaRelacion);
          $consulta->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
          if (!$consulta->execute()) {
            throw new Exception("Error al obtener la relación de tipo de pago.");
          }
          $relacion = $consulta->fetch(PDO::FETCH_ASSOC);

          if ($relacion) {
            if (!empty($relacion['cod_cuenta_bancaria'])) {

              $this->cod_cuenta_bancaria = $relacion['cod_cuenta_bancaria'];

              $saldodisponible = $this->saldoBanco();
              if ((float)$saldodisponible < (float)$pagos['monto']) {
                throw new Exception("Saldo insuficiente en la cuenta bancaria.");
              }
              $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo + :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
              $banco = $this->conex->prepare($actualizarSaldoCuenta);
              $banco->bindParam(':monto', $pagos['monto']);
              $banco->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
              $banco->execute();
            } else if (!empty($relacion['cod_caja'])) {
              $this->cod_caja = $relacion['cod_caja'];
              $saldodisponible = abs($this->saldoCaja());
              if ((float)$saldodisponible < (float)$pagos['monto']) {
                throw new Exception("Saldo insuficiente en la caja.");
              }

              $actualizarSaldoCaja = "UPDATE caja SET saldo = saldo + :monto WHERE cod_caja = :cod_caja";
              $caja = $this->conex->prepare($actualizarSaldoCaja);
              $caja->bindParam(':monto', $pagos['monto']);
              $caja->bindParam(':cod_caja', $relacion['cod_caja']);
              $caja->execute();
            }
          }
        }
      }
      $this->conex->commit();
      return $res;
    } catch (Exception $e) {
      $this->conex->rollBack();
      throw new Exception("Error al registrar vuelto: " . $e->getMessage());
    } finally {
      parent::desconectarBD();
    }
  }


  public function v()
  {
    return $this->vuelto();
  }

  private function saldoBanco()
  {
    $sql = "SELECT saldo FROM cuenta_bancaria WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";

    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_cuenta_bancaria', $this->cod_cuenta_bancaria);
    $res = $strExec->execute();
    $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

    if ($res) {
      return $resultado['saldo'];
    } else {
      return [];
    }
  }
  private function saldoCaja()
  {
    $sql = "SELECT saldo FROM caja WHERE cod_caja = :cod_caja";

    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_caja', $this->cod_caja);
    $res = $strExec->execute();
    $resultado = $strExec->fetch(PDO::FETCH_ASSOC);

    if ($res && $resultado) {
      return (float) $resultado['saldo'];
    } else {
      return 0;
    }
  }
  public function Scaja()
  {
    return $this->saldoCaja();
  }
  public function Sbanco()
  {
    return $this->saldoBanco();
  }

  private function gastos()
  {
    $sql = "SELECT monto_total, cod_pago_emitido, cod_gasto FROM pago_emitido WHERE cod_gasto = :cod_gasto";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_gasto', $this->cod_gasto);
    $res = $strExec->execute();
    $resultado = $strExec->fetch(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if ($res) {
      return $resultado;
    } else {
      return [];
    }
  }
  public function getGastos()
  {
    return $this->gastos();
  }
  public function getDatos()
  {
    return $this->datos;
  }
}
