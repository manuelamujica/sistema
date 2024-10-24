<?php
require_once 'conexion.php';

class Tproveedor extends Conexion
{

    private $conex;
    private $cod_tlf;
    private $cod_prov;
    private $telefono;
    private  $status;



    public function __construct()
    {
        $this->conex = new Conexion();
        $this->conex = $this->conex->conectar();
    }








    public function setcod_tlf($cod_tlf)
    {
        $this->cod_tlf = $cod_tlf;
    }
    public function getcod_tlf()
    {
        return $this->cod_tlf;
    }


    public function setCod1($cod_prov)
    {
        $this->cod_prov = $cod_prov;
    }
    public function getCod1()
    {
        return $this->cod_prov;
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




    // TODO FUNCIONA 
    private function registra() {
        $sql = "INSERT INTO tlf_proveedores (cod_prov, cod_tlf, telefono, status) VALUES (:cod_prov, :cod_tlf, :telefono, 1)";
        
        $strExec = $this->conex->prepare($sql);
    
        // Vincula los parámetros
        $strExec->bindParam(':cod_prov', $this->cod_prov);
        $strExec->bindParam(':cod_tlf', $this->cod_tlf); 
        $strExec->bindParam(':telefono', $this->telefono);
    
       
        // Ejecuta la consulta  
        $resul = $strExec->execute();

        if ($resul == 1) {
            return 1; // Éxito  
        } else {
            return 0; // Fallo  
        }
    }
    
    public function getregistra() {
        return $this->registra();
    }
    

  //metodo buscar
  private function busca($dato)
  {
    $this->telefono = $dato;
    $registro = "select * from tlf_proveedores where telefono='" . $this->telefono . "'";
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

  public function getbusca($valor)
  {
    return $this->busca($valor);
  }
}
