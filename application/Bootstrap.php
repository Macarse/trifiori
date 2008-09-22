<?php

require_once 'Zend/Loader.php';

/**
 * this class provides a starting point for the Zend Framework MVC and general environemnt setup
 */
class Bootstrap {
	private $frontController = null;
	
	public function __construct() {
         Zend_Loader::registerAutoload();
         $this->setupEnvironment();
		 $this->setupMVC();
	}
	
	public function run() {
      	$response = $this->frontController->dispatch();
        $this->sendResponse($response);		
	}
	
	private function setupEnvironment() {
      	error_reporting(E_ALL|E_STRICT);
        ini_set('display_errors', true);
        date_default_timezone_set('America/Buenos_Aires');		
	}
	
	private function setupMVC() {
		$this->setupFrontController();
		$this->setupView();
	}
	
	private function setupFrontController() {
        $this->frontController = Zend_Controller_Front::getInstance();
        $this->frontController->throwExceptions();
        $this->frontController->returnResponse(true);
        $this->frontController->setControllerDirectory(dirname(__FILE__).'/controllers');
	}
	
	private function setupView() {
		// Makes oages rendered are in UTF-8 encoding
        $view = new Zend_View;
        $view->setEncoding('UTF-8');
        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	}
	
  	private function sendResponse(Zend_Controller_Response_Http $response) {
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8', true);
        $response->sendResponse();
    }
}

?>
