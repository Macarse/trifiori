<?php
class Destinaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'DESTINACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'DestinacionesModel';

    public function removeDestinacion( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getDestinacionByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getDestinacionByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('DESCRIPCION_DES = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function addDestinacion( $name )
    {
        /*TODO: Validaciones*/
        $data = array('DESCRIPCION_DES' => $name);
        $this->insert($data);

        return True;
    }
	
    public function getDestinacionByDesc( $name )
    {
        $where = $this->getAdapter()->quoteInto('DESCRIPCION_DES = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function searchDestinacion( $name, $sortby, $sorttype )
    {
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        $mySortby = "DESCRIPCION_DES";
        
        if ($name != "")
            $where = "DESCRIPCION_DES LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->where("DELETED LIKE '0'")->order($mySortby . " " . $mySorttype);
    }
    
    public function modifyDestinacion( $id, $name )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DESCRIPCION_DES'    => $name), $where );

        return True;
    }

    public function getDestinacionesArray()
    {
        $arr = array();

        try
        {
            $where = $this->getAdapter()->quoteInto("DELETED LIKE '0'");
            $data = $this->fetchAll($where);
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($data as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
