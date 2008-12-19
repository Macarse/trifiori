<?php

class CV_Validate_MonedaExiste extends Zend_Validate_Abstract
{
    const MSG_MONEDAEXISTE = 'msgValidateMonedaExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La moneda ya existe');
        $this->_messageTemplates = array(self::MSG_MONEDAEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
     	    $model = new Monedas();
		    $data = $model->fetchAll("NAME_MON LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_MONEDAEXISTE);
                return false;
            }
            else
            {
                return true;
            }
        }
        catch (Zend_Exception $e)
        {
            return true;
        }

    }
}

?>
