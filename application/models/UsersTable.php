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

    public function searchUser( $name )
    {
        $name = mysql_real_escape_string($name);

        return $this->select()->where("USUARIO_USU LIKE '%" . $name . "%'"); 
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