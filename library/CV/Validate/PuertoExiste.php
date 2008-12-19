<?php

class CV_Validate_PuertoExiste extends Zend_Validate_Abstract
{
    const MSG_PUERTOEXISTE = 'msgValidatePuertoExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El puerto ya existe');
        $this->_messageTemplates = array(self::MSG_PUERTOEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Puertos();
            $data = $model->fetchAll("NOMBRE_PUE LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_PUERTOEXISTE);
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
