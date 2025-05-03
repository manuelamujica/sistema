<?php
require_once 'conexion.php';

class  Representantes extends Conexion{
  private $cod_representante;
  private $cod_prov;
  private $cedula;
  private $nombre;
  private $apellido;
  private $telefono;
  private $status;

  public function __construct()
  {
    parent::__construct(_DB_HOST_, _DB_NAME_, _DB_USER_, _DB_PASS_);
  }


  public function setCod1($cod_prov)
  {
    $this->cod_prov = $cod_prov;
  }
  public function getCod1()
  {
    return $this->cod_prov;
  }
  public function setCod($cod_representante)
  {
    $this->cod_representante = $cod_representante;
  }
  public function getCod()
  {
    return $this->cod_representante;
  }

  public function getcedula()
  {
    return $this->cedula;
  }

  public function setcedula($cedula)
  {
    $this->cedula = $cedula;
  }

  public function getnombre()
  {
    return $this->nombre;
  }

  public function setnombre($nombre)
  {
    $this->nombre = $nombre;
  }

  public function getapellido()
  {
    return $this->apellido;
  }

  public function setapellido($apellido)
  {
    $this->apellido = $apellido;
  }



  public function gettelefono()
  {
    return $this->telefono;
  }

  public function settelefono($telefono)
  {
    $this->telefono = $telefono;
  }



  public function getStatus()
  {
    return $this->status;
  }

  public function setStatus($status)
  {
    $this->status = $status;
  }

  //metodos crud  registrar  //
  private function registrar()
  {
    $sql = "INSERT INTO prov_representantes (cod_prov, cedula, nombre, apellido, telefono, status) VALUES (:cod_prov, :cedula, :nombre, :apellido, :telefono, 1)";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    // Vincula los parámetros  
    $strExec->bindParam(':cod_prov', $this->cod_prov);
    $strExec->bindParam(':cedula', $this->cedula);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':apellido', $this->apellido);
    $strExec->bindParam(':telefono', $this->telefono);
    // Ejecutar el INSERT  
    $resul = $strExec->execute();
    parent::desconectarBD();
    if ($resul) {
      $res = 1;
    } else {
      $res = 0;
    }
    return $res;
  } //fin de registrar//

  public function getregistra()
  {
    return $this->registrar();
  }




  //inicio de actualizar   //
  private function editar()
  {
    $sql = "UPDATE prov_representantes 
              SET cedula=:cedula, nombre=:nombre, apellido=:apellido, telefono=:telefono, status=:status 
              WHERE cod_representante = :cod_representante";
    parent::conectarBD();
    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_representante', $this->cod_representante);
    $strExec->bindParam(':cedula', $this->cedula);
    $strExec->bindParam(':nombre', $this->nombre);
    $strExec->bindParam(':apellido', $this->apellido);
    $strExec->bindParam(':telefono', $this->telefono);
    $strExec->bindParam(':status', $this->status);
    // Ejecuta la consulta  
    $resul = $strExec->execute();
    parent::desconectarBD();
    if ($resul == 1) {
      return 1; // Éxito  
    } else {
      return 0; // Fallo  
    }
  }


  public function getedita()
  {
    return $this->editar();
  }


  //actualizar//
  private function eliminar($valor)
  {
      // Eliminar de forma física sin buscar nada
      $eliminar = "DELETE FROM prov_representantes WHERE cod_representante = :cod_representante";
      parent::conectarBD();
      $strExecDelete = $this->conex->prepare($eliminar);
      $strExecDelete->bindParam(':cod_representante', $valor);
      if ($strExecDelete->execute()) {
        parent::desconectarBD();
          return 'success_physical_delete';
      } else {
        parent::desconectarBD();
          return 'error_physical_delete';
      }
  }
  
  // Método para obtener el resultado de la eliminación
  public function geteliminar($valor)
  {
      return $this->eliminar($valor);
  }

  //inicio de consultar//
  private function consultar()
  {
    $registro = "select * from prov_representantes";
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

  //fin de consultar//

  //metodo buscar
  public function buscar($dato)
  {
    $this->cedula = $dato;
    $registro = "select * from prov_representantes where cedula='" . $this->cedula . "'";
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
