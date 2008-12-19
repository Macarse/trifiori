<?php

class CV_Validate_OPPExiste extends Zend_Validate_Abstract
{
    const MSG_OPPEXISTE = 'msgValidateOPPExiste';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('La OPP ya existe');
        $this->_messageTemplates = array(self::MSG_OPPEXISTE => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Opps();
		    $data = $model->fetchAll("NUMERO_OPP LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
            {
                $this->_error(self::MSG_OPPEXISTE);
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
