<?php

class CV_Validate_DestinacionExiste extends Zend_Validate_Abstract
{
    const MSG_DESTINACIONEXISTE = 'msgValidateDestinacionExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La destinación ya existe');
        $this->_messageTemplates = array(self::MSG_DESTINACIONEXISTE => $errorMsg);

        $this->_setValue($value);

        $destinacion = new Destinaciones();
        try
        {
            $codDestinacion = $destinacion->getDestinacionByName($value);
            if ($codDestinacion != NULL)
            {
                $this->_error(self::MSG_DESTINACIONEXISTE);
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