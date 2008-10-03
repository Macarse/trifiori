<?php

class UsersModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'UsersTable';

    public function id()
    {
        return $this->CODIGO_USU;
    }

    public function name()
    {
        return $this->NOMBRE_USU;
    }

    public function user()
    {
        return $this->USUARIO_USU;
    }

    public function isAdmin()
    {
        if ($this->USUARIO_USU == 'admin')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function language()
    {
        /*TODO: Esto debería ser un switch
        o un join con la tabla IDIOMAS */
        if ($this->IDIOMA_USU == 1)
        {
            return 'en';
        }
        else
        {
            return 'es';
        }
    }

}

?>