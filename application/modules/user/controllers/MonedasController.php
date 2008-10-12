<?php
class user_MonedasController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
    }

    public function addmonedasAction()
    {
        $this->view->headTitle("Agregar Moneda");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddMonedaTrack']))
            {
                $this->_addform = $this->getMonedaAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $monedasTable = new Monedas();
                        $monedasTable->addMoneda(   $values['name'],
                                                    $values['longName']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->monedaAddForm = $this->getMonedaAddForm();
    }

    public function listmonedasAction()
    {
        $this->view->headTitle("Listar Monedas");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Monedas();
            $this->view->Monedas = $table->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }

    public function removemonedasAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
        }
        else
        {
            try
            {
            $monedasTable = new Monedas();
            $monedasTable->removeMoneda( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
    }

    public function modmonedasAction()
    {
        $this->view->headTitle("Modificar Moneda");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->monedaModForm = $this->getMonedaModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModMonedaTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $monedasTable = new Monedas();
                        $monedasTable->modifyMoneda( $this->_id,
                                                     $values['name'],
                                                     $values['longName']
                                                     );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
                }
            }
        }
    }

    private function getMonedaModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $monedasTable = new Monedas();
        $row = $monedasTable->getMonedaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 3))
             ->setRequired(true)
             ->addFilter('StringToLower');

        $longName = $this->_modform->createElement('text', 'longName', array('label' => 'Descripción'));
        $longName->setValue($row->longName() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False)
             ->addFilter('StringToLower');

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($longName)
             ->addElement('hidden', 'ModMonedaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getMonedaAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator('alnum')
                 ->addValidator('stringLength', false, array(1, 3))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        $longName = $this->_addform->createElement('text', 'longName', array('label' => 'Descripción'));
        $longName->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False)
             ->addFilter('StringToLower');

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($longName)
             ->addElement('hidden', 'AddMonedaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_addform;
    }

}
?>
