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

        $clientes = new Clientes();
        try
        {
            $codCliente = $clientes->getClienteByName($value);
            if ($codCliente != NULL)
                return true;
            else
            {
                $this->_error(self::MSG_CLIENTE);
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