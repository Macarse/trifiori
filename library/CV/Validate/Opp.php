<?php

class CV_Validate_Opp extends Zend_Validate_Abstract
{
    const MSG_OPP = 'msgValidateOpp';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Opp Invalido');
        $this->_messageTemplates = array(self::MSG_OPP => $errorMsg);

        $this->_setValue($value);

        $opp = new Opps();
        try
        {
            $codOpp = $opp->getOppByNumero($value);
            if ($codOpp != NULL)
                return true;
            else
            {
                $this->_error(self::MSG_OPP);
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