<?php

class CV_Validate_Carga extends Zend_Validate_Abstract
{
    const MSG_CARGA = 'msgValidateCarga';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Carga Inválida');
        $this->_messageTemplates = array(self::MSG_CARGA => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Cargas();
            $data = $model->fetchAll("NROPAQUETE_CAR LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_CARGA);
                return false;
            }
        }
        catch (Zend_Exception $e)
        {
            return true;
        }

    }
}

?>
