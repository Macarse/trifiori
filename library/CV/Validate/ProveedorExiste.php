<?php

class CV_Validate_ProveedorExiste extends Zend_Validate_Abstract
{
    const MSG_PROVEEDOREXISTE = 'msgValidateProveedorExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El proveedor ya existe');
        $this->_messageTemplates = array(self::MSG_PROVEEDOREXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Proveedores();
            $data = $model->fetchAll("NOMBRE_TRA LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_PROVEEDOREXISTE);
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
