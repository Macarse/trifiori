<?php
class Lang extends Zend_Db_Table_Abstract
{
    protected $_name = 'IDIOMAS';
    protected $_sequence = true;
    protected $_rowClass = 'LangModel';

    /*TODO: Analizar si vamos a agregar ABM de lang al panel de admin*/

    public function getLangArray()
    {
        $arr = array();

        try
        {
            $langs = $this->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        foreach ($langs as $row)
        {
            $arr[ $row->id() ] = $row->name();
        }

        return $arr;
    }

}

?>