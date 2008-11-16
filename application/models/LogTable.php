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
    
    public function searchLog( $msg )
    {
        $msg = mysql_real_escape_string($msg);

        return $this->select()->where("MSG LIKE '%ALTERANDO%' AND MSG LIKE '%" . $msg . "%'"); 
    }
}

?>