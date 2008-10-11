<?php

class LangModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'LangTable';

    public function id()
    {
        return $this->CODIGO_IDI;
    }

    public function name()
    {
        return $this->NOMBRE_IDI;
    }
}

?>