<?php
class Monedas extends Zend_Db_Table_Abstract
{
    protected $_name = 'MONEDAS';
    protected $_sequence = true;
    protected $_rowClass = 'MonedasModel';

    public function removeMoneda( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
        $this->delete( $where );
    }

    public function getMonedaByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_MON = ?', $id);
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
            $monedas = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($monedas as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>