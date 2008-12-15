<?php
class Monedas extends Zend_Db_Table_Abstract
{
    protected $_name = 'MONEDAS';
    protected $_sequence = true;
    protected $_rowClass = 'MonedasModel';

    public function removeMoneda( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getMonedaByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
	
	public function getMonedaByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('NAME_MON = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addMoneda( $name, $longName )
    {
        /*TODO: Validaciones*/
        $data = array(  'NAME_MON' => $name,
                        'DESCRIPCION_MON' => $longName,
                    );
        $this->insert($data);

        return True;
    }

    public function searchMoneda( $name , $sortby , $sorttype )
    {     
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        if ($mySortby == "desc")
            $mySortby = "DESCRIPCION_MON";
        else
            $mySortby = "NAME_MON";
        
        if ($name != "")
            $where = "NAME_MON LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->where("DELETED LIKE '0'")->order($mySortby . " " . $mySorttype);
    }
    
    public function modifyMoneda( $id, $name, $longName )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NAME_MON' => $name,
                            'DESCRIPCION_MON' => $longName,
                            ), $where );

        return True;
    }

    public function getMonedasArray()
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
