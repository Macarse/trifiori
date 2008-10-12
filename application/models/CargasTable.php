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

}

?>
