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

    public function searchBandera( $name )
    {
        $name = mysql_real_escape_string($name);

        return $this->select()->where("NOMBRE_BAN LIKE '%" . $name . "%'"); 
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
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_BAN = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NOMBRE_BAN'    => $name), $where );

        return True;
    }

    public function getBanderasArray()
    {
        $arr = array();

        try
        {
            $banderas = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($banderas as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
