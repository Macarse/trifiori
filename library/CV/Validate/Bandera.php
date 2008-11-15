<?php
require_once 'Zend/Validate/Abstract.php';

class CV_Validate_Bandera extends Zend_Validate_Abstract
{
    const MSG_BANDERA = 'msgValidateBandera';

    protected $_messageTemplates = array(
        self::MSG_BANDERA => "Invalid Flag",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

		$banderas = new Banderas();
		try
		{
			$codBandera = $banderas->getBanderaByName($value);
			if ($codBandera != NULL)
				return true;
			else
			{
		            $this->_error(self::MSG_BANDERA);
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