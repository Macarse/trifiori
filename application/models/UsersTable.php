<?php
class Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'USUARIOS';
    protected $_sequence = true;
    protected $_rowClass = 'UsersModel';

    public function addUser( $name, $user, $pass, $lang, $css, $email )
    {
        $data = array(  'NOMBRE_USU'  => $name,
                        'USUARIO_USU' => $user,
                        'PASSWORD_USU'=> $pass,
                        'CODIGO_CSS'  => $css,
                        'IDIOMA_USU'  => $lang,
                        'EMAIL_USU'   => $email
                    );

        $this->insert($data);

        return True;
    }

    public function removeUser( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_USU = ?', $id);
        $this->delete( $where );
    }

    public function getUserByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_USU = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getUserByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('USUARIO_USU = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function searchUser( $name , $sortby , $sorttype)
    {
        $name = mysql_real_escape_string($name);
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);

        if ($mySorttype == "asc")
            $mySorttype = "ASC";
        else
            $mySorttype = "DESC";
        
        if ($mySortby == "name")
            $mySortby = "NOMBRE_USU";
        else
            $mySortby = "USUARIO_USU";
        
        if ($name != "")
            $where = "USUARIO_USU LIKE '%" . $name . "%'";
        else
            $where = "1=1";
            
        return $this->select()->from($this)->where($where)->order($mySortby . " " . $mySorttype);
    }

    public function getUserByResetHash( $hash )
    {
        /*TODO: Try Catch*/
        $where = $this->getAdapter()->quoteInto('RESET_HASH_USU = ?', $hash);

        return $this->fetchRow($where);
    }

    public function newPass( $email )
    {
        /*TODO: Try Catch*/
        $where = $this->getAdapter()->quoteInto('EMAIL_USU = ?', $email);
        $row = $this->fetchRow($where);

        if (count($row))
        {
            $hash = hash('SHA1', rand());
            $this->modifyResetHash( $row->id(), $hash );

            return $hash;
        }
        else
        {
            return NULL;
        }
    }


    public function changePass( $id, $pass, $hash)
    {
        /*TODO: Try Catch*/
        $where = $this->getAdapter()->quoteInto('CODIGO_USU = ?', $id);
        $row = $this->fetchRow($where);

        $this->update(array('PASSWORD_USU'  => $pass,
                            'RESET_HASH_USU'=> $hash ), $where );

        return True;
    }

    public function modifyResetHash( $id, $hash)
    {
        /*TODO: Try Catch*/
        $where = $this->getAdapter()->quoteInto('CODIGO_USU = ?', $id);
        $row = $this->fetchRow($where);

        $this->update(array('RESET_HASH_USU'    => $hash), $where );

        return True;
    }

    public function modifyUser( $id, $name, $user, $pass, $lang, $css, $email )
    {
        /*TODO: Try Catch*/
        $where = $this->getAdapter()->quoteInto('CODIGO_USU = ?', $id);
        $row = $this->fetchRow($where);

        /*Si no se setteó la pass, uso la vieja.*/
        if ($pass == "")
        {
            $pass = $row->PASSWORD_USU;
        }
        else
        {
            $pass = hash('SHA1', $pass);
        }

        $this->update(array('NOMBRE_USU'    => $name,
                            'USUARIO_USU'   => $user,
                            'PASSWORD_USU'  => $pass,
                            'CODIGO_CSS'    => $css,
                            'EMAIL_USU'     => $email,
                            'IDIOMA_USU'    => $lang ), $where );

        return True;
    }

}

?>