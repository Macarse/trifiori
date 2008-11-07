<?php

class Trifiori_Controller_Plugin_Log extends Zend_Controller_Plugin_Abstract
{
    public function __construct()
    {
        // mapeo de columnas de la tabla de Logs
        $colMapping = array('NIVEL' => 'priority', 'MSG' => 'message');
        
        $db = Zend_Registry::get('database');
        
        if ($db != null)
        {
            $writer = new Zend_Log_Writer_Db($db, 'LOGS', $colMapping);

        
            $logger = new Zend_Log($writer);
            Zend_Registry::set('logger', $logger);
        }
    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $reg = Zend_Registry::getInstance(); 
        if (isset($reg['logger']))
        {
            $logger = Zend_Registry::get('logger');
            $identity = Zend_Auth::getInstance()->getIdentity();
        
            if ($identity != null)
            {
                $username = Zend_Registry::getInstance()->identity->USUARIO_USU;
            }
            else
            {
                $username = Zend_Registry::get('name');
            }
        
            $module = $request->getModuleName();
            $controller = $request->getControllerName();
            $action = $request->getActionName();
        
            $msg = $username;
            switch ($action)
            {   
                case strpos($action, "remove"):
                    $msg = $msg . " ALTERANDO " . $controller . " Eliminado id " . 
                        $request->getParam('id') . ".";
                    break;
                case strpos($action, "mod"):
                    $msg = $msg . " ALTERANDO " . $controller . " Modificando id " . 
                        $request->getParam('id') . ".";
                    break;
                case strpos($action, "add"):
                    $msg = $msg . " ALTERANDO " . $controller .  " Agregando nuevo.";
                    break;
                default:
                    $msg = $msg . " accediendo a " . $module . "/" . 
                        $controller . "/" . $action;
                    break;
            }
        
            $logger->info($msg);
        }    
    }
    
    public function postDispatch( Zend_Controller_Request_Abstract $request )
    {
        $reg = Zend_Registry::getInstance();
        if (isset($reg['logger']))
        {
            $logger = Zend_Registry::get('logger');

            if (Zend_Registry::isRegistered('validLogin'))
            {
                $validLogin = Zend_Registry::get('validLogin');
                if ( !$validLogin )
                {
                    $msg = "Login erroneo desde " . $_SERVER['REMOTE_ADDR'];
                    $logger->emerg($msg);
                }
            }
        }

    }
}
?>