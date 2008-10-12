<?php
class user_DestinacionesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
    }

    public function adddestinacionesAction()
    {
        $this->view->headTitle("Agregar Destinación");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddDestinacionTrack']))
            {
                $this->_addform = $this->getDestinacionAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $destinacionesTable = new Destinaciones();
                        $destinacionesTable->addDestinacion($values['name']);
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->destinacionAddForm = $this->getDestinacionAddForm();
    }

    public function listdestinacionesAction()
    {
        $this->view->headTitle("Listar Destinaciones");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Destinaciones();
            $this->view->Destinaciones = $table->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }

    public function removedestinacionesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
        }
        else
        {
            try
            {
            $destinacionesTable = new Destinaciones();
            $destinacionesTable->removeDestinacion( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
    }

    public function moddestinacionesAction()
    {
        $this->view->headTitle("Modificar Destinación");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->destinacionModForm = $this->getDestinacionModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModDestinacionTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $destinacionesTable = new Destinaciones();
                        $destinacionesTable->modifyDestinacion( $this->_id,
                                            $values['name']);
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
                }
            }
        }
    }

    private function getDestinacionModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $destinacionesTable = new Destinaciones();
        $row = $destinacionesTable->getDestinacionByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(true)
             ->addFilter('StringToLower');

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement('hidden', 'ModDestinacionTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getDestinacionAddForm()
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
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement('hidden', 'AddDestinacionTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_addform;
    }

}
?>
