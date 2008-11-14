<?php
class Opps extends Zend_Db_Table_Abstract
{
    protected $_name = 'OPP';
    protected $_sequence = true;
    protected $_rowClass = 'OppsModel';


    public function removeOpp( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
        $this->delete( $where );
    }

    public function getOppByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_OPP = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addOpp($name, $declaracionOk, $pedidoDinero, $otrosOpp,
                           $fraccionado, $estampillas, $impuestosInternos)
    {
        /*TODO: Validaciones*/
        $data = array(  'NUMERO_OPP'              => $name,
                        'DECLARACION_OK_OPP'      => $declaracionOk,
                        'PEDIDO_DE_DINERO_OPP'    => $pedidoDinero,
                        'OTROS_OPP'               => $otrosOpp,
                        'FRACCIONADO_OPP'         => $fraccionado,
                        'ESTAMPILLAS_OPP'         => $estampillas,
                        'IMPUESTOS_INTERNOS_OPP'  => $impuestosInternos
                    );

        $this->insert($data);

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
            $opps = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($opps as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
