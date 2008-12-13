<?php
class Log extends Zend_Db_Table_Abstract
{
    protected $_name = 'LOGS';
    protected $_sequence = true;
    protected $_rowClass = 'LogModel';

    public function getLogById( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGOLOG = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function searchLog( $msg , $sortby , $sorttype)
    {
        $msg = mysql_real_escape_string($msg);
        $mySortby = mysql_real_escape_string($sortby);
        $mySorttype = mysql_real_escape_string($sorttype);

        if ($mySorttype == "asc")
            $mySorttype = "ASC";
        else
            $mySorttype = "DESC";
        
        if ($mySortby == "id")
            $mySortby = "CODIGOLOG";
        else
            $mySortby = "NIVEL";
        
        if ($msg != "")
            $where = "MSG LIKE '%ALTERANDO%' AND MSG LIKE '%" . $msg . "%'";
        else
            $where = "MSG LIKE '%ALTERANDO%'";
        
        return $this->select()->from($this)->where($where)->order($mySortby . " " . $mySorttype);
    }
}

?>