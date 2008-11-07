<?php
class user_DestinacionesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/destinaciones/listdestinaciones');
    }

    public function adddestinacionesAction()
    {
        $this->view->headTitle($this->language->_("Agregar Destinación"));

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
        $this->view->headTitle($this->language->_("Listar Destinaciones"));

        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['SearchDestinacionTrack']))
            {
                $this->_searchform = $this->getDestinacionSearchForm();
                if ($this->_searchform->isValid($_POST))
                {
                    $values = $this->_searchform->getValues();
                    
                    try
                    {
                        $destinacionesT = new Destinaciones();
                        $destinaciones = $destinacionesT->searchDestinacion($values["destinacion"]);
                        $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($destinaciones, $destinacionesT));
                        $paginator->setCurrentPageNumber($this->_getParam('page'));
                        $paginator->setItemCountPerPage(15);
                        $this->view->paginator = $paginator;
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
                $this->view->destinacionSearchForm = $this->getDestinacionSearchForm();
            }
        }
        else
        {
            $this->view->destinacionSearchForm = $this->getDestinacionSearchForm();
            try
            {
                $table = new Destinaciones();
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
        $this->view->headTitle($this->language->_("Modificar Destinación"));

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
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(true);

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement('hidden', 'ModDestinacionTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Ingresar')));

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
        $this->_addform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement('hidden', 'AddDestinacionTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Ingresar')));

        return $this->_addform;
    }
	
	private function getDestinacionSearchForm()
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

        $destinacion = $this->_searchform->createElement('text', 'destinacion', array('label' => $this->language->_('Nombre')));
        $destinacion       ->addValidator($alnumWithWS)
                     ->addValidator('stringLength', false, array(1, 200));

        // Add elements to form:
        $this->_searchform->addElement($destinacion)
             ->addElement('hidden', 'SearchDestinacionTrack', array('values' => 'logPost'))
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

		   $model = new Destinaciones();
		   $data = $model->fetchAll("DESCRIPCION_DES LIKE '" .  $this->_name . "%'");
		   
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
