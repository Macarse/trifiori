<?php
require_once 'Zend/Validate/Abstract.php';

class CV_Validate_Carga extends Zend_Validate_Abstract
{
    const MSG_CARGA = 'msgValidateCarga';

    protected $_messageTemplates = array(
        self::MSG_CARGA => "Invalid Load",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

		$cargas = new Cargas();
		try
		{
			$codCarga = $cargas->getCargaByNroPaq($value);
			if ($codCarga != NULL)
				return true;
			else
			{
		            $this->_error(self::MSG_CARGA);
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