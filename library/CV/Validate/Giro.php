<?php

class CV_Validate_Giro extends Zend_Validate_Abstract
{
    const MSG_GIRO = 'msgValidateGiro';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Giro Invalido');
        $this->_messageTemplates = array(self::MSG_GIRO => $errorMsg);

        $this->_setValue($value);

        $model = new Giros();
        try
        {
   		    $data = $model->fetchAll("SECCION_GIR LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_GIRO);
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
