<?php
class Destinaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'DESTINACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'DestinacionesModel';

    public function removeDestinacion( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
        $this->delete( $where );
    }

    public function getDestinacionByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addDestinacion( $name )
    {
        /*TODO: Validaciones*/
        $data = array('DESCRIPCION_DES' => $name);
        $this->insert($data);

        return True;
    }

    public function modifyDestinacion( $id, $name )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_DES = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('DESCRIPCION_DES'    => $name), $where );

        return True;
    }

    public function getDestinacionesArray()
    {
        $arr = array();

        try
        {
            $Destinaciones = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($Destinaciones as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
