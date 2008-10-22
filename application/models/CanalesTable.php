<?php
class Canales extends Zend_Db_Table_Abstract
{
    protected $_name = 'CANALES';
    protected $_sequence = true;
    protected $_rowClass = 'CanalesModel';

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
