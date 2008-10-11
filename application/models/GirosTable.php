<?php
class Giros extends Zend_Db_Table_Abstract
{
    protected $_name = 'GIROS';
    protected $_sequence = true;
    protected $_rowClass = 'GirosModel';

    public function removeGiro( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
        $this->delete( $where );
    }

    public function getGiroByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addGiro( $name )
    {
        /*TODO: Validaciones*/
        $data = array('SECCION_GIR' => $name);
        $this->insert($data);

        return True;
    }

    public function modifyGiro( $id, $name )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_GIR = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('SECCION_GIR'    => $name), $where );

        return True;
    }

    public function getGirosArray()
    {
        $arr = array();

        try
        {
            $giros = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($giros as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
