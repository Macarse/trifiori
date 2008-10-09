<?php
class user_BanderasController extends Zend_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_acl;
    protected $_username;
    
    public function init()
    {
        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
        $_acl = Zend_Registry::getInstance()->accesslist;
        $_username = Zend_Registry::getInstance()->name;

        if (! $_acl->isAllowed($_username, 'user'))
        {
            $this->_helper->redirector->gotoUrl('default/index');
        }
    }

    public function indexAction()
    {
    }

    public function addbanderasAction()
    {
        $this->view->headTitle("Agregar Bandera");

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddBanderaTrack']))
            {
                $this->_addform = $this->getBanderaAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $banderasTable = new Banderas();
                        $banderasTable->addBandera($values['name']);
                    }
                    catch (Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->banderaAddForm = $this->getBanderaAddForm();
    }

    private function getBanderaAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement('hidden', 'AddBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_addform;
    }

}
?>
