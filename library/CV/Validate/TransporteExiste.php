<?php

class CV_Validate_TransporteExiste extends Zend_Validate_Abstract
{
    const MSG_TRANSPORTEEXISTE = 'msgValidateTransporteExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El transporte ya existe');
        $this->_messageTemplates = array(self::MSG_TRANSPORTEEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Transportes();
		    $data = $model->fetchAll("NOMBRE_BUQ LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_TRANSPORTEEXISTE);
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
