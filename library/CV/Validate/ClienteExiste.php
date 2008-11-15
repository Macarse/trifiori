<?php

class CV_Validate_ClienteExiste extends Zend_Validate_Abstract
{
    const MSG_CLIENTEEXISTE = 'msgValidateClienteExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El cliente ya existe');
        $this->_messageTemplates = array(self::MSG_CLIENTEEXISTE => $errorMsg);

        $this->_setValue($value);

        $cliente = new Clientes();
        try
        {
            $codCliente = $cliente->getClienteByCUIT($value);
            if ($codCliente != NULL)
            {
                $this->_error(self::MSG_CLIENTEEXISTE);
                return false;
            }
            else
            {
                return true;
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