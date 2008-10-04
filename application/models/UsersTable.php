<?php
class Users extends Zend_Db_Table_Abstract
{
    protected $_name = 'USUARIOS';
    protected $_sequence = true;
    protected $_rowClass = 'UsersModel';

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

    public function modifyUser( $id, $name, $user, $pass, $lang )
    {
        /*TODO: Validaciones.*/

        /*TODO: Ese id existe?*/
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

        /*TODO: Pudo actualizar correctamente?*/
        $this->update(array('NOMBRE_USU'    => $name,
                            'USUARIO_USU'   => $user,
                            'PASSWORD_USU'  => $pass,
                            'IDIOMA_USU'    => $lang ), $where );

        return True;
    }

}

?>