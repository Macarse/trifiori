<?php

class ProveedoresModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'ProveedoresTable';

    public function id()
    {
        return $this->CODIGO_TRA;
    }

    public function name()
    {
        return $this->NOMBRE_TRA;
    }

    public function adress()
    {
        return $this->DIRECCION_TRA;
    }

    public function tel()
    {
        return $this->TELEFONOS_TRA;
    }

    public function fax()
    {
        return $this->FAX_TRA;
    }

    public function mail()
    {
        return $this->MAIL_TRA;
    }

}

?>