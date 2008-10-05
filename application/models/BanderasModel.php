<?php

class BanderasModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'BanderasTable';

    public function id()
    {
        return $this->CODIGO_BAN;
    }

    public function name()
    {
        return $this->NOMBRE_BAN;
    }
}

?>