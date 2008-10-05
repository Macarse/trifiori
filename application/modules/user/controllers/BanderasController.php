<?php
class user_BanderasController extends Zend_Controller_Action
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
        $this->view->headTitle("Agregar Bandera");
    }

    public function addbanderastateAction()
    {
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddBanderaTrack']))
            {
                $this->_form = $this->getBanderaForm();
                if ($this->_form->isValid($_POST))
                {
                    $values = $this->_form->getValues();

                    /*TODO: Try except.*/
                    $banderasTable = new Banderas();
                    $banderasTable->addBandera($values['name']);
                }
            }
        }

        $this->view->banderaForm = $this->getBanderaForm();
    }

    private function getBanderaForm()
    {
        if (null !== $this->_form)
        {
            return $this->_form;
        }

        $this->_form = new Zend_Form();
        $this->_form->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_form->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Add elements to form:
        $this->_form->addElement($name)
             ->addElement('hidden', 'AddBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_form;
    }

}
?>