<?php

require_once 'conexion.php';

class Proveedor extends Conexion
{

  private $conex;
  private $cod_prov;
  private $rif;
  private $razon_social;
  private $email;
  private $direccion;
  private $status;



  public function __construct()
  {
    $this->conex = new Conexion();
    $this->conex = $this->conex->conectar();
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


  //metodos crud    TODO FUNCIONA  //

  private function registrar()
  {

    $sql = "INSERT INTO proveedores(rif,razon_social,email,direccion,status)  VALUES  (:rif,:razon_social,:email,:direccion,1)";

    $strExec = $this->conex->prepare($sql);

    $strExec->bindParam(':rif', $this->rif);
    $strExec->bindParam(':razon_social', $this->razon_social);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);


    $resul = $strExec->execute();
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


  //inicio de actualizar//
  private function editar() {
    
    $sql = "UPDATE proveedores SET rif = :rif, razon_social = :razon_social, email = :email, direccion = :direccion, status = :status WHERE cod_prov = :cod_prov";

    $strExec = $this->conex->prepare($sql);
    $strExec->bindParam(':cod_prov', $this->cod_prov);
    $strExec->bindParam(':rif', $this->rif);
    $strExec->bindParam(':razon_social', $this->razon_social);
    $strExec->bindParam(':email', $this->email);
    $strExec->bindParam(':direccion', $this->direccion);
    $strExec->bindParam(':status', $this->status);

    // Ejecuta la consulta  
    $resul = $strExec->execute();

    if ($resul == 1) {
      return 1; // Éxito  
    } else {
      return 0; // Fallo  
    }
}

public function getedita() {
    return $this->editar();
}
  //actualizar//



    //acomodar ti tiene compra o//
    private function eliminar($valor)
    {
        // Verificar si hay compras asociadas
        $sql = "SELECT COUNT(*) AS n_compras FROM compras WHERE cod_prov = :cod_prov";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
        $strExec->execute();
        $resultadoCompras = $strExec->fetch(PDO::FETCH_ASSOC);
    
        if ($resultadoCompras['n_compras'] > 0) {
            // No se puede eliminar, tiene compras asociadas
            return 'error_compra_asociada';
        }
    
        // Verificar si hay representantes asociados
        $sql = "SELECT COUNT(*) AS n_representantes FROM prov_representantes WHERE cod_prov = :cod_prov";
        $strExec = $this->conex->prepare($sql);
        $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
        $strExec->execute();
        $resultadoRepresentantes = $strExec->fetch(PDO::FETCH_ASSOC);
    
        if ($resultadoRepresentantes['n_representantes'] > 0) {
            // Actualizar el estado a 2
            $logico = "UPDATE proveedores SET status = 2 WHERE cod_prov = :cod_prov";
            $strExec = $this->conex->prepare($logico);
            $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
            $strExec->execute();
            return 'success';
        } else {
            // Eliminar proveedor
            $fisico = "DELETE FROM proveedores WHERE cod_prov = :cod_prov";
            $strExec = $this->conex->prepare($fisico);
            $strExec->bindParam(':cod_prov', $valor, PDO::PARAM_INT);
            $strExec->execute();
            return 'success';
        }
    }
  
  public function geteliminar($valor)
  {
      return $this->eliminar($valor);
  }

  //inicio de consultar todo//
  private  function consultar()
  {
    $registro = "SELECT 
    p.cod_prov, 
    p.*, 
    pr.cod_representante,  
    pr.nombre, 
    pr.cedula, 
    pr.apellido, 
    pr.telefono AS rep_tel, 
    p.status, 
    pr.status AS statusr,
    t.telefono,
    t.cod_tlf  -- Agregar cod_tlf del teléfono
FROM 
    proveedores p  
LEFT JOIN 
    prov_representantes pr ON p.cod_prov = pr.cod_prov AND pr.status = 1  
LEFT JOIN 
    tlf_proveedores t ON p.cod_prov = t.cod_prov  
ORDER BY 
    p.cod_prov ASC";

    $consulta = $this->conex->prepare($registro);
    $resul = $consulta->execute();
    $datos = $consulta->fetchAll(PDO::FETCH_ASSOC);

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
  private function buscar($dato)
  {
    $this->rif = $dato;
    $registro = "select * from proveedores where rif='" . $this->rif . "'";
    $resulado = "";
    $dato = $this->conex->prepare($registro);
    $resul = $dato->execute();
    $resultado = $dato->fetch(PDO::FETCH_ASSOC);
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
