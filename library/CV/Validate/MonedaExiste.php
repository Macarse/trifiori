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

        $monedas = new Monedas();
        try
        {
            $codMoneda = $monedas->getMonedaByName($value);
            if ($codMoneda != NULL)
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
            throw new Exception($e->getMessage());
            return false;
        }

    }
}

?>