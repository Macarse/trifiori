<?php
class IndexController extends Trifiori_Default_Controller_Action
{
    protected $_form;
    protected $_mailform;
    protected $_passform;
    protected $_flashMessenger = null;

    public function init()
    {
        parent::init();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        $this->view->headTitle("Trifiori Login");

        $this->view->message = $this->_flashMessenger->getMessages();

        if (Zend_Auth::getInstance()->getIdentity() !== null)
        {

            $user = Zend_Auth::getInstance()->getIdentity()->USUARIO_USU;

            if ($this->$user == "admin")
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

    public function resetpassAction()
    {
        $this->view->headTitle($this->language->_("Resetear Contraseña"));

        if (isset($_GET["hash"]))
        {
            $usuariosTable = new Users();
            $row = $usuariosTable->getUserByResetHash($_GET["hash"]);

            if (count($row))
            {
                if ($this->getRequest()->isPost())
                {
                    if (isset($_POST['resetPassTrack']))
                    {
                        //Borro los mensaje de errores anteriores.
                        unset($this->view->error);
                        unset($this->view->suceed);

                        $this->_passform = $this->getPassForm();
                        if ($this->_passform->isValid($_POST))
                        {
                            $values = $this->_passform->getValues();
                            if ($values['password'] == $values['passwordvrfy'])
                            {
                                try
                                {
                                    $usuariosTable->changePass( $row->id(),
                                                                hash('SHA1', $values['password']),
                                                                NULL
                                                                );

                                }
                                catch (Zend_Exception $error)
                                {
                                    $this->view->error = $error;
                                }

                                $this->view->succeed = $this->language->_('Operación exitosa');
                                $this->_passform = null;
                            }
                            else
                            {
                                $this->view->error = $this->language->_('Las contraseñas no son iguales');
                            }
                        }
                        else
                        {
                            $this->view->error = $this->language->_('Ocurrió un error');
                        }
                    }
                }

                $this->view->getPassForm = $this->getPassForm();
            }
            else
            {
                $this->_helper->redirector->gotoUrl('/');
            }

        }
        else
        {
            $this->_helper->redirector->gotoUrl('/');
        }
    }

    public function forgotpassAction()
    {
        $this->view->headTitle($this->language->_("Recuperar Contraseña"));

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['forgotPassTrack']))
            {
                //Borro los mensaje de errores anteriores.
                unset($this->view->error);
                unset($this->view->succeed);

                $this->_mailform = $this->getMailForm();
                if ($this->_mailform->isValid($_POST))
                {
                    // process user
                    $values = $this->_mailform->getValues();

                    try
                    {
                        $usuariosTable = new Users();
                        $hash = $usuariosTable->newPass($values['email']);
                        $this->view->succeed = $this->language->_('Operación exitosa');
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $this->language->_("Error en la Base de datos.");
                    }

                    try
                    {
                        //Envio el mail.
                        if ($hash)
                        {
                            $config = Zend_Registry::getInstance()->configuration;

                            $body  = $this->language->_('Se ha pedido resetear la contrase&ntilde;a.') . '<br/>';
                            $body .= $this->language->_('Para cambiarla haga click: ');
                            $body .= '<a href="' . $config->site->url .
                                     '/index/resetpass?hash=' . $hash . '">' .
                                     $this->language->_("aqu&iacute;") . '</a>';

                            $config = Zend_Registry::getInstance()->configuration;

                            $mail = new Zend_Mail();
                            $mail->setBodyHtml($body);
                            $mail->setFrom($config->gmail->email, 'Trifiori Web');
                            $mail->addTo($values['email'], $this->language->_("Usuario"));
                            $mail->setSubject('Trifiori Web');
                            $mail->send(Zend_Registry::getInstance()->mailTransport);

                        }
                        else
                        {
                            unset($this->view->succeed);
                            $this->view->error = $values['email'] . ' ' .
                                        $this->language->_('no existe en la base de datos');
                        }

                    }
                    catch (Zend_Exception $error)
                    {
                        /*Como no pudo mandar mail, entonces no suceedeo*/
                        unset($this->view->succeed);
                        $this->view->error = $this->language->_("Error al intentar enviar e-mail.");
                    }


                    $this->_mailform = null;
                }
                else
                {
                    $this->view->error = $this->language->_('Ocurrió un error');
                }
            }
        }

        $this->view->getMailForm = $this->getMailForm();


    }

    private function getPassForm()
    {
        if (null !== $this->_passform)
        {
            return $this->_passform;
        }

        $this->_passform = new Zend_Form();
        $this->_passform->setAction('')->setMethod('post');

        $password = $this->_passform->createElement('password', 'password',
            array('label' => $this->language->_('Clave')));
        $password->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        $passwordvrfy = $this->_passform->createElement('password', 'passwordvrfy',
            array('label' => $this->language->_('Repetir Clave')));
        $passwordvrfy->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        // Add elements to form:
        $this->_passform    ->addElement($password)
                            ->addElement($passwordvrfy)
                            ->addElement('hidden', 'resetPassTrack', array('values' => 'logPost'))
                            ->addElement('submit', 'login', array('label' => $this->language->_('Aceptar')));

        return $this->_passform;
    }


    private function getMailForm()
    {
        if (null !== $this->_mailform)
        {
            return $this->_mailform;
        }

        $this->_mailform = new Zend_Form();
        $this->_mailform->setAction($this->_baseUrl)->setMethod('post');

        $email = $this->_mailform->createElement('text', 'email',
            array('label' => $this->language->_('E-mail')));
        $email  ->addValidator('stringLength', false, array(1, 100))
                ->addValidator('EmailAddress')
                ->setRequired(True);

        // Add elements to form:
        $this->_mailform->addElement($email)
             ->addElement('hidden', 'forgotPassTrack', array('values' => 'logPost'))
             ->addElement('submit', 'login', array('label' => $this->language->_('Enviar')));

        return $this->_mailform;
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
        $username = $this->_form->createElement('text', 'username',
            array('label' => $this->language->_('Usuario')));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_form->createElement('password', 'password',
            array('label' => $this->language->_('Clave')));
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
