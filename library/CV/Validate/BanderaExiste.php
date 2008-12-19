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

        try
        {
            $banderas = new Banderas();
            $codBandera = $banderas->fetchAll("NOMBRE_BAN LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($codBandera))
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
            return true;
        }

    }
}

?>
