<?php
class Proveedores extends Zend_Db_Table_Abstract
{
    protected $_name = 'PROVEEDOR_MEDIO';
    protected $_sequence = true;
    protected $_rowClass = 'ProveedoresModel';

    public function removeProveedor( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_TRA = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_TRA = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getProveedorByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_TRA = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getProveedorByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('NOMBRE_TRA = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function addProveedor( $name, $adress, $tel, $fax, $mail )
    {
        $row = $this->getProveedorByName( $name );
        if (count($row))
        {
            $this->updateProveedor ($row->id(), $name, $adress, $tel, $fax, $mail );
        }
        else
        {
            $data = array(  'NOMBRE_TRA' => $name,
                            'DIRECCION_TRA' => $adress,
                            'TELEFONOS_TRA' => $tel,
                            'FAX_TRA' => $fax,
                            'MAIL_TRA' => $mail,
                        );
            $this->insert($data);
        }

        return True;
    }

    public function searchProveedor( $name, $sortby, $sorttype )
    {
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);
        $name = mysql_real_escape_string($name);
        
        if ($mySorttype == "desc")
            $mySorttype = "DESC";
        else
            $mySorttype = "ASC";
        
        switch ($sortby)
        {
            case 'name':
                $mySortby = "NOMBRE_TRA";
                break;
            case 'adress':
                $mySortby = "DIRECCION_TRA";
                break;
            case 'tel':
                $mySortby = "TELEFONOS_TRA";
                break;
            case 'fax':
                $mySortby = "FAX_TRA";
                break;
            case 'mail':
                $mySortby = "MAIL_TRA";
                break;
            default:
                $mySortby = "NOMBRE_TRA";
                break;
        }
        
        if ($name != "")
            $where = "NOMBRE_TRA LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->where("DELETED LIKE '0'")->order($mySortby . " " . $mySorttype); 
    }

    public function updateProveedor( $id, $name, $adress, $tel, $fax, $mail )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_TRA = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NOMBRE_TRA' => $name,
                            'DIRECCION_TRA' => $adress,
                            'TELEFONOS_TRA' => $tel,
                            'FAX_TRA' => $fax,
                            'MAIL_TRA' => $mail,
                            'DELETED'  => '0'
                            ), $where );

        return True;
    }
    
    public function modifyProveedor( $id, $name, $adress, $tel, $fax, $mail )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_TRA = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NOMBRE_TRA' => $name,
                            'DIRECCION_TRA' => $adress,
                            'TELEFONOS_TRA' => $tel,
                            'FAX_TRA' => $fax,
                            'MAIL_TRA' => $mail,
                            ), $where );

        return True;
    }

    public function getProveedoresArray()
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
