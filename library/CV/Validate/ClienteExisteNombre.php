<?php

class CV_Validate_ClienteExisteNombre extends Zend_Validate_Abstract
{
    const MSG_CLIENTEEXISTENOMBRE = 'msgValidateClienteExisteNombre';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El cliente ya existe');
        $this->_messageTemplates = array(self::MSG_CLIENTEEXISTENOMBRE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Clientes();
   		    $data = $model->fetchAll("NOMBRE_CLI LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_CLIENTEEXISTENOMBRE);
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
