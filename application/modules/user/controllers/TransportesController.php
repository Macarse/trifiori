<?php
class user_TransportesController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
    }

    public function addtransportesAction()
    {
        $this->view->headTitle($this->language->_("Agregar Transporte"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddTransporteTrack']))
            {
                $this->_addform = $this->getTransporteAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $transportesTable = new Transportes();
                        $transportesTable->addTransporte(   $values['nameBandera'],
                                                            $values['codMedio'],
                                                            $values['name'],
                                                            $values['observaciones']
                                                        );
                        $this->view->message = $this->language->_("Inserción exitosa.");
                        $this->_addform = null;
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $this->language->_("Error en la Base de datos.");
                    }
                }
            }
        }

        if (($this->view->transporteAddForm = $this->getTransporteAddForm()) == NULL)
            $this->view->error = $this->language->_("Error en la Base de datos.");
    }

    public function listtransportesAction()
    {
        $this->view->headTitle($this->language->_("Listar Transportes"));

        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getTransporteSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $transportesT = new Transportes();

                if (isset($_GET["consulta"]))
                {
                    if (isset($_GET["sortby"]))
                    {
                        if (isset($_GET["sort"]))
                        {
                            $transportes = $transportesT->searchTransporte($_GET["consulta"], $_GET["sortby"], $_GET["sort"]);
                            $mySortType = $_GET["sort"];
                        }
                        else
                        {
                            $transportes = $transportesT->searchTransporte($_GET["consulta"], $_GET["sortby"], null);
                            $mySortType = null;
                        }
                        $mySortBy = $_GET["sortby"];
                    }
                    else
                    {
                        $transportes = $transportesT->searchTransporte($_GET["consulta"], null, null);
                        $mySortType = null;
                        $mySortBy = null;
                    }
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                    Zend_Registry::set('sortby', $mySortBy);
                    Zend_Registry::set('sorttype', $mySortType);
                }
                else
                {
                    $transportes = $transportesT->searchTransporte("", "", "");

                    Zend_Registry::set('sortby', "");
                    Zend_Registry::set('sorttype', "");
                    Zend_Registry::set('busqueda', "");
                }

                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($transportes, $transportesT));

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
                $this->view->error = $this->language->_("Error en la Base de datos.");
            }
        }
        $this->view->transporteSearchForm = $this->getTransporteSearchForm();
    }

    public function removetransportesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }
        else
        {
            try
            {
                $transportesTable = new Transportes();
                $transportesTable->removeTransporte( $this->getRequest()->getParam('id') );
                $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
                $this->_flashMessenger->addMessage(
                        $this->language->_("No se puedo eliminar. Error en la Base de datos.")
                                                );
            }
        }

        $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
    }

    public function modtransportesAction()
    {
        $this->view->headTitle("Modificar Transporte");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->transporteModForm = $this->getTransporteModForm($this->_id)) == null)
            {
               $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
            }
        }
        else
        {
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModTransporteTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $transportesTable = new Transportes();
                        $transportesTable->modifyTransporte(    $this->_id,
                                                                $values['nameBandera'],
                                                                $values['codMedio'],
                                                                $values['name'],
                                                                $values['observaciones']
                                                            );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->_flashMessenger->addMessage(
                            $this->language->_("No se puedo modificar. Error en la Base de datos.")
                                                        );
                    }

                    $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
                }
            }
        }
    }

    private function getTransporteAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform ->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');


        $codBandera = $this->_addform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
                    ->addValidator(new CV_Validate_Bandera());

        try
        {
            $mediosTable = new Medios();
            $mediosOptions =  $mediosTable->getMediosArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        $codMedio = $this->_addform->createElement('select', 'codMedio');
        $codMedio   ->setRequired(true)
                ->setLabel('*' . $this->language->_('Medio'))
                    ->setMultiOptions($mediosOptions);


        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 100))
                 ->addValidator(new CV_Validate_TransporteExiste())
                 ->setRequired(true);

        $observaciones = $this->_addform->createElement('text', 'observaciones',
                array('label' => $this->language->_('Observaciones'))
                                                        );
        $observaciones  ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 400))
                        ->setRequired(False);

        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );


        // Add elements to form:
        $this->_addform->addElement($name)
                       ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera))
                       ->addElement($codMedio)
                       ->addElement($observaciones)
             ->addElement('hidden', 'AddTransporteTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));

        return $this->_addform;
    }

    private function getTransporteModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Levanto el transporte para completar el form.*/
        try
        {
            $transportesTable = new Transportes();
            $row = $transportesTable->getTransporteByID( $id );
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }

        $this->_modform = new Zend_Form();
        $this->_modform ->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $codBandera = $this->_modform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
                    ->setValue($row->codBanderaName() )
                    ->addValidator(new CV_Validate_Bandera());

        try
        {
            $mediosTable = new Medios();
            $mediosOptions =  $mediosTable->getMediosArray();
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }
        $codMedio = $this->_modform->createElement('select', 'codMedio');
        $codMedio   ->setValue( $row->codMedio() )
                    ->setRequired(true)
                    ->setLabel('*' . $this->language->_('Medio'))
                    ->setMultiOptions($mediosOptions);

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 400))
             ->setRequired(true);

        $observaciones = $this->_modform->createElement('text', 'observaciones',
                array('label' => $this->language->_('Observaciones'))
                                                        );
        $observaciones  ->setValue($row->observaciones() )
                        ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 400))
                        ->setRequired(False);

        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );

        // Add elements to form:
        $this->_modform->addElement($name)
                       ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera))
                       ->addElement($codMedio)
                       ->addElement($observaciones)
             ->addElement('hidden', 'ModTransporteTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }


    private function getTransporteSearchForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform  ->setAction($this->_baseUrl)
                            ->setName('form')
                            ->setMethod('get');

        $transporte = $this->_searchform->createElement('text', 'consulta', array('label' => $this->language->_('Nombre')));
        $transporte       ->addValidator($alnumWithWS)
                     ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
        $this->_searchform->addElement($transporte)
             ->addElement('hidden', 'SearchTransporteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }

    public function getdataAction()
    {
        $arr = array();
        $aux = array();

       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->layout()->disableLayout();

        if ( $this->getRequest()->getParam('query') != null )
        {
            $this->_name = $this->getRequest()->getParam('query');

            $model = new Transportes();
            $data = $model->fetchAll("NOMBRE_BUQ LIKE '" .  $this->_name . "%' AND DELETED LIKE '0'");

            foreach ($data as $row)
            {
                array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
            }
	
            $arr = array("Resultset" => array("Result" => $aux));
	
            try
            {
                $responseDataJsonEncoded = Zend_Json::encode($arr);
                $this->getResponse()->setHeader('Content-Type', 'application/json')
                                    ->setBody($responseDataJsonEncoded);

            }
            catch(Zend_Json_Exception $e)
            {
                // handle and generate HTTP error code response, see below
                $this->getResponse()->setHeader('Content-Type', 'application/json')
                                    ->setBody('[{Error}]');
            }
        }
   }
}
?>
