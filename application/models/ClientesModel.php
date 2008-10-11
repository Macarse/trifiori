<?php

class ClientesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'ClientesTable';

    public function id()
    {
        return $this->CODIGO_CLI;
    }

    public function name()
    {
        return $this->NOMBRE_CLI;
    }

    public function adress()
    {
        return $this->DIRECCION_CLI;
    }

    public function codPostal()
    {
        return $this->CODIGOPOSTAL_CLI;
    }

    public function localidad()
    {
        return $this->LOCALIDAD_CLI;
    }

    public function CUIT()
    {
        return $this->CUIT_CLI;
    }

    public function tipoIVA()
    {
        return $this->TIPOIVA_CLI;
    }

    public function tipoCliente()
    {
        return $this->TIPOCLIENTE_CLI;
    }

}

?>