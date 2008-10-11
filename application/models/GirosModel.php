<?php

class GirosModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'GirosTable';

    public function id()
    {
        return $this->CODIGO_GIR;
    }

    public function name()
    {
        return $this->SECCION_GIR;
    }
}

?>