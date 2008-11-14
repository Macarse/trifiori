<?php
require_once 'Zend/Validate/Abstract.php';

class CV_Validate_Destinacion extends Zend_Validate_Abstract
{
    const MSG_DESTINACION = 'msgValidateDestinacion';

    protected $_messageTemplates = array(
        self::MSG_DESTINACION => "Invalid Destination",
    );

    public function isValid($value)
    {
        $this->_setValue($value);

		$destinacion = new Destinaciones();
		try
		{
			$codDestinacion = $destinacion->getDestinacionByDesc($value);
			if ($codDestinacion != NULL)
				return true;
			else
			{
		            $this->_error(self::MSG_DESTINACION);
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