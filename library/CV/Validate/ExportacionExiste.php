<?php

class CV_Validate_ExportacionExiste extends Zend_Validate_Abstract
{
    const MSG_EXPORTACIONEXISTE = 'msgValidateExportacionExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La exportación ya existe');
        $this->_messageTemplates = array(self::MSG_EXPORTACIONEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Exportaciones();
            $data = $model->fetchAll("ORDEN LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_EXPORTACIONEXISTE);
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
