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

        $exportacion = new Importaciones();
        try
        {
            $codImportacion = $exportacion->getImportacionByOrden($value);
            if ($codImportacion != NULL)
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
            throw new Exception($e->getMessage());
            return false;
        }
    }
}

?>