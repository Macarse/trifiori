<?php

class PuertosModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'PuertosTable';

    public function id()
    {
        return $this->CODIGO_PUE;
    }

    public function name()
    {
        return $this->NOMBRE_PUE;
    }

    public function ubicacion()
    {
        return $this->UBICACION_PUE;
    }

    public function latitud()
    {
        return $this->LATITUD_PUE;
    }

    public function longitud()
    {
        return $this->LONGITUD_PUE;
    }
    
}

?>
