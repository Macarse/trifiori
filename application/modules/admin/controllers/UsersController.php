<?php
class admin_UsersController extends Trifiori_Admin_Controller_Action
{

    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;
    protected $_rmid;
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        parent::init();
    }

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('admin/users/listusers');
    }

    public function addusersAction()
    {
        $this->view->headTitle($this->language->_("Agregar Usuario"));

        //Borro los mensaje de errores anteriores.
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddUserTrack']))
            {
                if (($this->_addform = $this->getUserAddForm()) == NULL)
                {
                    $this->_helper->redirector->gotoUrl('admin/users/listusers');
                }

                if ($this->_addform->isValid($_POST))
                {
                    // process user
                    $values = $this->_addform->getValues();
                    if ($values['password'] == $values['passwordvrfy'])
                    {
                        try
                        {
                            $usuariosTable = new Users();
                            $usuariosTable->addUser(    $values['name'],
                                                        $values['username'],
                                                        hash('SHA1', $values['password']),
                                                        $values['lang'],
                                                        $values['css'],
                                                        $values['email']
                                                    );
                        }
                        catch (Zend_Exception $error)
                        {
                            $this->view->error = $this->language->_("Error en la Base de datos.");
                        }
                        $this->view->message = $this->language->_('Inserción exitosa');
                        $this->_addform = null;
                    }
                    else
                    {
                        $this->view->passNotEqual = $this->language->_('Las contraseñas no son iguales');
                    }
                }
            }
        }

        if (($this->_addform = $this->getUserAddForm()) == NULL)
        {
            $this->_helper->redirector->gotoUrl('admin/users/listusers');
        }
    }

    public function listusersAction()
    {
        $this->view->headTitle($this->language->_("Listar Usuarios"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getUserSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $table = new Users();

                if (isset($_GET["consulta"]))
                {
                    if (isset($_GET["sortby"]))
                    {
                        if (isset($_GET["sort"]))
                        {
                            $user = $table->searchUser($_GET["consulta"], $_GET["sortby"], $_GET["sort"]);
                            $mySortType = $_GET["sort"];
                        }
                        else
                        {
                            $user = $table->searchUser($_GET["consulta"], $_GET["sortby"], null);
                            $mySortType = null;
                        }
                        $mySortBy = $_GET["sortby"];
                    }
                    else
                    {
                        $user = $table->searchUser($_GET["consulta"], null, null);
                        $mySortType = null;
                        $mySortBy = null;
                    }
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                    Zend_Registry::set('sortby', $mySortBy);
                    Zend_Registry::set('sorttype', $mySortType);
                }
                else
                {
                    $user = $table->searchUser("", "", "");

                    Zend_Registry::set('sortby', "");
                    Zend_Registry::set('sorttype', "");
                    Zend_Registry::set('busqueda', "");
                }

                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($user, $table));

                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($this->_getParam('page'));
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }

                $paginator->setItemCountPerPage(15);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $this->language->_("Error en la Base de datos.");
            }
        }
        $this->view->userSearchForm = $this->getUserSearchForm();
    }

    public function removeusersAction()
    {
        $_rmid = $this->getRequest()->getParam('id');

        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $_rmid === null )
        {
            $this->_helper->redirector->gotoUrl('admin/users/listusers');
        }
        else
        {
            /* No se puede borrar el administrador. */
            if ( $_rmid == 1 )
            {
                $this->view->error = $this->language->_("No puede eliminar al administrador.");
            }
            else
            {
                try
                {
                $usersTable = new Users();
                $usersTable->removeUser( $_rmid );
                $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
                }
                catch (Exception $error)
                {
                    $this->view->error = $this->language->_("Error en la Base de datos.");
                }
            }
        }

        $this->_helper->redirector->gotoUrl('admin/users/listusers');
    }

    public function modusersAction()
    {
        $this->view->headTitle($this->language->_("Modificar Usuario"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->userModForm = $this->getUserModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('admin/users/listusers');
            }
        }
        else
        {
            $this->_helper->redirector->gotoUrl('admin/users/listusers');
        }
        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModUserTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $usersTable = new Users();
                        $usersTable->modifyUser(    $this->_id,
                                                    $values['name'],
                                                    $values['username'],
                                                    $values['password'],
                                                    $values['lang'],
                                                    $values['css'],
                                                    $values['email']
                                                );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $this->language->_("No puede eliminar al administrador.");
                    }

                    $this->_helper->redirector->gotoUrl('admin/users/listusers');
                }
            }
        }
    }

    private function getUserAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure username element:

        $username = $this->_addform->createElement('text', 'username', array('label' => '*' . $this->language->_('Usuario')));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_addform->createElement('password', 'password', array('label' => '*' . $this->language->_('Clave')));
        $password->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        $passwordvrfy = $this->_addform->createElement('password', 'passwordvrfy', array('label' => '*' . $this->language->_('Repetir Clave')));
        $passwordvrfy->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);


        try
        {
            $LangTable = new Lang();
            $langOptions =  $LangTable->getLangArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        $lang = $this->_addform->createElement('select', 'lang');
        $lang   ->setRequired(true)
                ->setOrder(1)
                ->setLabel('*' . $this->language->_('Idioma'))
                ->setMultiOptions($langOptions);

        try
        {
            $cssTable = new Css();
            $cssOptions =  $cssTable->getCssArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        $css = $this->_addform->createElement('select', 'css');
        $css    ->setRequired(true)
                ->setOrder(2)
                ->setLabel('*' . $this->language->_('Css'))
                ->setMultiOptions($cssOptions);

        $email = $this->_addform->createElement('text', 'email',
            array('label' => '*' . $this->language->_('E-mail')));
        $email   ->addValidator('stringLength', false, array(1, 100))
                ->addValidator('EmailAddress')
                ->setRequired(True);

        // Add elements to form:
        $this->_addform ->addElement($name)
                        ->addElement($username)
                        ->addElement($password)
                        ->addElement($passwordvrfy)
                        ->addElement($email)
                        ->addElement($lang)
                        ->addElement($css)
             ->addElement('hidden', 'AddUserTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));
        return $this->_addform;
    }

    private function getUserSearchForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform  ->setAction($this->_baseUrl)
                ->setName('form')
                ->setMethod('get');

        $user = $this->_searchform->createElement('text', 'consulta',
                array('label' => $this->language->_('Nombre de Usuario')));
        $user   ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
                $this->_searchform->addElement($user)
                ->addElement('hidden', 'SearchUserTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }

    private function getUserModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        try
        {
            $table = new Users();
            $user = $table->getUserByID( $id );
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        if ( $user === null )
        {
            $this->_helper->redirector->gotoUrl('admin/users/listusers');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($user->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 50))
             ->setRequired(true)
             ->addFilter('StringToLower');

        // Create and configure username element:
        $username = $this->_modform->createElement('text', 'username', array('label' => '*' . $this->language->_('Usuario')));
        $username->setValue($user->user() )
                 ->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_modform->createElement('password', 'password',
            array('label' => '*' . $this->language->_('Clave')));
        $password->addValidator('StringLength', false, array(1,100));

        $passwordvrfy = $this->_modform->createElement('password', 'passwordvrfy',
            array('label' => '*' . $this->language->_('Repetir Clave')));
        $passwordvrfy->addValidator('StringLength', false, array(1,100));


        try
        {
            $LangTable = new Lang();
            $langOptions =  $LangTable->getLangArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        $lang = $this->_modform->createElement('select', 'lang');
        $lang   ->setValue($user->langNum())
                ->setRequired(true)
                ->setOrder(1)
                ->setLabel('*' . $this->language->_('Idioma'))
                ->setMultiOptions($langOptions);

        try
        {
            $cssTable = new Css();
            $cssOptions =  $cssTable->getCssArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        $css = $this->_modform->createElement('select', 'css');
        $css    ->setValue($user->codCss() )
                ->setRequired(true)
                ->setOrder(2)
                ->setLabel('*' . $this->language->_('Css'))
                ->setMultiOptions($cssOptions);

        $email = $this->_modform->createElement('text', 'email',
            array('label' => '*' . $this->language->_('E-mail')));
        $email  ->setValue($user->email())
                ->addValidator('stringLength', false, array(1, 100))
                ->addValidator('EmailAddress')
                ->setRequired(True);

        // Add elements to form:
        $this   ->_modform->addElement($name)
                ->addElement($username)
                ->addElement($password)
                ->addElement($passwordvrfy)
                ->addElement($email)
                ->addElement($lang)
                ->addElement($css)
                ->addElement('hidden', 'ModUserTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

}
