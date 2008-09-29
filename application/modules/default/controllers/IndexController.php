<?php
class IndexController extends Trifiori_Default_Controller_Action
{
    public function indexAction()
    {
        $this->view->headTitle("Trifiori Login");

        //TODO: Borrar esta entrada, la deje para que vean como
        //mostrar contenido de una base de datos.
        $this->listarTodo();

        //TODO: _baseUrl esta fucked up habria que corregirlo.
        //echo $this->_baseUrl;
    }

    public function listarTodo()
    {
        $table = new Users();
        $this->view->users = $table->fetchAll();
    }

    public function loginstateAction()
    {
        $registry = Zend_Registry::getInstance();

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['loginTrack']))
            {
                $form = $this->getLoginForm();
                if ($form->isValid($_POST))
                {
                    // process login
                    $values = $form->getValues();

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
                    $result = $auth->authenticate($adapter);

                    // authentication succeeded
                    if ($result->isValid())
                    {
                        $auth->getStorage()->write($adapter->getResultRowObject(null, 'password'));
                        //TODO: Esto hay que modificarlo por la pagina principal.
                        $this->_helper->redirector->gotoUrl($this->_baseUrl);
                    }
                    else
                    {
                        // or not! Back to the login page!
                        $this->view->failedAuthentication = true;
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
        echo 'LALALALALALA';
        Zend_Auth::getInstance()->clearIdentity();
    }

    private function getLoginForm()
    {
        $form = new Zend_Form();
        $form->setAction($this->_baseUrl)->setMethod('post');

        // Create and configure username element:
        $username = $form->createElement('text', 'username', array('label' => 'Usuario'));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $form->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,20))
                 ->setRequired(true);

        // Add elements to form:
        $form->addElement($username)
             ->addElement($password)
             ->addElement('hidden', 'loginTrack', array('values' => 'logPost'))
             ->addElement('submit', 'login', array('label' => 'Ingresar'));
        return $form;
    }
}
