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

        try
        {
            $model = new Giros();
   		    $data = $model->fetchAll("SECCION_GIR LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
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
            return true;
        }
    }
}

?>
