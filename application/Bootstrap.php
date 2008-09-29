<?php

require_once 'Zend/Loader.php';
require_once 'models/models.php';

/**
 * this class provides a starting point for the Zend Framework MVC and general environemnt setup
 */
class Bootstrap
{
    private $frontController = null;
    private $root;

    public function __construct($root)
    {
        Zend_Loader::registerAutoload();

        $this->root = $root;
        $this->setupEnvironment();
        $this->setupRegistry();
        $this->readConfig();
        $this->setupDb();
        $this->setupMVC();
        $this->setupIdentity();
    }

    public function run()
    {
        $response = $this->frontController->dispatch();
        $this->sendResponse($response);
    }

    private function setupEnvironment()
    {
        error_reporting(E_ALL|E_STRICT);
        ini_set('display_errors', true);
        date_default_timezone_set('America/Buenos_Aires');
        Zend_Locale::setDefault('es_AR');
    }

    private function setupMVC()
    {
        $this->setupFrontController();
        $this->setupView();
    }

    private function setupFrontController()
    {
        $this->frontController = Zend_Controller_Front::getInstance();
        $this->frontController->throwExceptions();
        $this->frontController->returnResponse(true);
        $this->frontController->addModuleDirectory($this->root.'/application/modules');
    }

    private function setupView()
    {
        // Makes oages rendered are in UTF-8 encoding
        $view = new Zend_View;
        $view->setEncoding('UTF-8');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
/*
        $view->addHelperPath( $this->root. '/library/Forum/View/Helper',
                              'Forum_View_Helper_'
        );

        Zend_Layout::startMvc(array(
                'layoutPath' => $this->root. '/application/layouts',
                'layout' => 'common'
        ));
*/
    }

    private function sendResponse(Zend_Controller_Response_Http $response)
    {
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
        $response->sendResponse();
    }

    private function setupRegistry()
    {
        // Set the application registry to work as an object instead of an array
        $registry = new Zend_Registry(array(), ArrayObject::ARRAY_AS_PROPS);
        Zend_Registry::setInstance($registry);
    }

    private function readConfig()
    {
        $config = new Zend_Config_Ini($this->root . '/config/config.ini', 'general');
        Zend_Registry::getInstance()->configuration = $config;
    }

    private function setupDb()
    {
        $config = Zend_Registry::getInstance()->configuration;
        $db = Zend_Db::factory($config->db->adapter, $config->db->toArray());
        $db->query("SET NAMES 'utf8'");

        Zend_Registry::getInstance()->database = $db;
        Zend_Db_Table::setDefaultAdapter($db);
    }

    private function setupIdentity()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($identity == null)
        {
            Zend_Registry::getInstance()->user=false;
            Zend_Registry::getInstance()->admin=false;
        }
        else
        {
            Zend_Registry::getInstance()->identity=$identity;
            if ($identity->USUARIO_USU == 'admin')
            {
                Zend_Registry::getInstance()->admin=1;
            }
            else
            {
                Zend_Registry::getInstance()->admin=0;
            }

            Zend_Registry::getInstance()->user=true;
        }
    }

}

?>
