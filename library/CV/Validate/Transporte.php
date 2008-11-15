<?php
require_once 'Zend/Validate/Abstract.php';

class CV_Validate_Transporte extends Zend_Validate_Abstract
{
    const MSG_TRANSPORTE = 'msgValidateTransporte';

    protected $_messageTemplates = array(
        self::MSG_TRANSPORTE => "Invalid Transport",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

		$transporte = new Transportes();
		try
		{
			$codTransporte = $transporte->getTransporteByName($value);
			if ($codTransporte != NULL)
				return true;
			else
			{
		            $this->_error(self::MSG_TRANSPORTE);
		            return false;
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