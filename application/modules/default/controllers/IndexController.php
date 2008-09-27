<?php
// class IndexController extends Forum_Default_Controller_Action
class IndexController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->headTitle("Usuarios registrados");
        $this->view->bugMsg = "Esto no deberia verse";
         $this->listarTodo();
        // Redirect to the default page
        //$this->_helper->redirector('index','view-topics');
    }

    public function listarTodo()
    {
        $table = new Users();
        $this->view->users = $table->fetchAll();
    }

    public function adsAction() {
		$ads = array("Anuncio 1", "Anuncio 2", "Anuncio 3", "Anuncio 4", "Anuncio 5");
		shuffle($ads);
		$this->view->ads = array_slice($ads, 0, 2);
    }
    
    public function loginstateAction() {
    	$registry = Zend_Registry::getInstance();
    	
		if ($this->getRequest()->isPost()) {
			if (isset($_POST['loginTrack'])) {
        		$form = $this->getLoginForm();
				if ($form->isValid($_POST)) {
					// process login
			        $values = $form->getValues();

			        $adapter = new Zend_Auth_Adapter_DbTable($registry->database);
			        $adapter->setTableName('Users');
			        $adapter->setIdentityColumn('login');
			        $adapter->setCredentialColumn('password');
			        $adapter->setIdentity($values['username']);
			        $adapter->setCredential(
			            hash('SHA1', $values['password'])
			        );
			
			        // authentication attempt
			        $auth = Zend_Auth::getInstance();
			        $result = $auth->authenticate($adapter);
			
			        // authentication succeeded
			        if ($result->isValid()) {
			            $auth->getStorage()->write($adapter->getResultRowObject(null, 'password'));

			            $this->_helper->redirector->gotoUrl($this->_baseUrl);
			        } else { // or not! Back to the login page!
			            $this->view->failedAuthentication = true;
			        }   
				}
			}
        }

        $logged_in = $registry->user;
    	$this->view->currentUrl= $this->_baseUrl;

    	if (!$logged_in) {
    		$this->view->loginForm = $this->getLoginForm();
    		$this->_helper->viewRenderer->setRender('login');
    	}
    	else {
    		$this->view->user = $registry->identity->name;
    		$this->view->url = $this->_baseUrl;
    	}
    }

    public function logoutAction() {
    	Zend_Auth::getInstance()->clearIdentity();
    	$this->_helper->redirector('index', 'view-topics');
    }
    
    
    private function getLoginForm() {
		$form = new Zend_Form();
		$form->setAction($this->_baseUrl)->setMethod('post');
		
		// Create and configure username element:
		$username = $form->createElement('text', 'username', array('label' => 'Usuario'));
		$username->addValidator('alnum')
		         ->addValidator('regex', false, array('/^[a-z]+/'))
		         ->addValidator('stringLength', false, array(4, 20))
		         ->setRequired(true)
		         ->addFilter('StringToLower');
		
		// Create and configure password element:
		$password = $form->createElement('password', 'password', array('label' => 'Clave'));
		$password->addValidator('StringLength', false, array(6))
		         ->setRequired(true);
		
		// Add elements to form:
		$form->addElement($username)
		     ->addElement($password)
		     ->addElement('hidden', 'loginTrack', array('values' => 'logPost'))
		     ->addElement('submit', 'login', array('label' => 'Ingresar'));
    	return $form;
    }
}   
