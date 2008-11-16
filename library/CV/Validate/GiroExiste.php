<?php

class CV_Validate_GiroExiste extends Zend_Validate_Abstract
{
    const MSG_GIROEXISTE = 'msgValidateGiroExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El giro ya existe');
        $this->_messageTemplates = array(self::MSG_GIROEXISTE => $errorMsg);

        $this->_setValue($value);

        $giro = new Giros();
        try
        {
            $codGiro = $giro->getGiroBySeccion($value);
            if ($codGiro != NULL)
            {
                $this->_error(self::MSG_GIROEXISTE);
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