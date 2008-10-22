<?php

class CanalesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'CanalesTable';

    public function id()
    {
        return $this->CODIGO_CAN;
    }

    public function name()
    {
        return $this->DESCRIPCION_CAN;
    }
}

?>