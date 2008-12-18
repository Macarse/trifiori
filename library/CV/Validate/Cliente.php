<?php

class CV_Validate_Cliente extends Zend_Validate_Abstract
{
    const MSG_CLIENTE = 'msgValidateCliente';
    protected $_messageTemplates = NULL;


    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Cliente Inválido');
        $this->_messageTemplates = array(self::MSG_CLIENTE => $errorMsg);
        $this->_setValue($value);

        try
        {
            $model = new Clientes();
   		    $data = $model->fetchAll("NOMBRE_CLI LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_CLIENTE);
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
