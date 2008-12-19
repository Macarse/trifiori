<?php

class CV_Validate_ImportacionExiste extends Zend_Validate_Abstract
{
    const MSG_IMPORTACIONEXISTE = 'msgValidateImportacionExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La importación ya existe');
        $this->_messageTemplates = array(self::MSG_IMPORTACIONEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Importaciones();
            $data = $model->fetchAll("ORDEN_IMP LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_IMPORTACIONEXISTE);
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
