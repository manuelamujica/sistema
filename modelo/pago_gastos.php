<?php
//MODIFIQUE SETTER Y GETTER PERO FALTAN LOS ATRIBUTOS Y SIGO DESARROLLANDO PARA QUE PUEDA FUNCIONAR COMO LO HACIA ANTES CON LSO SETTER NORMALES
require_once 'conexion.php';
require_once 'validaciones.php';
class Pagos extends Conexion
{
  use ValidadorTrait;
  private $errores = [];
  private $conex;
  private $datos = [];


  public function __construct()
  {
    $this->conex = new Conexion();
    $this->conex = $this->conex->conectar();
  }

  //SETTER Y GETTER 
  public function setDatos(array $datos)
  {
    foreach ($datos as $key => $value) {
      switch ($key) {
        case 'cod_pago_emitido':
        //case 'tipo_pago':
        case 'cod_gasto':
        case 'cod_tipo_pago':
        case 'cod_vuelto_r':

          if (!is_numeric($value)) {
            $this->errores[] = "El campo $key debe ser numérico.";
          }
          break;

        case 'monto':
        case 'montopagado':
        case 'vuelto':
        case 'monto_vueltodet':
          if (!is_numeric($value) || $value < 0) {
            $this->errores[] = "El campo $key debe ser un número mayor o igual a 0.";
          }
          break;

        case 'fecha':
          if (!empty($value)) {
            $this->errores[] = "El campo $key debe estar lleno.";
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


  private function registrarPG()
  {
    $tipo_pago = "gasto";
    $sql = "INSERT INTO pago_emitido(tipo_pago,fecha,cod_gasto, monto_total) VALUES(:tipo_pago,:fecha,:cod_gasto,:monto_total)";
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':tipo_pago', $tipo_pago);
    $strExec->bindParam(':fecha', $this->datos['fecha']);
    $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
    $strExec->bindParam(':monto_total', $this->datos['monto']);
    $resp_pago_emitido = $strExec->execute();
    var_dump($resp_pago_emitido. " registro gasto");
    $cod_pago_emitido = $this->pagoemitidoUltimoR();
    if ($resp_pago_emitido) {
      foreach ($this->datos['pago'] as $pagos) {
        if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
          $this->datos['cod_tipo_pago'] = $pagos['cod_tipo_pago'];
          $this->datos['monto'] = $pagos['monto'];
          $registro = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pagoe,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
          $strExec = $this->conex->prepare($registro);
          $strExec->bindParam(':cod_pago_emitido', $cod_pago_emitido);
          $strExec->bindParam(':cod_tipo_pago', $this->datos['cod_tipo_pago']);
          $strExec->bindParam(':monto', $this->datos['monto']);
          $strExec->execute();

          $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_detalle_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
          $strExec = $this->conex->prepare($consultaRelacion);
          $strExec->bindParam(':cod_tipo_pago', $this->datos['cod_tipo_pago']);
          $strExec->execute();
          $relacion = $strExec->fetch(PDO::FETCH_ASSOC);
          var_dump("Pasamos el ciclo");

          if ($relacion) {
            if (!empty($relacion['cod_cuenta_bancaria'])) {
              var_dump("banco");
              $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo - :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
              $strExec = $this->conex->prepare($actualizarSaldoCuenta);
              $strExec->bindParam(':monto', $this->datos['monto']);
              $strExec->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
              $strExec->execute();
            } elseif (!empty($relacion['cod_detalle_caja'])) {
              var_dump("caja");
              $actualizarSaldoCaja = "UPDATE detalle_caja SET saldo = saldo - :monto WHERE cod_detalle_caja = :cod_detalle_caja";
              $strExec = $this->conex->prepare($actualizarSaldoCaja);
              $strExec->bindParam(':monto', $this->datos['monto']);
              $strExec->bindParam(':cod_detalle_caja', $relacion['cod_detalle_caja']);
              $strExec->execute();
            }
          }
        }
      }
      if ($this->datos['monto'] > $this->datos['montopagado']) {
        var_dump("pago parcial");
        $status = "UPDATE gasto SET detgasto_status= 2 WHERE cod_gasto=:cod_gasto";
        $strExec = $this->conex->prepare($status);
        $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
        $strExec->execute();
        $r = abs($this->datos['monto'] - $this->datos['montopagado']);
        return $r;
      } else if ($this->datos['monto'] <= $this->datos['montopagado']) {
        var_dump("pago completo + vuelto");
        if ($this->datos['vuelto'] > 0) {
          $sql = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)";
          $strExec = $this->conex->prepare($sql);
          $strExec->bindParam(':vuelto_total', $this->datos['vuelto']);
          $respuestaV = $strExec->execute();
          $vuelto = $this->conex->lastInsertId();
          $actualizar_gasto = "UPDATE pago_emitido SET cod_vuelto_r = :cod_vuelto_r WHERE cod_pago_emitido= :cod_pago_emitido";
          $strExec = $this->conex->prepare($actualizar_gasto);
          $strExec->bindParam(':cod_vuelto_r', $this->datos['cod_vuelto_r']);
          $strExec->bindParam(':cod_pago_emitido', $this->datos['cod_pago_emitido']);
          $strExec->execute();
          $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
          $strExec = $this->conex->prepare($status);
          $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
          $strExec->execute();
          return $r = 0;
        } else {
          var_dump("pago completo");
          $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
          $strExec = $this->conex->prepare($status);
          $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
          $strExec->execute();
          return $r = 0;
        }
      }
    }
  }
  public function registrarPgasto()
  {
    return $this->registrarPG();
  }

  private function RvueltoR()
  {
    $detvuelto = "INSERT INTO detalle_vuelto(cod_vuelto_r,cod_tipo_pago, monto) VALUES(:cod_vuelto_r,:cod_tipo_pago,:monto)";
    $strExec = $this->conex->prepare($detvuelto);
    $strExec->bindParam(':cod_vuelto_r', $this->datos['cod_vuelto_r']);
    $strExec->bindParam(':cod_tipo_pago', $this->datos['cod_tipo_pago']);
    $strExec->bindParam(':monto', $this->datos['monto']);
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

  private function cuotaP($pago) // continuo mañana}
  {
    foreach ($pago as $pagos) {
      if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
        $cod_tipo_pago = $pagos['cod_tipo_pago'];
        $montopagado = $pagos['monto'];
        $sql = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pago,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_pago_emitido', $this->datos['cod_pago_emitido']);
        $strExec->bindParam(':cod_tipo_pago', $this->datos['cod_tipo_pago']);
        $strExec->bindParam(':monto', $this->datos['montopagado']);
        $strExec->execute();
      }
    }
    if ($montopagado > $this->datos) {
      $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
      $strExec->execute();
      if ($this->datos['vuelto'] > 0) {
        $actualizar = "UPDATE vuelto_recibido SET vuelto_total = :vuelto_total WHERE cod_vuelto_r = :cod_vuelto_r";
        $strExec = $this->conex->prepare($actualizar);
        $strExec->bindParam(':cod_vuelto_r', $this->datos['cod_vuelto_r']);
        $strExec->bindParam(':vuelto_total', $this->datos['vuelto']);
        $resp = $strExec->execute();
        if (!$resp) {
          $actualizar = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)";
          $strExec = $this->conex->prepare($actualizar);
          $strExec->bindParam(':vuelto_total', $this->datos['vuelto']);
          $strExec->execute();
        }
      }
      return $res = 0;
    } else if ($montopagado < $this->datos['monto']) {
      $status = "UPDATE gasto SET detgasto_status = 2 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
      $strExec->execute();
      $res = abs($montopagado - $this->datos['monto']);
      return $res;
    } else {
      $status = "UPDATE gasto SET detgasto_status = 3 WHERE cod_gasto=:cod_gasto";
      $strExec = $this->conex->prepare($status);
      $strExec->bindParam(':cod_gasto', $this->datos['cod_gasto']);
      $strExec->execute();
      return $res = 0;
    }
  }

  public function registrarCuota($pago)
  {
    return $this->cuotaP($pago);
  }
}
