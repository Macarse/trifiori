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

        $exportacion = new Exportaciones();
        try
        {
            $codExportacion = $exportacion->getExportacionByOrden($value);
            if ($codExportacion != NULL)
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
            throw new Exception($e->getMessage());
            return false;
        }
    }
}

?>