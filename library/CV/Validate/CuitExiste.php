<?php

class CV_Validate_CuitExiste extends Zend_Validate_Abstract
{
    const MSG_CUITEXISTE = 'msgValidateCuitExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El cuit ya existe');
        $this->_messageTemplates = array(self::MSG_CUITEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Clientes();
   		    $data = $model->fetchAll("CUIT_CLI LIKE '" .  $value . "' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_CUITEXISTE);
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
