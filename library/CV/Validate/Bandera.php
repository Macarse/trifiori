<?php

class CV_Validate_Bandera extends Zend_Validate_Abstract
{

    const MSG_BANDERA = 'msgValidateBandera';
    protected $_messageTemplates = NULL;


    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Bandera Inválida');
        $this->_messageTemplates = array(self::MSG_BANDERA => $errorMsg);

        $this->_setValue($value);

        $banderas = new Banderas();
        try
        {
            $codBandera = $banderas->getBanderaByName($value);
            if ($codBandera != NULL)
                return true;
            else
            {
                $this->_error(self::MSG_BANDERA);
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