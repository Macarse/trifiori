<?php
class Canales extends Zend_Db_Table_Abstract
{
    protected $_name = 'CANALES';
    protected $_sequence = true;
    protected $_rowClass = 'CanalesModel';

    public function getCanalByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CAN = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getCanalesArray()
    {
        $arr = array();

        try
        {
            $Canales = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($Canales as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
