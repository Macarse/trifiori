<?php

require_once 'tera-wurfl/tera_wurfl.php';

class Trifiori_Controller_Plugin_Twurfl extends Zend_Controller_Plugin_Abstract {

   public function preDispatch(Zend_Controller_Request_Abstract $request)
    {

        try
        {
            $tw = new Tera_Wurfl();

            $tw->getDeviceCapabilitiesFromAgent(
                    $request->getHeader('User-Agent'));

            if($tw->browser_is_wap)
            {
                $request->setModuleName('mobile');
            }
        }
        catch (Exception $e)
        {
            //Nada interesante para hacer. No se puede usar mobile.
        }

    }
}

?>
