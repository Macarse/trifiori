<?php

class CargasModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'CargasTable';

    public function id()
    {
        return $this->CODIGO_CAR;
    }

    public function cantBultos()
    {
        return $this->CANTBULTOS_CAR;
    }

    public function tipoEnvase()
    {
        return $this->TIPOENVASE_CAR;
    }

    public function peso()
    {
        return $this->PESOBRUTO_CAR;
    }

    public function unidad()
    {
        return $this->UNIDAD_CAR;
    }

    public function nroPaquete()
    {
        return $this->NROPAQUETE_CAR;
    }

    public function marcaYnum()
    {
        return $this->MARCAYNUMERO;
    }

    public function mercIMCO()
    {
        return $this->MERC__IMCO;
    }
}

?>