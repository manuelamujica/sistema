<?php
require_once 'conexion.php';
require_once 'validaciones.php';
class Pagos extends Conexion
{
  use ValidadorTrait;
  private $errores = [];
  private $conex;
  private $cod_pago_emitido;
  private $tipo_pago;
  private $cod_vuelto_r;
  private $fecha;
  private $cod_gasto;
  private $montopagado;
  private $monto;
  private $vuelto;
  private $cod_tipo_pago;
  private $monto_vueltodet;


  public function __construct()
  {
    $this->conex = new Conexion();
    $this->conex = $this->conex->conectar();
  }

  //SETTER Y GETTER DE PAGOS DE GASTOS
  public function set_cod_pago_emitido($cod_pago_emitido)
  {
    $this->cod_pago_emitido = $cod_pago_emitido;
  }
  public function get_cod_pago_emitido()
  {
    return $this->cod_pago_emitido;
  }
  public function set_tipo_pago($tipo_pago)
  {
    $this->tipo_pago = $tipo_pago;
  }
  public function get_tipo_pago()
  {
    return $this->tipo_pago;
  }
  public function set_cod_vuelto_r($cod_vuelto_r)
  {
    $this->cod_vuelto_r = $cod_vuelto_r;
  }
  public function get_cod_vuelto_r()
  {
    return $this->cod_vuelto_r;
  }
  public function set_fecha($fecha)
  {
    $this->fecha = $fecha;
  }
  public function get_fecha()
  {
    return $this->fecha;
  }
  public function set_cod_gasto($cod_gasto)
  {
    $this->cod_gasto = $cod_gasto;
  }
  public function get_cod_gasto()
  {
    return $this->cod_gasto;
  }
  public function set_monto($monto)
  {
    $this->monto = $monto;
  }
  public function get_monto()
  {
    return $this->monto;
  }
  public function set_montopagado($montopagado)
  {
    $this->montopagado = $montopagado;
  }
  public function get_montopagado()
  {
    return $this->montopagado;
  }

  public function set_vuelto($vuelto)
  {

    $this->vuelto = $vuelto;
  }
  public function get_vuelto()
  {
    return $this->vuelto;
  }

