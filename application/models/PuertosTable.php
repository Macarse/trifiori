<?php
class Puertos extends Zend_Db_Table_Abstract
{
    protected $_name = 'PUERTOS';
    protected $_sequence = true;
    protected $_rowClass = 'PuertosModel';

    public function removePuerto( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
        $this->delete( $where );
    }

    public function getPuertoByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getPuertoByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('NOMBRE_PUE = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function addPuerto( $name, $ubicacion, $latitud, $longitud )
    {
        /*TODO: Validaciones*/
        $data = array(  'NOMBRE_PUE' => $name,
                        'UBICACION_PUE' => $ubicacion,
                        'LONGITUD_PUE' => $latitud,
                        'LATITUD_PUE' => $longitud,
                    );
        $this->insert($data);

        return True;
    }
    
    public function searchPuerto( $name, $sortby, $sorttype )
    {
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        if ($mySortby == "ubicacion")
            $mySortby = "UBICACION_PUE";
        else
            $mySortby = "NOMBRE_PUE";
        
        if ($name != "")
            $where = "NOMBRE_PUE LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->order($mySortby . " " . $mySorttype);
    }
    
    public function modifyPuerto( $id, $name, $ubicacion, $latitud, $longitud )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NOMBRE_PUE'    => $name,
                            'UBICACION_PUE'    => $ubicacion,
                            'LATITUD_PUE'    => $latitud,
                            'LONGITUD_PUE'    => $longitud,
                            ), $where );

        return True;
    }

    public function getPuertosArray()
    {
        $arr = array();

        try
        {
            $Puertos = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($Puertos as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

    public function modifyGeoLocPuerto($name, $latitud, $longitud )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('NOMBRE_PUE = ?', $name);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('LATITUD_PUE'    => $latitud,
                            'LONGITUD_PUE'    => $longitud,
                            ), $where );

        return True;
    }
}

?>
