<?php

class CV_Validate_BanderaExiste extends Zend_Validate_Abstract
{

    const MSG_BANDERAEXISTE = 'msgValidateBanderaExiste';
    protected $_messageTemplates = NULL;


    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La bandera ya existe');
        $this->_messageTemplates = array(self::MSG_BANDERAEXISTE => $errorMsg);

        $this->_setValue($value);

        $banderas = new Banderas();
        try
        {
            $codBandera = $banderas->getBanderaByName($value);
            if ($codBandera != NULL)
            {
                $this->_error(self::MSG_BANDERAEXISTE);
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