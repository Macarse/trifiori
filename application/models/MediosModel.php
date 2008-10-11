<?php

class MediosModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'MediosTable';

    public function id()
    {
        return $this->CODIGOMED;
    }

    public function name()
    {
        return $this->DESCRIPCION_MED;
    }
}

?>