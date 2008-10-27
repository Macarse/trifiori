<?php
class admin_UsersController extends Trifiori_Admin_Controller_Action
{

    protected $_addform;
    protected $_modform;
    protected $_id;
    protected $_rmid;


    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('admin/users/listusers');
    }

    public function addusersAction()
    {
        $this->view->headTitle("Agregar Usuario");

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddUserTrack']))
            {
                //Borro los mensaje de errores anteriores.
                unset($this->view->error);
                unset($this->view->suceedAddUser);

                $this->_addform = $this->getUserAddForm();
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
                                                        $values['lang']
                                                    );
                        }
                        catch (Zend_Exception $error)
                        {
                            $this->view->error = $error;
                        }

                        /*TODO: Si ocurre un error se muestra que insertó bien*/
                        $this->view->suceedAddUser = 'Inserción exitosa';
                    }
                    else
                    {
                        $this->view->error = 'Las contraseñas no son iguales';
                    }
                }
                else
                {
                    $this->view->error = 'Ocurrió un error';
                }
            }
        }

        $this->view->getUserAddForm = $this->getUserAddForm();
    }

    public function listusersAction()
    {
        $this->view->headTitle("Listar Usuarios");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Users();
            $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($table->select(), $table));
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setItemCountPerPage(10);
            $this->view->paginator = $paginator;
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
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
                $this->view->error = "No puede eliminar al administrador.";
            }
            else
            {
                try
                {
                $usersTable = new Users();
                $usersTable->removeUser( $_rmid );
                }
                catch (Exception $error)
                {
                $this->view->error = $error;
                }
            }
        }

        $this->_helper->redirector->gotoUrl('admin/users/listusers');
    }

    public function modusersAction()
    {
        $this->view->headTitle("Modificar Usuario");

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
                                                    $values['lang']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
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

        $name = $this->_addform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure username element:
        
        $username = $this->_addform->createElement('text', 'username', array('label' => 'Usuario'));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_addform->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        $passwordvrfy = $this->_addform->createElement('password', 'passwordvrfy', array('label' => 'Repetir Clave'));
        $passwordvrfy->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);


        /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $LangTable = new Lang();
        $langOptions =  $LangTable->getLangArray();

        $lang = $this->_addform->createElement('select', 'lang');
        $lang   ->setRequired(true)
                ->setOrder(1)
                ->setLabel('Idioma')
                ->setMultiOptions($langOptions);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($username)
             ->addElement($password)
             ->addElement($passwordvrfy)
             ->addElement($lang)
             ->addElement('hidden', 'AddUserTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));
        return $this->_addform;
    }

    private function getUserModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $table = new Users();
        $user = $table->getUserByID( $id );

        if ( $user === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('admin/users/listusers');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($user->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 50))
             ->setRequired(true)
             ->addFilter('StringToLower');

        // Create and configure username element:
        $username = $this->_modform->createElement('text', 'username', array('label' => 'Usuario'));
        $username->setValue($user->user() )
                 ->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_modform->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,100));

        $passwordvrfy = $this->_modform->createElement('password', 'passwordvrfy', array('label' => 'Repetir Clave'));
        $passwordvrfy->addValidator('StringLength', false, array(1,100));


       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $LangTable = new Lang();
        $langOptions =  $LangTable->getLangArray();

        $lang = $this->_modform->createElement('select', 'lang');
        $lang   ->setValue($user->langNum())
                ->setRequired(true)
                ->setOrder(1)
                ->setLabel('Idioma')
                ->setMultiOptions($langOptions);


        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($username)
             ->addElement($password)
             ->addElement($passwordvrfy)
             ->addElement($lang)
             ->addElement('hidden', 'ModUserTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

}
