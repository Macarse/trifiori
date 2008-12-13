<?php
class Cargas extends Zend_Db_Table_Abstract
{
    protected $_name = 'CARGAS';
    protected $_sequence = True;
    protected $_rowClass = 'CargasModel';

    public function removeCarga( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CAR = ?', $id);
        $this->delete( $where );
    }

    public function getCargaByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CAR = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
	
    public function getCargaByNroPaq( $name )
    {
        $where = $this->getAdapter()->quoteInto('NROPAQUETE_CAR = ?', $name);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addCarga( $cantBultos, $tipoEnvase, $peso, $unidad,
                              $nroPaquete, $marcaYnum, $mercIMCO )
    {
        $data = array(  'CANTBULTOS_CAR'    => $cantBultos,
                        'TIPOENVASE_CAR'    => $tipoEnvase,
                        'PESOBRUTO_CAR'     => $peso,
                        'UNIDAD_CAR'        => $unidad,
                        'NROPAQUETE_CAR'    => $nroPaquete,
                        'MARCAYNUMERO'      => $marcaYnum,
                        'MERC__IMCO'        => $mercIMCO,
                    );

        $this->insert($data);

        return True;
    }
    
    public function searchCarga( $name, $sortby, $sorttype )
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
            case 'bultos':
                $mySortby = "CANTBULTOS_CAR";
                break;
            case 'tipoenvase':
                $mySortby = "TIPOENVASE_CAR";
                break;
            case 'peso':
                $mySortby = "PESOBRUTO_CAR";
                break;
            case 'unidad':
                $mySortby = "UNIDAD_CAR";
                break;
            case 'nropaq':
                $mySortby = "NROPAQUETE_CAR";
                break;
            case 'marcayum':
                $mySortby = "MARCAYNUMERO";
                break;
            case 'imco':
                $mySortby = "MERC__IMCO";
                break;
            default:
                $mySortby = "NROPAQUETE_CAR";
                break;
        }
        
        if ($name != "")
            $where = "NROPAQUETE_CAR LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->order($mySortby . " " . $mySorttype);
    }
    
    public function modifyCarga( $id, $cantBultos, $tipoEnvase, $peso, $unidad,
                                 $nroPaquete, $marcaYnum, $mercIMCO )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_CAR = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array(
                            'CANTBULTOS_CAR'    => $cantBultos,
                            'TIPOENVASE_CAR'    => $tipoEnvase,
                            'PESOBRUTO_CAR'     => $peso,
                            'UNIDAD_CAR'        => $unidad,
                            'NROPAQUETE_CAR'    => $nroPaquete,
                            'MARCAYNUMERO'      => $marcaYnum,
                            'MERC__IMCO'        => $mercIMCO), $where );

        return True;
    }

    public function getCargasArray()
    {
        $arr = array();

        try
        {
            $cargas = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($cargas as $row)
        {
            $arr[ $row->id() ] = $row->nroPaquete();
        }

        return $arr;
    }

}

?>
