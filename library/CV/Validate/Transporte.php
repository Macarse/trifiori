<?php

class CV_Validate_Transporte extends Zend_Validate_Abstract
{
    const MSG_TRANSPORTE = 'msgValidateTransporte';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Transporte Inválido');
        $this->_messageTemplates = array(self::MSG_TRANSPORTE => $errorMsg);

        $this->_setValue($value);

        $transporte = new Transportes();
        try
        {
            $codTransporte = $transporte->getTransporteByName($value);
            if ($codTransporte != NULL)
                return true;
            else
            {
                $this->_error(self::MSG_TRANSPORTE);
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