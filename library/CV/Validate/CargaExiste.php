<?php

class CV_Validate_CargaExiste extends Zend_Validate_Abstract
{
    const MSG_CARGAEXISTE = 'msgValidateCargaExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La carga ya existe');
        $this->_messageTemplates = array(self::MSG_CARGAEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Cargas();
            $data = $model->fetchAll("NROPAQUETE_CAR LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_CARGAEXISTE);
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
