<?php

require_once 'Zend/Loader.php';
require_once 'models/models.php';

/*
 * this class provides a starting point for the Zend Framework MVC and general environment setup
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
        $this->setupIdentity();
        $this->setupMail();
        $this->setupTranslate();
        $this->setupMVC();

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

        // Registrar los plugins de ACL y Log.
        $this->frontController->registerPlugin( new Trifiori_Controller_Plugin_ACL() );
        $this->frontController->registerPlugin( new Trifiori_Controller_Plugin_Log() );

        // Registrar el plugin de Translate.
        $this->frontController->registerPlugin( new Trifiori_Controller_Plugin_Translate() );

        // Mobile!
        $this->frontController->registerPlugin(new Trifiori_Controller_Plugin_Twurfl());
    }

    private function setupView()
    {
        // Makes sure pages rendered are in UTF-8 encoding
        $view = new Zend_View;
        $view->setEncoding('UTF-8');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

        Zend_Layout::startMvc(array(
                'layoutPath' => $this->root . '/application/layouts',
                'layout' => 'common'
        ));
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

        try
        {
            $db = Zend_Db::factory($config->db->adapter, $config->db->toArray());
            $db->query("SET NAMES 'utf8'");

            Zend_Registry::getInstance()->database = $db;
            Zend_Db_Table::setDefaultAdapter($db);
        }
        catch (Zend_Exception $error)
        {
            Zend_Registry::getInstance()->database = null;
        }

    }

    private function setupMail()
    {

        $config = Zend_Registry::getInstance()->configuration;

        $cfg = array(   'auth' => 'login',
                        'username' => $config->gmail->username,
                        'password' => $config->gmail->password,
                        'ssl' => 'tls'
                    );

        $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $cfg);

        Zend_Registry::getInstance()->mailTransport = $transport;

    }


    private function setupTranslate()
    {
        $translate = new Zend_Translate('gettext', $this->root . '/application/languages/en.mo', 'en');
        $translate->addTranslation($this->root . '/application/languages/es.mo', 'es');
        Zend_Registry::getInstance()->language = $translate;
        Zend_Form::setDefaultTranslator($translate);
    }

    private function setupIdentity()
    {
        $identity = Zend_Auth::getInstance()->getIdentity();
        if ($identity == null)
        {
            Zend_Registry::getInstance()->user=false;
            Zend_Registry::getInstance()->admin=false;
            Zend_Registry::getInstance()->name = 'guest';
        }
        else
        {
            Zend_Registry::set('identity', $identity);

            if ($identity->USUARIO_USU == 'admin')
            {
                Zend_Registry::getInstance()->name = 'admin';
                Zend_Registry::getInstance()->admin=1;
            }
            else
            {
                Zend_Registry::getInstance()->name = 'user';
                Zend_Registry::getInstance()->admin=0;
            }

            Zend_Registry::getInstance()->user=true;
        }
    }
}

?>
