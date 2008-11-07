<?php
class user_BanderasController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
    }

    public function addbanderasAction()
    {
        $this->view->headTitle($this->language->_("Agregar Bandera"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

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
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->banderaAddForm = $this->getBanderaAddForm();
    }

    public function listbanderasAction()
    {
        $this->view->headTitle($this->language->_("Listar Banderas"));

        $this->view->paginator = null;
        
        /*Errors from the past are deleted*/
        unset($this->view->error);
        
        if ($this->getRequest()->isPost())
        {
        
            if (isset($_POST['SearchBanderaTrack']))
            {
                $this->_searchform = $this->getBanderaSearchForm();
                if ($this->_searchform->isValid($_POST))
                {
                    $values = $this->_searchform->getValues();
                    
                    try
                    {
                        $banderasT = new Banderas();
                        $banderas = $banderasT->searchBandera($values["bandera"]);
                        $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($banderas, $banderasT));
                        $paginator->setCurrentPageNumber($this->_getParam('page'));
                        $paginator->setItemCountPerPage(15);
                        $this->view->paginator = $paginator;
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
                $this->view->banderaSearchForm = $this->getBanderaSearchForm();
            }
        }
        else
        {
            $this->view->banderaSearchForm = $this->getBanderaSearchForm();
            try
            {
                $table = new Banderas();
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($table->select(), $table));
                $paginator->setCurrentPageNumber($this->_getParam('page'));
                $paginator->setItemCountPerPage(20);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }    
    }

    public function removebanderasAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
        }
        else
        {
            try
            {
            $banderasTable = new Banderas();
            $banderasTable->removeBandera( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
    }

    public function modbanderasAction()
    {
        $this->view->headTitle($this->language->_("Modificar Bandera"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->banderaModForm = $this->getBanderaModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModBanderaTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $banderasTable = new Banderas();
                        $banderasTable->modifyBandera( $this->_id,
                                            $values['name']);
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
                }
            }
        }
    }

    private function getBanderaModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $bandera = new Banderas();
        $row = $bandera->getBanderaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
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
             ->addElement('hidden', 'ModBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Ingresar')));

        return $this->_modform;
    }

    private function getBanderaAddForm()
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
             ->addElement('hidden', 'AddBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Ingresar')));

        return $this->_addform;
    }
    
    private function getBanderaSearchForm()
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

        $banderas = $this->_searchform->createElement('text', 'bandera', array('label' => $this->language->_('Nombre')));
        $banderas    ->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 150));

        // Add elements to form:
        $this->_searchform->addElement($banderas)
             ->addElement('hidden', 'SearchBanderaTrack', array('values' => 'logPost'))
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

		   $model = new Banderas();
		   $banderas = $model->fetchAll("NOMBRE_BAN LIKE '" .  $this->_name . "%'");
		   
           foreach ($banderas as $row)
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
