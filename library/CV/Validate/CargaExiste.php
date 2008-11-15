<?php

class CV_Validate_CargaExiste extends Zend_Validate_Abstract
{
    const MSG_CARGAEXISTE = 'msgValidateCargaExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La carga ya existe');
        $this->_messageTemplates = array(self::MSG_CARGAEXISTE => $errorMsg);

        $this->_setValue($value);

        $cargas = new Cargas();
        try
        {
            $codCarga = $cargas->getCargaByNroPaq($value);
            if ($codCarga != NULL)
            {
                $this->_error(self::MSG_CARGAEXISTE);
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