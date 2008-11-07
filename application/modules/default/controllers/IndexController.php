<?php
class IndexController extends Zend_Controller_Action
{
    protected $_form;

    public function init()
    {
        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }

    public function indexAction()
    {
        $this->view->headTitle("Trifiori Login");
        if (Zend_Auth::getInstance()->getIdentity() !== null)
        {
            if (Zend_Auth::getInstance()->getIdentity()->USUARIO_USU == "admin")
            {
                $this->_helper->redirector->gotoUrl('admin/panel');
            }
            else
            {
                $this->_helper->redirector->gotoUrl('user/main-page');
            }
        }
    }

    public function loginstateAction()
    {
        $registry = Zend_Registry::getInstance();

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['loginTrack']))
            {
                $this->_form = $this->getLoginForm();
                if ($this->_form->isValid($_POST))
                {
                    // process login
                    $values = $this->_form->getValues();

                    if ($registry->database != null)
                    {                       
                        $adapter = new Zend_Auth_Adapter_DbTable($registry->database);
                        $adapter->setTableName('USUARIOS');
                        $adapter->setIdentityColumn('USUARIO_USU');
                        $adapter->setCredentialColumn('PASSWORD_USU');
                        $adapter->setIdentity($values['username']);
                        $adapter->setCredential(
                            hash('SHA1', $values['password'])
                        );

                        // authentication attempt
                        $auth = Zend_Auth::getInstance();
                    
                        try
                        {
                            $result = $auth->authenticate($adapter);
                            $registry->validLogin = $result->isValid();
                        }
                        catch (Zend_Exception $error)
                        {
                            $this->view->error = $error;
                            $result->validLogin = False;
                        }
                    
                        // authentication succeeded
                        if ($registry->validLogin)
                        {
                            $auth->getStorage()->write($adapter->getResultRowObject(null, 'password'));
                            if (Zend_Auth::getInstance()->getIdentity()->USUARIO_USU == 'admin')
                            {
                                $this->_helper->redirector->gotoUrl('admin/panel');
                            }
                            else
                            {
                                $this->_helper->redirector->gotoUrl('user/main-page');
                            }
                        }
                        else
                        {
                            // or not! Back to the login page!
                            $this->view->failedAuthentication = true;
                        }
                    }
                    else
                    {
                        $this->view->error = "Imposible conectarse a la base de datos.";
                    }
                }
            }
        }

        $logged_in = $registry->user;
        $this->view->currentUrl= $this->_baseUrl;

        if (!$logged_in)
        {
            $this->view->loginForm = $this->getLoginForm();
            $this->_helper->viewRenderer->setRender('login');
        }
        else
        {
            $this->view->user = $registry->identity->name;
            $this->view->url = $this->_baseUrl;
        }
    }

    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
    }

    private function getLoginForm()
    {
        if (null !== $this->_form)
        {
            return $this->_form;
        }

        $this->_form = new Zend_Form();
        $this->_form->setAction($this->_baseUrl)->setMethod('post');

        // Create and configure username element:
        $username = $this->_form->createElement('text', 'username', array('label' => 'Usuario'));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_form->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,20))
                 ->setRequired(true);

        // Add elements to form:
        $this->_form->addElement($username)
             ->addElement($password)
             ->addElement('hidden', 'loginTrack', array('values' => 'logPost'))
             ->addElement('submit', 'login', array('label' => 'Ingresar'));
        return $this->_form;
    }
}
