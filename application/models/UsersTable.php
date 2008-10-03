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
}

?>