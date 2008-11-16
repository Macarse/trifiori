<?php

class CV_Validate_Fecha extends Zend_Validate_Abstract
{
    const MSG_FECHA = 'msgValidateFecha';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Fecha Inválida. Debe ser dd-MM-YYYY.');
        $this->_messageTemplates = array(self::MSG_FECHA => $errorMsg);

        $this->_setValue($value);

        $lang = Zend_Registry::getInstance()->language->getLocale();
        $retVal = null;

        if( $lang == 'es' )
        {
            $retVal =  Zend_Date::isDate($value, 'dd-MM-YYYY', 'es');
        }
        else if( $lang == 'en' )
        {
            $retVal = Zend_Date::isDate($value, 'MM-dd-YYYY', 'en');
        }

        if ($retVal == False)
            $this->_error(self::MSG_FECHA);

        return $retVal;
    }
}

?>