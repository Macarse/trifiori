<?php

class DestinacionesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'DestinacionesTable';

    public function id()
    {
        return $this->CODIGO_DES;
    }

    public function name()
    {
        return $this->DESCRIPCION_DES;
    }
}

?>