<?php
class admin_ModuserController extends Zend_Controller_Action
{

    protected $_form;
    protected $_showForm = False;
    /*TODO: Villero*/
    protected $_id;

    public function init()
    {
        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }

    public function indexAction()
    {
        $this->view->headTitle("Modificar Usuario");
        $this->view->baseUrl = $this->_baseUrl;
    }

    public function finduserAction()
    {
        /*TODO: Acá se crea el buscador o se usa un método de Users*/
        $this->view->buscador = "Esto es el buscador";
    }

    public function modifyuserAction()
    {
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');
            if (($this->view->modForm = $this->getModUserForm($this->_id)) != null)
            {
                $this->_showForm = True;
                $this->view->showForm = True;
            }
        }

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModUserTrack']))
            {
                unset($this->view->failedAddUser);

                if ($this->_form->isValid($_POST))
                {
                    // process user
                    $values = $this->_form->getValues();
                    if ($values['password'] == $values['passwordvrfy'])
                    {
                        //Falta agregar idioma.
                        $userTable = new Users();
                        $userTable->modifyUser( $this->_id,
                                            $values['name'],
                                            $values['username'],
                                            $values['password'],
                                            $values['lang'] );

                        /*Se actualizó, volver a mostrar lista de users*/
                        $this->_showForm = False;
                        $this->_helper->redirector->gotoUrl('admin/moduser');
                    }
                    else
                    {
                        $this->view->failedAddUser = 'Las contraseñas no son iguales';
                    }
                }
                else
                {
                    $this->view->failedAddUser = 'Algún campo no fue insertado correctamente';
                }
            }
        }
    }

    public function listuserAction()
    {
        /*TODO de esta función:
        * 1) No pude lograr que se muestre o la lista o el form.
             Ahora va a otra pantalla y queda feo.

          2) Hay que ver qué hacemos con lo de idioma, si es combo, etc
             El idioma hace cualquiera.

          3) Hay muchas cosas feas para mi gusto. Comenten.

        */
        /*Si no hay que mostrar el form mostrar la lista de users*/
        if ( !$this->_showForm )
        {
            $table = new Users();
            $this->view->users = $table->fetchAll();
            $this->view->showForm = False;
        }
    }

    public function removeuserAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            /*TODO: No sé si está bien hardcodearlo así. Preguntar.*/
            $this->_helper->redirector->gotoUrl('admin/moduser');
        }
        else
        {
            $UserTable = new Users();
            $UserTable->removeUser( $this->getRequest()->getParam('id') );
        }

        $this->_helper->redirector->gotoUrl('admin/moduser');
    }



    private function getModUserForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_form)
        {
            return $this->_form;
        }

        /*Levanto el usuario para completar el form.*/
        $table = new Users();
        $user = $table->getUserByID( $id );

        if ( $user === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('admin/moduser');
        }

        $this->_form = new Zend_Form();
        $this->_form->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_form->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($user->name() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 50))
             ->setRequired(true)
             ->addFilter('StringToLower');

        // Create and configure username element:
        $username = $this->_form->createElement('text', 'username', array('label' => 'Usuario'));
        $username->setValue($user->user() )
                 ->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_form->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,100));

        $passwordvrfy = $this->_form->createElement('password', 'passwordvrfy', array('label' => 'Repetir Clave'));
        $passwordvrfy->addValidator('StringLength', false, array(1,100));

        // frutita++
        $lang = $this->_form->createElement('text', 'lang', array('label' => 'Idioma'));
        $lang->setValue($user->language() )
             ->addValidator('StringLength', false, array(1,20))
             ->setRequired(true);

        // Add elements to form:
        $this->_form->addElement($name)
             ->addElement($username)
             ->addElement($password)
             ->addElement($passwordvrfy)
             ->addElement($lang)
             ->addElement('hidden', 'ModUserTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_form;
    }
}
