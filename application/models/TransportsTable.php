<?php
class Transports extends Zend_Db_Table_Abstract
{
    protected $_name = 'TRANSPORTES';
    protected $_sequence = True;
    protected $_rowClass = 'TransportsModel';

    public function removeTransporte( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
        $this->delete( $where );
    }

    public function getTransporteByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_BUQ = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addTransporte( $codBandera, $codMedio, $name, $observaciones )
    {
        $data = array(  'CODIGO_BAN'         => $codBandera,
                        'CODIGOMED'          => $codMedio,
                        'NOMBRE_BUQ'         => $name,
                        'OBSERVACIONES_BUQ'  => $observaciones
                    );

        $this->insert($data);

        return True;
    }

    public function modifyBandera( $id, $codBandera, $codMedio, $name, $observaciones )
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

        $this->update(array(
                            'CODIGO_BAN'         => $codBandera,
                            'CODIGOMED'          => $codMedio,
                            'NOMBRE_BUQ'         => $name,
                            'OBSERVACIONES_BUQ'  => $observaciones), $where );

        return True;
    }

}

?>
