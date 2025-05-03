<?php
//MODIFIQUE SETTER Y GETTER PERO FALTAN LOS ATRIBUTOS Y SIGO DESARROLLANDO PARA QUE PUEDA FUNCIONAR COMO LO HACIA ANTES CON LOS SETTER NORMALES
require_once 'conexion.php';
require_once 'validaciones.php';
class Pagos extends Conexion
{
  use ValidadorTrait;
  private $errores = [];
  private $conex;
  private $datos = [];
  private $cod_pago_emitido;
  private $cod_gasto;
  private $cod_tipo_pago;
  private $cod_vuelto_r;
  private $montototal;
  private $montopagado;
  private $vuelto;
  private $monto_vueltodet;
  private $fecha;
  private $status;
  private $pago = [];

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

        case 'cod_vuelto_r':
          if (is_numeric($value)) {
            $this->cod_vuelto_r = $value;
          } else {
            $this->errores[] = "El campo $key debe ser numérico.";
          }
          break;

        case 'montototal':
          if (!is_numeric($value) || $value >= 0) {
            $this->montototal = $value;
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

        case 'pago':
          if (is_array($value)) {
            /*foreach ($value as $index => $pago) {
              if (!isset($pago['monto']) || !is_numeric($pago['monto']) || $pago['monto'] <= 0) {
                $this->errores[] = "El monto del pago en la posición $index debe ser un número mayor a 0.";
              }
              if (!isset($pago['cod_tipo_pago']) || !is_numeric($pago['cod_tipo_pago'])) {
                $this->errores[] = "El código de tipo de pago en la posición $index debe ser numérico.";
              }
            }*/
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


  private function registrarPG() //LISTO COMPLETADO AL 100% NI LE MUEVAN JAJAJAJ
  {
    try {
      $this->conex->beginTransaction();

      $tipo_pago = "gasto";
      $sql = "INSERT INTO pago_emitido(tipo_pago,fecha,cod_gasto, monto_total) VALUES(:tipo_pago,:fecha,:cod_gasto,:monto_total)";
      $gasto = $this->conex->prepare($sql);
      $gasto->bindParam(':tipo_pago', $tipo_pago);
      $gasto->bindParam(':fecha', $this->fecha);
      $gasto->bindParam(':cod_gasto', $this->cod_gasto);
      $gasto->bindParam(':monto_total', $this->montototal);
      $resp_pago_emitido = true;
      if (!$gasto->execute()) {
        throw new Exception("Error al insertar en pago_emitido.");
        $resp_pago_emitido = false;
      }

      $cod_pago_emitido = $this->conex->lastInsertId();

      if ($resp_pago_emitido) {
        foreach ($this->pago as $pagos) {
          if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
            var_dump($pagos['cod_tipo_pago']);
            var_dump($pagos['monto']);
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

            $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_detalle_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
            $consulta = $this->conex->prepare($consultaRelacion);
            $consulta->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
            if (!$consulta->execute()) {
              throw new Exception("Error al obtener la relación de tipo de pago.");
            }
            $relacion = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($relacion) {
              if (!empty($relacion['cod_cuenta_bancaria'])) {

                $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo - :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
                $banco = $this->conex->prepare($actualizarSaldoCuenta);
                $banco->bindParam(':monto', $this->montopagado);
                $banco->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
                if (!$banco->execute()) {
                  throw new Exception("Error al actualizar saldo en cuenta_bancaria.");
                }
              } elseif (!empty($relacion['cod_detalle_caja'])) {

                $actualizarSaldoCaja = "UPDATE detalle_caja SET saldo = saldo - :monto WHERE cod_detalle_caja = :cod_detalle_caja";
                $caja = $this->conex->prepare($actualizarSaldoCaja);
                $caja->bindParam(':monto', $this->montopagado);
                $caja->bindParam(':cod_detalle_caja', $relacion['cod_detalle_caja']);
                if (!$caja->execute()) {
                  throw new Exception("Error al actualizar saldo en detalle_caja.");
                }
              }
            }
          }
        }
        if ($this->montototal > $this->montopagado) {
          var_dump("pago parcial");
          var_dump($this->montototal, $this->montopagado);
          $status = "UPDATE gasto SET detgasto_status= 2 WHERE cod_gasto=:cod_gasto";
          $editgasto = $this->conex->prepare($status);
          $editgasto->bindParam(':cod_gasto', $this->cod_gasto);
          if (!$editgasto->execute()) {
            throw new Exception("Error al actualizar el estado del gasto.");
          }

          $this->conex->commit();
          return $r = abs($this->montototal - $this->montopagado);
        } else if ($this->montototal < $this->montopagado) {
          var_dump("pago completo + vuelto");
          var_dump($this->vuelto);
          if ($this->vuelto > 0) {
            $sql = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)";
            $vuel = $this->conex->prepare($sql);
            $vuel->bindParam(':vuelto_total', $this->vuelto);
            if (!$vuel->execute()) {
              throw new Exception("Error al insertar en vuelto_recibido.");
            }

            $vueltocod = $this->conex->lastInsertId();
            var_dump($vueltocod);
            var_dump("Pago emitido".$cod_pago_emitido);

            $actualizargasto = "UPDATE pago_emitido SET cod_vuelto_r = :cod_vuelto_r WHERE cod_pago_emitido= :cod_pago_emitido";
            $insert = $this->conex->prepare($actualizargasto);
            $insert->bindParam(':cod_vuelto_r', $vueltocod);
            $insert->bindParam(':cod_pago_emitido', $cod_pago_emitido);
            if (!$insert->execute()) {
              throw new Exception("Error al actualizar el gasto con el vuelto.");
            }else{
              var_dump("Vuelto registrado");
            }

            $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
            $gastoxvuelto = $this->conex->prepare($status);
            $gastoxvuelto->bindParam(':cod_gasto', $this->cod_gasto);
            if (!$gastoxvuelto->execute()) {
              throw new Exception("Error al actualizar el estado del gasto.");
            }

            $this->conex->commit();
            return $r = 0;
          } else {
            $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
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

      return true;
    } catch (Exception $e) {
      $this->conex->rollBack();
      throw new Exception("Error en la transacción: " . $e->getMessage());
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

  private function cuotaP() // continuo mañana SIGUE CON PROBLEMAS DEBE DE SER LOS PARAMETRO Y VALORES QUE SE LE PASAN A LOS METODOS DE LA CLASE
  {
    try {

      $this->conex->beginTransaction();
      foreach ($this->pago as $pagos) {
        if (!empty($pagos['monto']) && $pagos['monto'] > 0) {
          var_dump($pagos['cod_tipo_pago']);
          var_dump($pagos['monto']);
          $this->cod_tipo_pago = $pagos['cod_tipo_pago'];
          $this->montopagado = $pagos['monto'];
          $registro = "INSERT INTO detalle_pago_emitido(cod_pago_emitido, cod_tipo_pagoe,monto) VALUES(:cod_pago_emitido,:cod_tipo_pago,:monto)";
          $strExec = $this->conex->prepare($registro);
          $strExec->bindParam(':cod_pago_emitido', $this->cod_pago_emitido);
          $strExec->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
          $strExec->bindParam(':monto', $this->montopagado);
          var_dump($this->cod_pago_emitido, $this->cod_tipo_pago, $this->pago);
          if (!$strExec->execute()) {
            throw new Exception("Error al insertar en detalle_pago_emitido.");
          }
          var_dump($this->pago);
          $consultaRelacion = "SELECT cod_cuenta_bancaria, cod_detalle_caja FROM detalle_tipo_pago WHERE cod_tipo_pago = :cod_tipo_pago";
          $consulta = $this->conex->prepare($consultaRelacion);
          $consulta->bindParam(':cod_tipo_pago', $this->cod_tipo_pago);
          if (!$consulta->execute()) {
            throw new Exception("Error al obtener la relación de tipo de pago.");
          }
          $relacion = $consulta->fetch(PDO::FETCH_ASSOC);

          if ($relacion) {
            if (!empty($relacion['cod_cuenta_bancaria'])) {

              $actualizarSaldoCuenta = "UPDATE cuenta_bancaria SET saldo = saldo - :monto WHERE cod_cuenta_bancaria = :cod_cuenta_bancaria";
              $banco = $this->conex->prepare($actualizarSaldoCuenta);
              $banco->bindParam(':monto', $this->montopagado);
              $banco->bindParam(':cod_cuenta_bancaria', $relacion['cod_cuenta_bancaria']);
              if (!$banco->execute()) {
                throw new Exception("Error al actualizar saldo en cuenta_bancaria.");
              }
            } elseif (!empty($relacion['cod_detalle_caja'])) {

              $actualizarSaldoCaja = "UPDATE detalle_caja SET saldo = saldo - :monto WHERE cod_detalle_caja = :cod_detalle_caja";
              $caja = $this->conex->prepare($actualizarSaldoCaja);
              $caja->bindParam(':monto', $this->montopagado);
              $caja->bindParam(':cod_detalle_caja', $relacion['cod_detalle_caja']);
              if (!$caja->execute()) {
                throw new Exception("Error al actualizar saldo en detalle_caja.");
              }
            }
          }
        }
      }
      if ($this->montopagado > $this->montototal) {
        var_dump("pago completo + vuelto");
        var_dump($this->montopagado, $this->montototal);
        $status = "UPDATE gasto SET detgasto_status= 3 WHERE cod_gasto=:cod_gasto";
        $pago = $this->conex->prepare($status);
        $pago->bindParam(':cod_gasto', $this->cod_gasto);
        if (!$pago->execute()) {
          throw new Exception("Error al actualizar el estado del gasto.");
        }
        if ($this->vuelto > 0) {
          $actualizar = "UPDATE vuelto_recibido SET vuelto_total = :vuelto_total WHERE cod_vuelto_r = :cod_vuelto_r";
          $act = $this->conex->prepare($actualizar);
          $act->bindParam(':cod_vuelto_r', $this->cod_vuelto_r);
          $act->bindParam(':vuelto_total', $this->vuelto);
          if (!$act->execute()) {
            throw new Exception("Error al actualizar el vuelto recibido.");
          }
          $resp = true;
          if (!$resp) {
            $actualizar = "INSERT INTO vuelto_recibido(vuelto_total, status_vuelto) VALUES(:vuelto_total, 1)";
            $recibido = $this->conex->prepare($actualizar);
            $recibido->bindParam(':vuelto_total', $this->vuelto);
            if (!$recibido->execute()) {
              throw new Exception("Error al insertar en vuelto_recibido.");
            }
          }
        }
        $this->conex->commit();
        return $res = 0;
      } else if ($this->montopagado < $this->montototal) {
        $status = "UPDATE gasto SET detgasto_status = 2 WHERE cod_gasto=:cod_gasto";
        $restante = $this->conex->prepare($status);
        $restante->bindParam(':cod_gasto', $this->cod_gasto);
        if (!$restante->execute()) {
          throw new Exception("Error al actualizar el estado del gasto.");
        }

        $this->conex->commit();
        return $res = abs($this->montopagado - $this->montototal);
      } else {
        $status = "UPDATE gasto SET detgasto_status = 3 WHERE cod_gasto=:cod_gasto";
        $completo = $this->conex->prepare($status);
        $completo->bindParam(':cod_gasto', $this->cod_gasto);
        if (!$completo->execute()) {
          throw new Exception("Error al actualizar el estado del gasto.");
        }

        $this->conex->commit();
        return $res = 0;
      }
    } catch (Exception $e) {
      $this->conex->rollBack();
      throw new Exception("Error en la transacción: " . $e->getMessage());
    }
  }

  public function registrarCuota()
  {
    return $this->cuotaP();
  }
}
