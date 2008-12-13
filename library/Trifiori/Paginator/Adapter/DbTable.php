<?php
class Trifiori_Paginator_Adapter_DbTable extends Zend_Paginator_Adapter_DbSelect
{
    protected $_table;

    public function __construct(Zend_Db_Table_Select $select, Zend_Db_Table_Abstract $table)
    {
        $this->_select = $select;
        $this->_table = $table;
    }

    public function getItems($offset, $itemCountPerPage)
    {
        $this->_select->limit($itemCountPerPage, $offset);

        return $this->_table->fetchAll($this->_select);
    }

//     public function count()
//     {
//         if ($this->_rowCount === null)
//         {
//             $expression = new Zend_Db_Expr('COUNT(*) AS ' . self::ROW_COUNT_COLUMN);
// 
//             $rowCount = clone $this->_select;
//             $rowCount->from($this->_table)
//                 ->reset(Zend_Db_Select::COLUMNS)
//                 ->reset(Zend_Db_Select::ORDER)
//                 ->reset(Zend_Db_Select::LIMIT_OFFSET)
//                 ->columns($expression);
// 
//             $this->setRowCount($rowCount);
//         }
// 
//         return $this->_rowCount;
//     }

}

