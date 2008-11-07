<?php

class CssModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'CssTable';

    public function id()
    {
        return $this->CODIGO_CSS;
    }

    public function name()
    {
        return $this->NOMBRE_CSS;
    }
}

?>