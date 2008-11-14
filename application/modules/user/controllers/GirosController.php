<?php
class user_GirosController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        parent::init();
    }
    
    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/giros/listgiros');
    }

    public function addgirosAction()
    {
        $this->view->headTitle($this->language->_("Agregar Giro"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddGiroTrack']))
            {
                $this->_addform = $this->getGiroAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $girosTable = new Giros();
                        $girosTable->addGiro($values['name']);
                        $this->view->message = $this->language->_("Inserción exitosa.");
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->giroAddForm = $this->getGiroAddForm();
    }

    public function listgirosAction()
    {
        $this->view->headTitle($this->language->_("Listar Giros"));

        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);
        
        $this->view->message = $this->_flashMessenger->getMessages();

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['SearchGiroTrack']))
            {
                $this->_searchform = $this->getGiroSearchForm();
                if ($this->_searchform->isValid($_POST))
                {
                    $values = $this->_searchform->getValues();
                    
                    try
                    {
                        $girosT = new Giros();
                        $giros = $girosT->searchGiro($values["giro"]);
                        $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($giros, $girosT));
                        $paginator->setCurrentPageNumber($this->_getParam('page'));
                        $paginator->setItemCountPerPage(15);
                        $this->view->paginator = $paginator;
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
                $this->view->giroSearchForm = $this->getGiroSearchForm();
            }
        }
        else
        {
            $this->view->giroSearchForm = $this->getGiroSearchForm();
            try
            {
                $table = new Giros();
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($table->select(), $table));
                $paginator->setCurrentPageNumber($this->_getParam('page'));
                $paginator->setItemCountPerPage(15);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
    }

    public function removegirosAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/giros/listgiros');
        }
        else
        {
            try
            {
            $girosTable = new Giros();
            $girosTable->removeGiro( $this->getRequest()->getParam('id') );
            $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
            $this->_flashMessenger->addMessage($this->language->_($error));
            }
        }

        $this->_helper->redirector->gotoUrl('user/giros/listgiros');
    }

    public function modgirosAction()
    {
        $this->view->headTitle($this->language->_("Modificar Giro"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->giroModForm = $this->getGiroModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/giros/listgiros');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModGiroTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $girosTable = new Giros();
                        $girosTable->modifyGiro( $this->_id,
                                            $values['name']);
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->_flashMessenger->addMessage($this->language->_($error));
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/giros/listgiros');
                }
            }
        }
    }

    private function getGiroModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $girosTable = new Giros();
        $row = $girosTable->getGiroByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/giros/listgiros');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 100))
             ->setRequired(true);

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement('hidden', 'ModGiroTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getGiroAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 100))
                 ->setRequired(true);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement('hidden', 'AddGiroTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));

        return $this->_addform;
    }
	
    private function getGiroSearchForm()
    {      
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $giro = $this->_searchform->createElement('text', 'giro', array('label' => $this->language->_('Nombre')));
        $giro       ->addValidator($alnumWithWS)
                     ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
        $this->_searchform->addElement($giro)
             ->addElement('hidden', 'SearchGiroTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }
    
	public function getdataAction() {
       $arr = array();
	   $aux = array();
	   
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->layout()->disableLayout();
	   
	   if ( $this->getRequest()->getParam('query') != null )
        {
            $this->_name = $this->getRequest()->getParam('query');

		   $model = new Giros();
		   $data = $model->fetchAll("SECCION_GIR LIKE '" .  $this->_name . "%'");
		   
           foreach ($data as $row)
		   {
               array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
	       }
	
		   $arr = array("Resultset" => array("Result" => $aux));
	
		   try {
			   $responseDataJsonEncoded = Zend_Json::encode($arr);
			   $this->getResponse()->setHeader('Content-Type', 'application/json')
								   ->setBody($responseDataJsonEncoded);
	
		   } catch(Zend_Json_Exception $e) {
			   // handle and generate HTTP error code response, see below
			   $this->getResponse()->setHeader('Content-Type', 'application/json')
								   ->setBody('[{Error}]');
		   }
		 }
   }
}
?>
