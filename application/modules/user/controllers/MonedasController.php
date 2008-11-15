<?php
class user_MonedasController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/monedas/listmonedas');
    }

    public function addmonedasAction()
    {      
        $this->view->headTitle($this->language->_("Agregar Moneda"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

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
                        $this->view->message = $this->language->_("Inserción exitosa.");
                        $this->_addform = null;
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

        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);
        
        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getMonedaSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $monedasT = new Monedas();
                
                if (isset($_GET["consulta"]))
                {
                    $monedas = $monedasT->searchMoneda($_GET["consulta"]);
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                }
                else
                {
                    $monedas = $monedasT->select();
                    Zend_Registry::set('busqueda', "");
                }
                    
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($monedas, $monedasT));
                
                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }
                
                $paginator->setItemCountPerPage(15);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->monedaSearchForm = $this->getMonedaSearchForm();
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
            $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
            $this->_flashMessenger->addMessage($this->language->_($error));
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
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->_flashMessenger->addMessage($this->language->_($error));
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
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
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
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 3))
             ->setRequired(true);

        $longName = $this->_modform->createElement('text', 'longName', array('label' => $this->language->_('Descripción')));
        $longName->setValue($row->longName() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($longName)
             ->addElement('hidden', 'ModMonedaTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getMonedaAddForm()
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
                 ->addValidator('stringLength', false, array(1, 3))
                 ->setRequired(true);

        $longName = $this->_addform->createElement('text', 'longName', array('label' => $this->language->_('Descripción')));
        $longName->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($longName)
             ->addElement('hidden', 'AddMonedaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Agregar'));

        return $this->_addform;
    }
    
    private function getMonedaSearchForm()
    {      
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('get');

        $moneda = $this->_searchform->createElement('text', 'consulta', array('label' => $this->language->_('Nombre')));
        $moneda       ->addValidator($alnumWithWS)
                     ->addValidator('stringLength', false, array(1, 150));

        // Add elements to form:
        $this->_searchform->addElement($moneda)
             ->addElement('hidden', 'SearchMonedaTrack', array('values' => 'logPost'))
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

		   $model = new Monedas();
		   $data = $model->fetchAll("NAME_MON LIKE '" .  $this->_name . "%'");
		   
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
