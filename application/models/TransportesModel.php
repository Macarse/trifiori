<?php

class TransportesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'TransportesTable';

    public function id()
    {
        return $this->CODIGO_BUQ;
    }

    public function codBanderaName()
    {
        $row = null;
        $banderasTable = new Banderas();

        $row = $banderasTable->getBanderaByID($this->CODIGO_BAN);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codBandera()
    {
        return $this->CODIGO_BAN;
    }

   public function codMedioName()
    {
        /*TODO: Agregar la lógica cuando esté codMedio*/
        return $this->CODIGO_BAN;
    }

    public function codMedio()
    {
        return $this->CODIGOMED;
    }

    public function name()
    {
        return $this->NOMBRE_BUQ;
    }

    public function observaciones()
    {
        return $this->OBSERVACIONES_BUQ;
    }

}

?>