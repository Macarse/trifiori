<?php
class Transportes extends Zend_Db_Table_Abstract
{
    protected $_name = 'TRANSPORTES';
    protected $_sequence = True;
    protected $_rowClass = 'TransportesModel';
 
    public function removeTransporte( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }
 
    public function getTransporteByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
        $row = $this->fetchRow( $where );
 
        return $row;
    }
  
    public function getTransporteByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('NOMBRE_BUQ = ?', $name);
        $row = $this->fetchRow( $where );
 
        return $row;
    }
 
 
    public function addTransporte( $nameBandera, $codMedio, $name, $observaciones )
    {
    //Banderas
 
            $banderas = new Banderas();
    try
    {
        $codBandera = $banderas->getBanderaByName($nameBandera);
        if ($codBandera != NULL)
        {
            $codBandera = $codBandera->id();
        }
        else
        {
            throw new Exception('No existe la Bandera');
            return False;
        }
    }
    catch (Zend_Exception $e)
    {
        throw new Exception($e->getMessage());
        return False;
    }
        $row = $this->getTransporteByName( $name );
        if (count($row))
        {
            $this->updateTransporte ($row->id(), $nameBandera, $codMedio, $name, $observaciones );
        }
        else
        {
            $data = array( 'CODIGO_BAN' => $codBandera,
                           'CODIGOMED' => $codMedio,
                           'NOMBRE_BUQ' => $name,
                           'OBSERVACIONES_BUQ' => $observaciones
                         );
            $this->insert($data);
        }

        return True;

    }
 
    public function searchTransporte( $name , $sortby , $sorttype )
    {
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        if ($mySortby == "flags")
            $mySortby = "NOMBRE_BAN";
        else
            $mySortby = "NOMBRE_BUQ";
        
        if ($name != "")
            $where = "NOMBRE_BUQ LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        $select = $this->select();
                
        $select->from($this, array('CODIGO_BUQ', 'CODIGO_BAN', 'NOMBRE_BUQ', 'CODIGOMED', 'OBSERVACIONES_BUQ'));
        $select->setIntegrityCheck(false)
                ->join('BANDERAS', 'BANDERAS.CODIGO_BAN = TRANSPORTES.CODIGO_BAN', array())
                ->where($where)
                ->where("TRANSPORTES.DELETED LIKE '0'")
                ->order($mySortby . " " . $mySorttype);
 
        return $select;
    }
    
    public function updateTransporte( $id, $nameBandera, $codMedio, $name, $observaciones )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }
 
    //Banderas
            $banderas = new Banderas();
    try
    {
        $codBandera = $banderas->getBanderaByName($nameBandera);
        if ($codBandera != NULL)
        {
            $codBandera = $codBandera->id();
        }
        else
        {
            throw new Exception('No existe la Bandera');
            return False;
        }
    }
    catch (Zend_Exception $e)
    {
        throw new Exception($e->getMessage());
        return False;
    }
 
 
    $this->update(array(
                  'CODIGO_BAN' => $codBandera,
                  'CODIGOMED' => $codMedio,
                  'NOMBRE_BUQ' => $name,
                  'OBSERVACIONES_BUQ' => $observaciones,
                  'DELETED'  => '0'), $where );
 
    return True;
    }

    public function modifyTransporte( $id, $nameBandera, $codMedio, $name, $observaciones )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }
 
    //Banderas
            $banderas = new Banderas();
    try
    {
        $codBandera = $banderas->getBanderaByName($nameBandera);
        if ($codBandera != NULL)
        {
            $codBandera = $codBandera->id();
        }
        else
        {
            throw new Exception('No existe la Bandera');
            return False;
        }
    }
    catch (Zend_Exception $e)
    {
        throw new Exception($e->getMessage());
        return False;
    }
 
 
    $this->update(array(
                  'CODIGO_BAN' => $codBandera,
                  'CODIGOMED' => $codMedio,
                  'NOMBRE_BUQ' => $name,
                  'OBSERVACIONES_BUQ' => $observaciones), $where );
 
    return True;
    }
 
    public function getTransportesArray()
    {
        $arr = array();
 
        try
        {
            $where = $this->getAdapter()->quoteInto("DELETED LIKE '0'");
            $transportes = $this->fetchAll($where);
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }
 
        foreach ($transportes as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }
 
        return $arr;
    }
 
}
 
?>
