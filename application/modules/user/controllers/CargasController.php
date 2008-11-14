<?php
class user_CargasController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
    }

    public function addcargasAction()
    {
        $this->view->headTitle($this->language->_("Agregar Carga"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddCargaTrack']))
            {
                $this->_addform = $this->getCargaAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $cargasTable = new Cargas();
                        $cargasTable->addCarga( $values['cantBultos'],
                                                $values['tipoEnvase'],
                                                $values['peso'],
                                                $values['unidad'],
                                                $values['nroPaquete'],
                                                $values['marcaYnum'],
                                                $values['mercIMCO']
                                              );
                        $this->view->message = $this->language->_("Inserción exitosa.");
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->cargaAddForm = $this->getCargaAddForm();
    }

    public function listcargasAction()
    {
        $this->view->headTitle($this->language->_("Listar Cargas"));

        $this->view->paginator = null;
        
        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();
        
        $this->_searchform = $this->getCargaSearchForm();
        if ($this->_searchform->isValid($_GET))
        {          
            try
            {
                $cargasT = new Cargas();
                
                if (isset($_GET["consulta"]))
                {
                    $cargas = $cargasT->searchCarga($_GET["consulta"]);
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                }
                else
                {
                    $cargas = $cargasT->select();   
                    Zend_Registry::set('busqueda', "");
                }
                
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($cargas, $cargasT));
                
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
        $this->view->cargaSearchForm = $this->getCargaSearchForm();
    }

    public function removecargasAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
        }
        else
        {
            try
            {
            $cargasTable = new Cargas();
            $cargasTable->removeCarga( $this->getRequest()->getParam('id') );
            $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
            $this->_flashMessenger->addMessage($this->language->_($error));
            }
        }

        $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
    }

    public function modcargasAction()
    {
        $this->view->headTitle($this->language->_("Modificar Carga"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->cargaModForm = $this->getCargaModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModCargaTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $cargasTable = new Cargas();
                        $cargasTable->modifyCarga( $this->_id,
                                                    $values['cantBultos'],
                                                    $values['tipoEnvase'],
                                                    $values['peso'],
                                                    $values['unidad'],
                                                    $values['nroPaquete'],
                                                    $values['marcaYnum'],
                                                    $values['mercIMCO']
                                                );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->_flashMessenger->addMessage($this->language->_($error));
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
                }
            }
        }
    }

    private function getCargaModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $cargasTable = new Cargas();
        $row = $cargasTable->getCargaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');


        $cantBultos = $this->_modform->createElement('text', 'cantBultos',
                                            array('label' => '*' . $this->language->_('Cantidad de Bultos')));
        $cantBultos ->setValue($row->cantBultos() )
                    ->addValidator('digits')
                    ->addValidator('stringLength', false, array(1, 11))
                    ->setRequired(True);


        $tipoEnvase = $this->_modform->createElement('select', 'tipoEnvase');
        $tipoEnvase ->setValue($row->tipoEnvase() )
                    ->setRequired(True)
                    ->setOrder(1)
                    ->setLabel('*' . $this->language->_('Tipo Envase'))
                    ->setMultiOptions(array('Envase Flexible' => $this->language->_('Envase Flexible'),
                                            'Caja' => $this->language->_('Caja'),
                                            'Frasco' => $this->language->_('Frasco'),
                                            'Tarro' => $this->language->_('Tarro'),
                                            'Lata de Aluminio' => $this->language->_('Lata de Aluminio'),
                                        ));

        $peso = $this->_modform->createElement('text', 'peso', array('label' => '*' . $this->language->_('Peso')));
        $peso   ->setValue($row->peso() )
                ->addValidator('float')
                ->addValidator('stringLength', false, array(1, 10))
                ->setRequired(true);

        $unidad = $this->_modform->createElement('select', 'unidad');
        $unidad ->setValue($row->unidad() )
                ->setRequired(True)
                ->setOrder(2)
                ->setLabel('*' . $this->language->_('Unidad'))
                ->setMultiOptions(array('Toneladas' => $this->language->_('Toneladas'),
                                        'Kilogramos' => $this->language->_('Kilogramos'),
                                        'Gramos' => $this->language->_('Gramos')
                                        ));

        $nroPaquete = $this->_modform->createElement('text', 'nroPaquete',
                                            array('label' => $this->language->_('Número de Paquete')));
        $nroPaquete ->setValue($row->nroPaquete() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 25))
                    ->setRequired(False);


        $marcaYnum = $this->_modform->createElement('text', 'marcaYnum',
                                            array('label' => $this->language->_('Marca y número')));
        $marcaYnum  ->setValue($row->marcaYnum() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 100))
                    ->setRequired(False);


        $mercIMCO = $this->_modform->createElement('text', 'mercIMCO',
                                            array('label' => $this->language->_('Merc. IMCO')));
        $mercIMCO   ->setValue($row->mercIMCO() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 100))
                    ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($cantBultos)
             ->addElement($tipoEnvase)
             ->addElement($peso)
             ->addElement($unidad)
             ->addElement($nroPaquete)
             ->addElement($marcaYnum)
             ->addElement($mercIMCO)
             ->addElement('hidden', 'ModCargaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getCargaAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $cantBultos = $this->_addform->createElement('text', 'cantBultos',
                                            array('label' => '*' . $this->language->_('Cantidad de Bultos')));
        $cantBultos->addValidator('digits')
                   ->addValidator('stringLength', false, array(1, 11))
                   ->setRequired(True);


        $tipoEnvase = $this->_addform->createElement('select', 'tipoEnvase');
        $tipoEnvase    ->setRequired(True)
                       ->setOrder(1)
                       ->setLabel('*' . $this->language->_('Tipo Envase'))
                       ->setMultiOptions(array('Envase Flexible' => $this->language->_('Envase Flexible'),
                                                'Caja' => $this->language->_('Caja'),
                                                'Frasco' => $this->language->_('Frasco'),
                                                'Tarro' => $this->language->_('Tarro'),
                                                'Lata de Aluminio' => $this->language->_('Lata de Aluminio'),
                                            ));

        $peso = $this->_addform->createElement('text', 'peso', array('label' => '*' . $this->language->_('Peso')));
        $peso   ->addValidator('float')
                ->addValidator('stringLength', false, array(1, 10))
                ->setRequired(true);

        $unidad = $this->_addform->createElement('select', 'unidad');
        $unidad ->setRequired(True)
                ->setOrder(2)
                ->setLabel('*' . $this->language->_('Unidad'))
                ->setMultiOptions(array('Toneladas' => $this->language->_('Toneladas'),
                                        'Kilogramos' => $this->language->_('Kilogramos'),
                                        'Gramos' => $this->language->_('Gramos')
                                        ));

        $nroPaquete = $this->_addform->createElement('text', 'nroPaquete',
                                            array('label' => $this->language->_('Número de Paquete')));
        $nroPaquete->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 25))
                   ->setRequired(False);


        $marcaYnum = $this->_addform->createElement('text', 'marcaYnum',
                                            array('label' => $this->language->_('Marca y número')));
        $marcaYnum ->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 100))
                   ->setRequired(False);


        $mercIMCO = $this->_addform->createElement('text', 'mercIMCO',
                                            array('label' => $this->language->_('Merc. IMCO')));
        $mercIMCO ->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 100))
                   ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($cantBultos)
             ->addElement($tipoEnvase)
             ->addElement($peso)
             ->addElement($unidad)
             ->addElement($nroPaquete)
             ->addElement($marcaYnum)
             ->addElement($mercIMCO)
             ->addElement('hidden', 'AddCargaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));

        return $this->_addform;
    }

    private function getCargaSearchForm()
    {      
        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('get');

        $carga = $this->_searchform->createElement('text', 'consulta', array('label' => $this->language->_('Nombre')));
        $carga       ->addValidator('alnum')
                     ->addValidator('stringLength', false, array(1, 25));

        // Add elements to form:
        $this->_searchform->addElement($carga)
             ->addElement('hidden', 'SearchCargaTrack', array('values' => 'logPost'))
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

		   $model = new Cargas();
		   $data = $model->fetchAll("NROPAQUETE_CAR LIKE '" .  $this->_name . "%'");
		   
           foreach ($data as $row)
		   {
               array_push($aux, array("id" => $row->id(), "data" => $row->nroPaquete()));	
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