  public function set_cod_tipo_pago($cod_tipo_pago)
  {
    $this->cod_tipo_pago = $cod_tipo_pago;
  }
  public function get_cod_tipo_pago()
  {
    return $this->cod_tipo_pago;
  }
  public function set_monto_vueltodet($monto_vueltodet)
  {
      $this->monto_vueltodet = $monto_vueltodet;
  }
  public function get_monto_vueltodet()
  {
    return $this->monto_vueltodet;
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
  dc.cod_detalle_caja AS cod_detalle_caja,
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
      dtcaja.cod_detalle_caja,
      dtcaja.cod_divisas,
      dtcaja.cod_caja
    FROM
      detalle_caja dtcaja
    GROUP BY
      dtcaja.cod_detalle_caja,
      dtcaja.cod_divisas,
      dtcaja.cod_caja
  ) dc ON tp.cod_detalle_caja = dc.cod_detalle_caja
LEFT JOIN
  cambio_divisa cam2 ON dc.cod_divisas = cam2.cod_cambio
LEFT JOIN
  divisas d2 ON cam2.cod_divisa = d2.cod_divisa
";
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    if ($resul) {
      return $datos;
    } else {
      return [];
    }
  }


  private function registrarPG($pago, $monto) 
  {
    $this->tipo_pago = "gasto";
    $sql = "INSERT INTO pago_emitido(tipo_pago,fecha,cod_gasto, monto_total) VALUES(:tipo_pago,:fecha,:cod_gasto,:monto_total)";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':tipo_pago', $this->tipo_pago);
    $strExec->bindParam(':fecha', $this->fecha);
    $strExec->bindParam(':cod_gasto', $this->cod_gasto);
    $strExec->bindParam(':monto_total', $this->monto);
    $resp_pago_emitido = $strExec->execute();
    $cod_pago_emitido = $this->pagoemitidoUltimoR();
    if ($resp_pago_emitido) {
      foreach ($pago as $pagos) {
        if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
          $this->cod_tipo_pago = $pagos['cod_tipo_pago'];
          $this->monto = $pagos['monto'];
          $registro = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pagoe,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
          $strExec = $this->conex->prepare($registro);
          $strExec->bindParam(':cod_pago_emitido', $cod_pago_emitido);
          $strExec->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
          $strExec->bindParam(':monto', $this->monto);
          $strExec->execute();

          $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_detalle_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
          $strExec = $this->conex->prepare($consultaRelacion);
          $strExec->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
          $strExec->execute();
          $relacion = $strExec->fetch(PDO::FETCH_ASSOC);

          if ($relacion) {
            if (!empty($relacion['cod_cuenta_bancaria'])) {

              $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo - :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
              $strExec = $this->conex->prepare($actualizarSaldoCuenta);
              $strExec->bindParam(':monto', $this->monto);
              $strExec->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
              $strExec->execute();
            } elseif (!empty($relacion['cod_detalle_caja'])) {
              $actualizarSaldoCaja = "UPDATE detalle_caja SET saldo = saldo - :monto WHERE cod_detalle_caja = :cod_detalle_caja";
              $strExec = $this->conex->prepare($actualizarSaldoCaja);
              $strExec->bindParam(':monto', $this->monto);
              $strExec->bindParam(':cod_detalle_caja', $relacion['cod_detalle_caja']);
              $strExec->execute();
            }
          }
        }
      }
      if ($monto > $this->montopagado) {
        $status = "UPDATE gasto SET detgasto_status= 2 WHERE cod_gasto=:cod_gasto";
        $strExec = $this->conex->prepare($status);
        $strExec->bindParam(':cod_gasto', $this->cod_gasto);
        $strExec->execute();
        $r = abs($monto - $this->montopagado);
        return $r;
      } else if ($monto <= $this->montopagado) {
        if ($this->vuelto > 0) {
          $sql = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)"; 
          $strExec = $this->conex->prepare($sql);
          $strExec->bindParam(':vuelto_total', $this->vuelto);
          $respuestaV = $strExec->execute();
          $vuelto = $this->conex->lastInsertId();
          $actualizar_gasto = "UPDATE pago_emitido SET cod_vuelto_r = :cod_vuelto_r WHERE cod_pago_emitido= :cod_pago_emitido";
          $strExec = $this->conex->prepare($actualizar_gasto);
          $strExec->bindParam(':cod_vuelto_r', $vuelto);
          $strExec->bindParam(':cod_pago_emitido', $cod_pago_emitido);
          $strExec->execute();
          $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
          $strExec = $this->conex->prepare($status);
          $strExec->bindParam(':cod_gasto', $this->cod_gasto);
          $strExec->execute();
          return $r = 0;
        } else {
          $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
          $strExec = $this->conex->prepare($status);
          $strExec->bindParam(':cod_gasto', $this->cod_gasto);
          $strExec->execute();
          return $r = 0;
        }
      }
    }
  }
  public function registrarPgasto($pago, $monto)
  {
    return $this->registrarPG($pago, $monto);
  }

  private function RvueltoR()
  {
    $detvuelto = "INSERT INTO detalle_vuelto(cod_vuelto_r,cod_tipo_pago, monto) VALUES(:cod_vuelto_r,:cod_tipo_pago,:monto)";
    $strExec = $this->conex->prepare($detvuelto);
    $strExec->bindParam(':cod_vuelto_r', $this->cod_vuelto_r);
    $strExec->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
    $strExec->bindParam(':monto', $this->monto);
    $respuesta = $strExec->execute();
  }

  public function vuelto()
  {
    return $this->RvueltoR();
  }
  private function pagoemitidoUltimoR()
  {
    $sql = "SELECT MAX(cod_pago_emitido) as ultimo FROM pago_emitido";
    $strExec = $this->conex->prepare($sql);
    $resul = $strExec->execute();
    if ($resul == 1) {
      $r = $strExec->fetch(PDO::FETCH_ASSOC);
      return $r['ultimo'];
    } else {
      return $r = 0;
    }
  }

  private function cuotaP($pago)
  {
    foreach ($pago as $pagos) {
      if (!empty($pagos['monto']) && $pagos['monto'] > 0) { 
        $cod_tipo_pago = $pagos['cod_tipo_pago'];
        $montopagado = $pagos['monto'];
        $sql = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pago,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_pago_emitido', $this->cod_pago_emitido);
        $strExec->bindParam(':cod_tipo_pago', $cod_tipo_pago);
        $strExec->bindParam(':monto', $montopagado);
        $strExec->execute();
      }
    }
    if ($montopagado > $this->monto) { 
      $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->cod_gasto);
      $strExec->execute();
      if ($this->vuelto > 0) {
        $actualizar = "UPDATE vuelto_recibido SET vuelto_total = :vuelto_total WHERE cod_vuelto_r = :cod_vuelto_r";
        $strExec = $this->conex->prepare($actualizar);
        $strExec->bindParam(':cod_vuelto_r', $this->cod_vuelto_r);
        $strExec->bindParam(':vuelto_total', $this->vuelto);
        $resp = $strExec->execute();
        if (!$resp) {
          $actualizar = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)";
          $strExec = $this->conex->prepare($actualizar);
          $strExec->bindParam(':vuelto_total', $this->vuelto);
          $strExec->execute();
        }
      }
      return $res = 0;
    } else if ($montopagado < $this->monto) {
      $status = "UPDATE gasto SET detgasto_status = 2 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->cod_gasto);
      $strExec->execute();
      $res = abs($montopagado - $this->monto);
      return $res;
    } else {
      $status = "UPDATE gasto SET detgasto_status = 3 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->cod_gasto);
      $strExec->execute();
      return $res = 0;
    }
  }

  public function registrarCuota($pago)
  {
    return $this->cuotaP($pago);
  }
}
