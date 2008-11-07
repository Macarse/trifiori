<?php
class Css extends Zend_Db_Table_Abstract
{
    protected $_name = 'CSS';
    protected $_sequence = True;
    protected $_rowClass = 'CssModel';

    public function getCssByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_CSS = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function getCssArray()
    {
        $arr = array();

        try
        {
            $css = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($css as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>