<?php

class LogModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'LogTable';

    public function id()
    {
        return $this->CODIGOLOG;
    }

    public function msg()
    {
        return $this->MSG;
    }

    public function nivel()
    {
        return $this->NIVEL;
    }
}

?>