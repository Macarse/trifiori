<?php

class CV_Validate_UsuarioExiste extends Zend_Validate_Abstract
{
    const MSG_USUARIOEXISTE = 'msgValidateUsuarioExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El usuario ya existe');
        $this->_messageTemplates = array(self::MSG_USUARIOEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Users();
   		    $data = $model->fetchAll("NOMBRE_USU LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_USUARIOEXISTE);
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
