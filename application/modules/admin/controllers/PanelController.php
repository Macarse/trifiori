<?php
class admin_PanelController extends Zend_Controller_Action
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
        $this->view->var = 'Esto es prueba';
    }

    public function adduserstateAction()
    {
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddUserTrack']))
            {
                //Borro los mensaje de errores anteriores.
                unset($this->view->failedAddUser);
                unset($this->view->suceedAddUser);

                $this->_form = $this->getUserForm();
                if ($this->_form->isValid($_POST))
                {
                    // process user
                    $values = $this->_form->getValues();
                    if ($values['password'] == $values['passwordvrfy'])
                    {
                        //Falta agregar idioma.
                        $data = array(
                            'NOMBRE_USU'      => $values['name'],
                            'USUARIO_USU'     => $values['username'],
                            'PASSWORD_USU'    => hash('SHA1', $values['password']));

                        $db = Zend_Registry::getInstance()->database;

                        //Ver qué devuelve el insert para meterle un if si fue exitoso
                        $db->insert('USUARIOS', $data);
                        $this->view->suceedAddUser = 'Inserción exitosa';
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

        $this->view->getUserForm = $this->getUserForm();
    }

    private function getUserForm()
    {
        if (null !== $this->_form)
        {
            return $this->_form;
        }

        $this->_form = new Zend_Form();
        $this->_form->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_form->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 50))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure username element:
        $username = $this->_form->createElement('text', 'username', array('label' => 'Usuario'));
        $username->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 30))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Create and configure password element:
        $password = $this->_form->createElement('password', 'password', array('label' => 'Clave'));
        $password->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        $passwordvrfy = $this->_form->createElement('password', 'passwordvrfy', array('label' => 'Repetir Clave'));
        $passwordvrfy->addValidator('StringLength', false, array(1,100))
                 ->setRequired(true);

        // frutita++
        $lang = $this->_form->createElement('text', 'lang', array('label' => 'Idioma'));
        $lang->addValidator('StringLength', false, array(1,20))
                 ->setRequired(true);

        // Add elements to form:
        $this->_form->addElement($name)
             ->addElement($username)
             ->addElement($password)
             ->addElement($passwordvrfy)
             ->addElement($lang)
             ->addElement('hidden', 'AddUserTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));
        return $this->_form;
    }

}
