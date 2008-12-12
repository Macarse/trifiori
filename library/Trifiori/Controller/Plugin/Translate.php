<?php

class Trifiori_Controller_Plugin_Translate extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {

        $language = Zend_Registry::getInstance()->language;

        if (isset(Zend_Registry::getInstance()->identity) )
        {
            $user = Zend_Registry::getInstance()->identity->USUARIO_USU;

            if ($user != 'guest')
            {
                try
                {
                    $userTable = new Users();
                    $userRow = $userTable->getUserByName($user);

                    if ($userRow->language() == 'en')
                    {
                        $language->setLocale('en');
                        Zend_Registry::getInstance()->language = $language;
                    }
                    else
                    {
                        $language->setLocale('es');
                        Zend_Registry::getInstance()->language = $language;
                    }
                }
                catch (Zend_Exception $error)
                {
//                     Nada que hacer
                }
            }
        }
    }
}
?>