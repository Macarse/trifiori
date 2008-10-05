<?php
class user_ModbanderasController extends Zend_Controller_Action
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
        $this->view->headTitle("Modificar Bandera");
        $this->view->baseUrl = $this->_baseUrl;
    }

    public function findbanderaAction()
    {
        /*TODO: Acá se crea el buscador o se usa un método de Users*/
        $this->view->buscador = "Esto es el buscador";
    }


    public function listbanderaAction()
    {
        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');
            if (($this->view->modForm = $this->getModBanderaForm($this->_id)) != null)
            {
                $this->_showForm = True;
                $this->view->showForm = True;
            }
        }

        /*Si no hay que mostrar el form mostrar la lista de users*/
        if ( !$this->_showForm )
        {
            $table = new Banderas();
            $this->view->Banderas = $table->fetchAll();
            $this->view->showForm = False;
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModBanderaTrack']))
            {
                if ($this->_form->isValid($_POST))
                {
                    // process user
                    $values = $this->_form->getValues();
                        $banderasTable = new Banderas();
                        $banderasTable->modifyBandera( $this->_id,
                                            $values['name']);

                        /*Se actualizó, volver a mostrar lista de users*/
                        $this->_showForm = False;
                        $this->_helper->redirector->gotoUrl('user/modbanderas');
                }
            }
        }
    }

    public function removebanderaAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            /*TODO: No sé si está bien hardcodearlo así. Preguntar.*/
            $this->_helper->redirector->gotoUrl('user/modbanderas');
        }
        else
        {
            $banderasTable = new Banderas();
            $banderasTable->removeBandera( $this->getRequest()->getParam('id') );
        }

        $this->_helper->redirector->gotoUrl('user/modbanderas');
    }



    private function getModBanderaForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_form)
        {
            return $this->_form;
        }

        /*Levanto el usuario para completar el form.*/
        $bandera = new Banderas();
        $row = $bandera->getBanderaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/modbanderas');
        }

        $this->_form = new Zend_Form();
        $this->_form->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_form->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(true)
             ->addFilter('StringToLower');

        // Add elements to form:
        $this->_form->addElement($name)
             ->addElement('hidden', 'ModBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_form;
    }
}
