<?php
class Puertos extends Zend_Db_Table_Abstract
{
    protected $_name = 'PUERTOS';
    protected $_sequence = true;
    protected $_rowClass = 'PuertosModel';

    public function removePuerto( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
        $this->delete( $where );
    }

    public function getPuertoByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addPuerto( $name, $ubicacion )
    {
        /*TODO: Validaciones*/
        $data = array(  'NOMBRE_PUE' => $name,
                        'UBICACION_PUE' => $ubicacion,

                    );
        $this->insert($data);

        return True;
    }
    
    public function searchPuerto( $name )
    {
        $name = mysql_real_escape_string($name);
        return $this->select()->where("NOMBRE_PUE LIKE '%" . $name . "%'"); 
    }
    
    public function modifyPuerto( $id, $name, $ubicacion )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_PUE = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array('NOMBRE_PUE'    => $name,
                            'UBICACION_PUE'    => $ubicacion,
                            ), $where );

        return True;
    }

    public function getPuertosArray()
    {
        $arr = array();

        try
        {
            $Puertos = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($Puertos as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>
