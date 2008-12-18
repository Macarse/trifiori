<?php

class CV_Validate_Destinacion extends Zend_Validate_Abstract
{
    const MSG_DESTINACION = 'msgValidateDestinacion';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Destinación Inválida');
        $this->_messageTemplates = array(self::MSG_DESTINACION => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Destinaciones();
            $data = $model->fetchAll("DESCRIPCION_DES LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_DESTINACION);
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
