<?php
class Opps extends Zend_Db_Table_Abstract
{
    protected $_name = 'OPP';
    protected $_sequence = true;
    protected $_rowClass = 'OppsModel';


    public function removeOpp( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
        //$this->delete( $where );
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DELETED'    => '1'), $where );
    }

    public function getOppByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function getOppByNumero( $num )
    {
        $where = $this->getAdapter()->quoteInto('NUMERO_OPP = ?', $num);
        $row = $this->fetchRow( $where );

        return $row;
    }
    
    public function searchOpp( $name, $sortby, $sorttype )
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
                $mySortby = "NUMERO_OPP";
                break;
            case 'declaracionOk':
                $mySortby = "DECLARACION_OK_OPP";
                break;
            case 'pedidoDinero':
                $mySortby = "PEDIDO_DE_DINERO_OPP";
                break;
            case 'otrosOpp':
                $mySortby = "OTROS_OPP";
                break;
            case 'fraccionado':
                $mySortby = "FRACCIONADO_OPP";
                break;
            case 'estampillas':
                $mySortby = "ESTAMPILLAS_OPP";
                break;
            case 'impuestosInternos':
                $mySortby = "IMPUESTOS_INTERNOS_OPP";
                break;
            default:
                $mySortby = "NUMERO_OPP";
                break;
        }
        
        if ($name != "")
            $where = "NUMERO_OPP LIKE '%" . $name . "%'";
        else
            $where = "1=1";
        
        return $this->select()->from($this)->where($where)->where("DELETED LIKE '0'")->order($mySortby . " " . $mySorttype); 
    }

    protected function translateDate($value)
    {

        if ($value == '')
            return $value;

        $lang = Zend_Registry::getInstance()->language->getLocale();

        if( $lang == 'es' )
        {
            $date = new Zend_Date($value,'dd-MM-YYYY');
        }
        else if( $lang == 'en' )
        {
            $date = new Zend_Date($value,'MM-dd-YYYY');
        }

        $retVal = $date->get('YYYY-MM-dd');

        return $retVal;
    }

    public function addOpp($name, $declaracionOk, $pedidoDinero, $otrosOpp,
                           $fraccionado, $estampillas, $impuestosInternos)
    {

        $pedidoDinero = $this->translateDate($pedidoDinero);

        $row = $this->getOppByNumero( $name );
        if (count($row))
        {
            $this->updateOpp ($row->id(), $name, $declaracionOk, $pedidoDinero, $otrosOpp,
                           $fraccionado, $estampillas, $impuestosInternos );
        }
        else
        {
            $data = array(  'NUMERO_OPP'              => $name,
                            'DECLARACION_OK_OPP'      => $declaracionOk,
                            'PEDIDO_DE_DINERO_OPP'    => $pedidoDinero,
                            'OTROS_OPP'               => $otrosOpp,
                            'FRACCIONADO_OPP'         => $fraccionado,
                            'ESTAMPILLAS_OPP'         => $estampillas,
                            'IMPUESTOS_INTERNOS_OPP'  => $impuestosInternos
                        );
            $this->insert($data);
        }

        return True;
    }

    private function updateOpp( $id, $name, $declaracionOk, $pedidoDinero, $otrosOpp,
                           $fraccionado, $estampillas, $impuestosInternos)
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $pedidoDinero = $this->translateDate($pedidoDinero);

        $this->update(array(    'NUMERO_OPP'              => $name,
                                'DECLARACION_OK_OPP'      => $declaracionOk,
                                'PEDIDO_DE_DINERO_OPP'    => $pedidoDinero,
                                'OTROS_OPP'               => $otrosOpp,
                                'FRACCIONADO_OPP'         => $fraccionado,
                                'ESTAMPILLAS_OPP'         => $estampillas,
                                'IMPUESTOS_INTERNOS_OPP'  => $impuestosInternos,
                                'DELETED'                 => '0'
                            ), $where );

        return True;
    }

    public function modifyOpp( $id, $name, $declaracionOk, $pedidoDinero, $otrosOpp,
                           $fraccionado, $estampillas, $impuestosInternos)
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $pedidoDinero = $this->translateDate($pedidoDinero);

        $this->update(array(    'NUMERO_OPP'              => $name,
                                'DECLARACION_OK_OPP'      => $declaracionOk,
                                'PEDIDO_DE_DINERO_OPP'    => $pedidoDinero,
                                'OTROS_OPP'               => $otrosOpp,
                                'FRACCIONADO_OPP'         => $fraccionado,
                                'ESTAMPILLAS_OPP'         => $estampillas,
                                'IMPUESTOS_INTERNOS_OPP'  => $impuestosInternos
                            ), $where );

        return True;
    }

    public function getOppsArray()
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
