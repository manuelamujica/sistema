<?php

require_once 'conexion.php';

class Proveedor extends Conexion{
  private $cod_prov;
  private $rif;
  private $razon_social;
  private $email;
  private $direccion;
  private $status;

  public function __construct()
  {
    parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
  }

  public function setCod($cod_prov)
  {
    $this->cod_prov = $cod_prov;
  }

  public function gettCod()
  {
    return $this->cod_prov;
  }

  public function getRif()
  {
    return $this->rif;
  }
  public function setRif($rif)
  {
    $this->rif = $rif;
  }

  public function getRazon_Social()
  {
    return $this->razon_social;
  }

  public function setRazon_Social($razon_social)
  {
    $this->razon_social = $razon_social;
  }

  public function get_Email()
  {
    return $this->email;
  }

  public function setemail($email)
  {
    $this->email = $email;
  }

  public function getDireccion()
  {
    return $this->direccion;
  }

  public function setDireccion($direccion)
  {
    $this->direccion = $direccion;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

/*======================
  REGISTRAR
==========================*/
  private function registrar()
  {

    $sql = "INSERT INTO proveedores(rif,razon_social,email,direccion,status)  VALUES  (:rif,:razon_social,:email,:direccion,1)";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':rif', $this->rif);
    $strExec->bindParam(':razon_social', $this->razon_social);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);
    $resul = $strExec->execute();
    parent::desconectarBD();
    if ($resul) {
      $res = 1;
    } else {
      $res = 0;
    }
    return $res;
  } 

  public function getregistra()
  {
    return $this->registrar();
  }


  /*======================
  EDITAR
  ==========================*/
  private function editar() {
    
    $sql = "UPDATE proveedores SET rif = :rif, razon_social = :razon_social, email = :email, direccion = :direccion, status = :status WHERE cod_prov = :cod_prov";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_prov', $this->cod_prov);
    $strExec->bindParam(':rif', $this->rif);
    $strExec->bindParam(':razon_social', $this->razon_social);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);
    $strExec->bindParam(':status', $this->status);
    // Ejecuta la consulta  
    $resul = $strExec->execute();
    parent::desconectarBD();
    if ($resul == 1) {
      return 1; 
    } else {
      return 0;  
    }
}

public function getedita() {
    return $this->editar();
}

/*======================
  ELIMINAR
==========================*/

  private function eliminar($valor)
  {
      // Verificar si hay compras asociadas
      $sqlCompras = "SELECT COUNT(*) AS n_compras FROM compras WHERE cod_prov = :cod_prov";
      parent::conectarBD();
      $strExec = $this->conex->prepare($sqlCompras);
      $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
      $strExec->execute();
      $resultadoCompras = $strExec->fetch(PDO::FETCH_ASSOC);
      // Si hay compras asociadas, no se puede eliminar
      if ($resultadoCompras['n_compras'] > 0) {
          parent::desconectarBD();
          return 'error_compra_asociada';
      }
      // Verificar si hay representantes asociados
      $sqlRepresentantes = "SELECT COUNT(*) AS n_representantes FROM prov_representantes WHERE cod_prov = :cod_prov";
      $strExec = $this->conex->prepare($sqlRepresentantes);
      $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
      $strExec->execute();
      $resultadoRepresentantes = $strExec->fetch(PDO::FETCH_ASSOC);
  
      // Eliminar proveedor físicamente si no tiene compras asociadas
      if ($resultadoCompras['n_compras'] == 0) {
          // Eliminar proveedor
          $fisico = "DELETE FROM proveedores WHERE cod_prov = :cod_prov";
          $strExec = $this->conex->prepare($fisico);
          $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
          $strExec->execute();
          parent::desconectarBD();
          return 'success_eliminado';
      }
      parent::desconectarBD();
      return 'error_compra_asociada'; // Por si acaso
  }
  
/*  public function geteliminar($valor)
  {
      return $this->eliminar($valor);
  }*/

  public function get_eliminar()
  {
    if (empty($this->cod_prov)) {
      return 'error_cod';
    }
      return $this->eliminar($this->cod_prov);
  }

/*======================
  CONSULTAR
==========================*/

  private  function consultar()
  {
    $registro = "SELECT 
        p.cod_prov,
        p.rif,
        p.razon_social,
        p.email,
        p.direccion,
        p.status AS proveedor_status,
        GROUP_CONCAT(DISTINCT t.telefono ORDER BY t.telefono SEPARATOR ', ') AS telefonos,
        r.cod_representante,
        r.cedula,
        r.nombre,
        r.apellido,
        r.telefono AS rep_tel,
        r.status AS statusr
    FROM 
        proveedores p
    LEFT JOIN 
        tlf_proveedores t ON p.cod_prov = t.cod_prov
    LEFT JOIN 
        prov_representantes r ON p.cod_prov = r.cod_prov
    GROUP BY 
      p.cod_prov
    ORDER BY 
      p.cod_prov;";
    parent::conectarBD();
    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if ($resul) {
      return $datos;
    } else {
      return $res = 0;
    }
  }
  public function getconsulta()
  {
    return $this->consultar();
  }


/*======================
  BUSCAR
==========================*/
  private function buscar($dato)
  {
    $this->rif = $dato;
    $registro = "select * from proveedores where rif='" . $this->rif . "'";
    $resulado = "";
    parent::conectarBD();
    $dato = $this->conex->prepare($registro);
    $resul = $dato->execute();
    $resultado = $dato->fetch(PDO::FETCH_ASSOC);
    parent::desconectarBD();
    if ($resul) {
      return $resultado;
    } else {
      return false;
    }
  }

  public function getbuscar($valor)
  {
    return $this->buscar($valor);
  }
}
