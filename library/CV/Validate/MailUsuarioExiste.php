<?php

class CV_Validate_MailUsuarioExiste extends Zend_Validate_Abstract
{
    const MSG_MAILUSUARIOEXISTE = 'msgValidateMailUsuarioExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('El mail ya existe');
        $this->_messageTemplates = array(self::MSG_MAILUSUARIOEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Users();
   		    $data = $model->fetchAll("EMAIL_USU LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_MAILUSUARIOEXISTE);
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
