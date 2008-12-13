<?php
class Clientes extends Zend_Db_Table_Abstract
{
    protected $_name = 'CLIENTES';
    protected $_sequence = true;
    protected $_rowClass = 'ClientesModel';


    public function removeCliente( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CLI = ?', $id);
        $this->delete( $where );
    }

    public function getClienteByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CLI = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
	
    public function getClienteByName( $name )
    {
        $where = $this->getAdapter()->quoteInto('NOMBRE_CLI = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getClienteByCUIT( $cuit )
    {
        $where = $this->getAdapter()->quoteInto('CUIT_CLI = ?', $cuit);
        $row = $this->fetchRow( $where );

        return $row;
    }
      
    public function addCliente($name, $dir, $CP, $localidad, $cuit, $tipoIVA, $tipoCliente )
    {
        /*TODO: Validaciones*/
        $data = array('NOMBRE_CLI'       => $name,
                      'DIRECCION_CLI'    => $dir,
                      'CODIGOPOSTAL_CLI' => $CP,
                      'LOCALIDAD_CLI'    => $localidad,
                      'CUIT_CLI'         => $cuit,
                      'TIPOIVA_CLI'      => $tipoIVA,
                      'TIPOCLIENTE_CLI'  => $tipoCliente
                    );

        $this->insert($data);

        return True;
    }
    
    public function searchCliente( $name, $sortby, $sorttype )
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
                $mySortby = "NOMBRE_CLI";
                break;
            case 'address':
                $mySortby = "DIRECCION_CLI";
                break;
            case 'cp':
                $mySortby = "CODIGOPOSTAL_CLI";
                break;
            case 'local':
                $mySortby = "LOCALIDAD_CLI";
                break;
            case 'cuit':
                $mySortby = "CUIT_CLI";
                break;
            case 'iva':
                $mySortby = "TIPOIVA_CLI";
                break;
            case 'type':
                $mySortby = "TIPOCLIENTE_CLI";
                break;
            default:
                $mySortby = "NOMBRE_CLI";
                break;
        }
        
        if ($name != "")
            $where = "NOMBRE_CLI LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->order($mySortby . " " . $mySorttype); 
    }
    
    public function modifyCliente( $id, $name, $dir, $CP, $localidad, $cuit, $tipoIVA, $tipoCliente )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_CLI = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array(    'NOMBRE_CLI'       => $name,
                                'DIRECCION_CLI'    => $dir,
                                'CODIGOPOSTAL_CLI' => $CP,
                                'LOCALIDAD_CLI'    => $localidad,
                                'CUIT_CLI'         => $cuit,
                                'TIPOIVA_CLI'      => $tipoIVA,
                                'TIPOCLIENTE_CLI'  => $tipoCliente
                            ), $where );

        return True;
    }

    public function getClientesArray()
    {
        $arr = array();

        try
        {
            $clientes = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($clientes as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
