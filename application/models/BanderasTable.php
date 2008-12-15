<?php
class Banderas extends Zend_Db_Table_Abstract
{
    protected $_name = 'BANDERAS';
    protected $_sequence = true;
    protected $_rowClass = 'BanderasModel';

    public function removeBandera( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_BAN = ?', $id);
        //$this->delete( $where );
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

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getBanderaByID( $id )
    {
        $where = $this->getAdapter()->quoteInto("CODIGO_BAN = ?", $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
	
	public function getBanderaByName( $name )
    {
        $where = $this->getAdapter()->quoteInto("NOMBRE_BAN = ?", $name);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function searchBandera( $name , $sortby, $sorttype )
    {
        $name = mysql_real_escape_string($name);
//         siempre se ordena por nombre:
//         $mySortby = mysql_real_escape_string($sortby);
        $mySortby = "name";
        $mySorttype = mysql_real_escape_string($sorttype);

        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        if ($mySortby == "name")
            $mySortby = "NOMBRE_BAN";
        
        if ($name != "")
            $where = "NOMBRE_BAN LIKE '%" . $name . "%'";
        else
            $where = "1=1";
            
        return $this->select()
                    ->from($this)
                    ->where($where)
                    ->where("DELETED LIKE '0'")
                    ->order($mySortby . " " . $mySorttype);
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
            $where = $this->getAdapter()->quoteInto("DELETED LIKE '0'");
            $banderas = $this->fetchAll($where);
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
