<?php

class MonedasModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'MonedasTable';

    public function id()
    {
        return $this->CODIGO_MON;
    }

    public function name()
    {
        return $this->NAME_MON;
    }

    public function longName()
    {
        return $this->DESCRIPCION_MON;
    }
}

?>