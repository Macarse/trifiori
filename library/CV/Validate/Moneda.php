<?php

class CV_Validate_Moneda extends Zend_Validate_Abstract
{
    const MSG_MONEDA = 'msgValidateMoneda';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Moneda Inválida');
        $this->_messageTemplates = array(self::MSG_MONEDA => $errorMsg);

        $this->_setValue($value);

 	    $model = new Monedas();
        try
        {
		   $data = $model->fetchAll("NAME_MON LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_MONEDA);
                return false;
            }
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return false;
        }

    }
}

?>
