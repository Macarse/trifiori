<?php

class TransportsModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'TransportsTable';

    public function id()
    {
        return $this->CODIGO_BUQ;
    }

    public function codBanderaName()
    {
        return $banderasTable->getBanderaByID($this->CODIGO_BAN);
    }

    public function codBandera()
    {
        return $this->CODIGO_BAN;
    }

    public function codMedio()
    {
        return $this->CODIGOMED;
    }

    public function nombre()
    {
        return $this->NOMBRE_BUQ;
    }

    public function observaciones()
    {
        return $this->OBSERVACIONES_BUQ;
    }

}

?>