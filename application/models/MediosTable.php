<?php
class Medios extends Zend_Db_Table_Abstract
{
    protected $_name = 'MEDIOS';
    protected $_sequence = True;
    protected $_rowClass = 'MediosModel';

    /*TODO: Analizar si vamos a agregar ABM de Medios en user*/

    public function getMedioByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGOMED = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getMediosArray()
    {
        $arr = array();

        try
        {
            $medios = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($medios as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>