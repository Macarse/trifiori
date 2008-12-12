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

    public function email()
    {
        return $this->EMAIL_USU;
    }

    public function resetHash()
    {
        return $this->RESET_HASH_USU;
    }

    public function isAdmin()
    {
        if ($this->USUARIO_USU == 'admin')
        {
            return True;
        }
        else
        {
            return False;
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

    public function langNum()
    {
        return $this->IDIOMA_USU;
    }

    public function codCssName()
    {
        $row = NULL;
        $cssTable = new Css();

        $row = $cssTable->getCssByID($this->CODIGO_CSS);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codCss()
    {
        return $this->CODIGO_CSS;
    }

}

?>