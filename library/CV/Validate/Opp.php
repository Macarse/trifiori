<?php

class CV_Validate_Opp extends Zend_Validate_Abstract
{
    const MSG_OPP = 'msgValidateOpp';
    protected $_messageTemplates = NULL;

    public function isValid($value)
    {
        $errorMsg = Zend_Registry::getInstance()->language->_('Opp Inválido');
        $this->_messageTemplates = array(self::MSG_OPP => $errorMsg);

        $this->_setValue($value);

        try
        {
            $model = new Opps();
		    $data = $model->fetchAll("NUMERO_OPP LIKE '" .  $value . "%' AND DELETED LIKE '0'");
            if (count($data))
                return true;
            else
            {
                $this->_error(self::MSG_OPP);
                return false;
            }
        }
        catch (Zend_Exception $e)
        {
            return true;
        }

    }
}

?>
