<?php
class Giros extends Zend_Db_Table_Abstract
{
    protected $_name = 'GIROS';
    protected $_sequence = true;
    protected $_rowClass = 'GirosModel';

    public function removeGiro( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getGiroByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
	
	public function getGiroBySeccion( $seccion )
    {
        $where = $this->getAdapter()->quoteInto('SECCION_GIR = ?', $seccion);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addGiro( $name )
    {
        $row = $this->getGiroBySeccion( $name );
        if (count($row))
        {
            $this->updateGiro ($row->id(), $name );
        }
        else
        {
            $data = array('SECCION_GIR' => $name);
            $this->insert($data);
        }

        return True;
    }
    
    public function searchGiro( $name, $sortby, $sorttype )
    {
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        $mySortby = "SECCION_GIR";
        
        if ($name != "")
            $where = "SECCION_GIR LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->where("DELETED LIKE '0'")->order($mySortby . " " . $mySorttype);
    }

    private function updateGiro( $id, $name )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('SECCION_GIR'    => $name, 'DELETED'    => '0'), $where );

        return True;
    }
    
    public function modifyGiro( $id, $name )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('SECCION_GIR'    => $name), $where );

        return True;
    }

    public function getGirosArray()
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
