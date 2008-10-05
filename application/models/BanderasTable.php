<?php
class Banderas extends Zend_Db_Table_Abstract
{
    protected $_name = 'BANDERAS';
    protected $_sequence = true;
    protected $_rowClass = 'BanderasModel';

    public function removeBandera( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_BAN = ?', $id);
        $this->delete( $where );
    }

    public function getBanderaByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_BAN = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addBandera( $name )
    {
        /*TODO: Validaciones*/
        $data = array('NOMBRE_BAN' => $name);
        $this->insert($data);

        return True;
    }

    public function modifyBandera( $id, $name )
    {
        /*TODO: Validaciones.*/

        /*TODO: Ese id existe?*/
        $where = $this->getAdapter()->quoteInto('CODIGO_BAN = ?', $id);
        $row = $this->fetchRow($where);

        /*TODO: Pudo actualizar correctamente?*/
        $this->update(array('NOMBRE_BAN'    => $name), $where );

        return True;
    }

}

?>