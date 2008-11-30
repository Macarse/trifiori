<?php
class Mobile_IndexController extends Trifiori_Mobile_Controller_Action
{
    protected $_form;

    public function indexAction()
    {
        $this->view->headTitle("Trifiori MOBILE");

        unset($this->view->error);
        if (setcookie("test", "test", time() + 100))
        {
            if (isset ($_COOKIE['test']))
            {
                if (Zend_Auth::getInstance()->getIdentity() !== null)
                {

                    $user = Zend_Auth::getInstance()->getIdentity()->USUARIO_USU;

                    if ($this->$user == "admin")
                    {
                        $this->_helper->redirector->gotoUrl('mobile/admin');
                    }
                    else
                    {
                        $this->_helper->redirector->gotoUrl('mobile/user');
                    }
                }
            }
            else
            {
                $this->view->error = $this->language->_("Su browser no soporta cookies. No podrá navegar el sitio.");
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
                                $this->_helper->redirector->gotoUrl('mobile/admin');
                            }
                            else
                            {
                                $this->_helper->redirector->gotoUrl('mobile/user');
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
                        $this->view->error = $this->language->_("Imposible conectarse a la base de datos.");
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
        $username = $this->_form->createElement('text', 'username', array('label' => $this->language->_('Usuario')));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_form->createElement('password', 'password', array('label' => $this->language->_('Clave')));
        $password->addValidator('StringLength', false, array(1,20))
                 ->setRequired(true);

        // Add elements to form:
        $this->_form->addElement($username)
             ->addElement($password)
             ->addElement('hidden', 'loginTrack', array('values' => 'logPost'))
             ->addElement('submit', 'login', array('label' => $this->language->_('Entrar')));
        return $this->_form;
    }
}
