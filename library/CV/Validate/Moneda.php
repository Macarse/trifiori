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

        $monedas = new Monedas();
        try
        {
            $codMoneda = $monedas->getMonedaByName($value);
            if ($codMoneda != NULL)
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