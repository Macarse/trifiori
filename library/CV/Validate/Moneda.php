<?php
require_once 'Zend/Validate/Abstract.php';

class CV_Validate_Moneda extends Zend_Validate_Abstract
{
    const MSG_MONEDA = 'msgValidateMoneda';

    protected $_messageTemplates = array(
        self::MSG_MONEDA => "Invalid Currency",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

		$monedas = new Monedas();
		try
		{
			$codMoneda = $monedas->getMonedaByName($value);
			if ($codMoneda != NULL)
				return true;
			else
			{
		            $this->_error(self::MSG_MONEDA);
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