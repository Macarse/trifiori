<?php
class user_ImportacionesController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
    }

    public function addimportacionesAction()
    {
        $this->view->headTitle($this->language->_("Agregar Importación"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddImportacionTrack']))
            {
                $this->_addform = $this->getImportacionAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $importacionesTable = new Importaciones();
                        $importacionesTable->addImportacion(
                                                            $values['orden'],
                                                            $values['nameDestinacion'],
                                                            $values['nameBandera'],
                                                            $values['codCanal'],
                                                            $values['nameGiro'],
                                                            $values['nameCliente'],
                                                            $values['nameCarga'],
                                                            $values['nameTransporte'],
                                                            $values['nameMoneda'],
                                                            $values['nameOpp'],
                                                            $values['referencia'],
                                                            $values['fechaIngreso'],
                                                            $values['originalCopia'],
                                                            $values['desMercaderias'],
                                                            $values['valorFactura'],
                                                            $values['docTransporte'],
                                                            $values['ingresoPuerto'],
                                                            $values['DESnroDoc'],
                                                            $values['DESvencimiento'],
                                                            $values['DESbl'],
                                                            $values['DESdeclaracion'],
                                                            $values['DESpresentado'],
                                                            $values['DESsalido'],
                                                            $values['DEScargado'],
                                                            $values['DESfactura'],
                                                            $values['DEsfechaFactura']
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

        $this->view->importacionAddForm = $this->getImportacionAddForm();
    }

    public function listimportacionesAction()
    {
        $this->view->headTitle($this->language->_("Listar Importaciones"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getImportacionSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $importacionesTable = new Importaciones();
                $impo = $importacionesTable->searchImportacion($_GET);
                $busqueda = "";
                
                if (isset($_GET["searchOrden"]))
                {
                    $busqueda = "&searchOrden=" . $_GET["searchOrden"];
                }
                else
                {
                    $busqueda = "&searchOrden=";
                }
                
                if (isset($_GET["searchCliente"]))
                {
                    $busqueda = $busqueda . "&searchCliente=" . $_GET["searchCliente"];
                }
                else
                {
                    $busqueda = $busqueda . "&searchCliente=";
                }
                
                if (isset($_GET["searchCarga"]))
                {
                    $busqueda = $busqueda . "&searchCarga=" . $_GET["searchCarga"];
                }
                else
                {
                    $busqueda = $busqueda . "&searchCarga=";
                }
                
                Zend_Registry::set('busqueda', $busqueda);
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($impo, $importacionesTable));
                //$paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($exportacionesTable->select()->where("ORDEN < 10000"), $exportacionesTable));
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
        $this->view->importacionSearchForm = $this->getImportacionSearchForm();
    }
    
    public function removeimportacionesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
        }
        else
        {
            try
            {
            $importacionesTable = new Importaciones();
            $importacionesTable->removeImportacion( $this->getRequest()->getParam('id') );
            $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
            $this->_flashMessenger->addMessage($this->language->_($error));
            }
        }

        $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
    }

    public function modimportacionesAction()
    {
        $this->view->headTitle($this->language->_("Modificar Importación"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->importacionModForm = $this->getImportacionModForm($this->_id)) == null)
            {
               $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModImportacionTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $importacionesTable = new Importaciones();
                        $importacionesTable->modifyImportacion( $this->_id,
                                                                $values['orden'],
                                                                $values['nameDestinacion'],
                                                                $values['nameBandera'],
                                                                $values['codCanal'],
                                                                $values['nameGiro'],
                                                                $values['nameCliente'],
                                                                $values['nameCarga'],
                                                                $values['nameTransporte'],
                                                                $values['nameMoneda'],
                                                                $values['nameOpp'],
                                                                $values['referencia'],
                                                                $values['fechaIngreso'],
                                                                $values['originalCopia'],
                                                                $values['desMercaderias'],
                                                                $values['valorFactura'],
                                                                $values['docTransporte'],
                                                                $values['ingresoPuerto'],
                                                                $values['DESnroDoc'],
                                                                $values['DESvencimiento'],
                                                                $values['DESbl'],
                                                                $values['DESdeclaracion'],
                                                                $values['DESpresentado'],
                                                                $values['DESsalido'],
                                                                $values['DEScargado'],
                                                                $values['DESfactura'],
                                                                $values['DEsfechaFactura']
                                                            );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->_flashMessenger->addMessage($this->language->_($error));
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
                }
            }
        }
    }

    private function getImportacionAddForm()
    {

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)
                        ->setMethod('post')
                        ->setName('form');

        $orden = $this->_addform->createElement('text', 'orden',
                    array('label' => '*' . $this->language->_('Órden')));
        $orden  ->addValidator('int')
                ->addValidator(new CV_Validate_ImportacionExiste())
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $codDestinacion = $this->_addform->createElement('text', 'nameDestinacion',
                array('label' => '*' . $this->language->_('Destinación'), 'id' => 'idnameDestinacion'));
        $codDestinacion ->setRequired(true)
                        ->addValidator(new CV_Validate_Destinacion());


        $codBandera = $this->_addform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
                    ->addValidator(new CV_Validate_Bandera());

        $canalesTable = new Canales();
        $canalesOptions =  $canalesTable->getCanalesArray();

        $codCanal = $this->_addform->createElement('select', 'codCanal');
        $codCanal   ->setRequired(true)
                    ->setLabel('*' . $this->language->_('Canal'))
                    ->setMultiOptions($canalesOptions);

        $codGiro = $this->_addform->createElement('text', 'nameGiro',
                array('label' => $this->language->_('Giro'), 'id' => 'idnameGiro'));
        $codGiro ->setRequired(False)
                 ->addValidator(new CV_Validate_Giro());

        $codCliente = $this->_addform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true)
                    ->addValidator(new CV_Validate_Cliente());

        $codCarga = $this->_addform->createElement('text', 'nameCarga',
                array('label' =>'*' .  $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga ->setRequired(true)
                    ->addValidator(new CV_Validate_Carga());

        $codTransporte = $this->_addform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  ->setRequired(true)
						->addValidator(new CV_Validate_Transporte());

        $codMoneda = $this->_addform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda ->setRequired(true)
                    ->addValidator(new CV_Validate_Moneda());

        $codOpp = $this->_addform->createElement('text', 'nameOpp',
                array('label' => $this->language->_('Opp'), 'id' => 'idnameOpp'));
        $codOpp ->setRequired(False)
                ->addValidator(new CV_Validate_Opp());

        $referencia = $this->_addform->createElement('text', 'referencia',
                array('label' => '*' . $this->language->_('Referencia')));
        $referencia ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 150))
                    ->setRequired(True);

        $fechaIngreso = $this->_addform->createElement('text', 'fechaIngreso',
                array('label' =>'*' .  $this->language->_('Fecha de Ingreso'),
                'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $oriCpy = array( 'c' => $this->language->_('Copia'), 'o' => $this->language->_('Original'));

        $originalCopia = $this->_addform->createElement('select', 'originalCopia');
        $originalCopia  ->setOrder(20)
                        ->setLabel('*' . $this->language->_('Original/Copia'))
                        ->setRequired(true)
                        ->setMultiOptions($oriCpy);


        $desMercaderias = $this->_addform->createElement('text', 'desMercaderias',
                array('label' => '*' . $this->language->_('Descripción Mercadería')));
        $desMercaderias ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(True);

        $valorFactura = $this->_addform->createElement('text', '$valorFactura',
                array('label' => '*' . $this->language->_('Valor Factura')));
        $valorFactura   ->addValidator('float')
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(True);

        $docTransporte = $this->_addform->createElement('text', 'docTransporte',
                    array('label' => '*' . $this->language->_('Documentación Transporte')));
        $docTransporte  ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 30))
                        ->setRequired(true);


        $ingresoPuerto = $this->_addform->createElement('text', 'ingresoPuerto',
                array('label' => $this->language->_('Ingreso a Puerto'),
                 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $DESnroDoc = $this->_addform->createElement('text', 'DESnroDoc',
                    array('label' => '*' . $this->language->_('Despacho: Número de Documento')));
        $DESnroDoc  ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(True);


        $DESvencimiento = $this->_addform->createElement('text', 'DESvencimiento',
                array('label' => $this->language->_('Despacho: Vencimiento'),
                 'id' => 'idDESVencimineto', 'onKeyPress' => "keyCalendar(event,'calDesVencimiento');"));
        $DESvencimiento ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $DESbl = $this->_addform->createElement('text', 'DESbl',
                    array('label' => $this->language->_('Despacho: B/L')));
        $DESbl  ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 50))
                ->setRequired(False);

        $DESdeclaracion = $this->_addform->createElement('text', 'DESdeclaracion',
                    array('label' => '*' . $this->language->_('Despacho: Declaración')));
        $DESdeclaracion ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 10))
                        ->setRequired(True);

        $DESpresentado = $this->_addform->createElement('text', 'DESpresentado',
                array('label' => '*' . $this->language->_('Despacho: Presentado'),
                 'id' => 'idDESPresentado', 'onKeyPress' => "keyCalendar(event,'calDesPresentado');"));
        $DESpresentado ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DESsalido = $this->_addform->createElement('text', 'DESsalido',
                array('label' => '*' . $this->language->_('Despacho: Salido'),
                 'id' => 'idDESSalido', 'onKeyPress' => "keyCalendar(event,'calDESsalido');"));
        $DESsalido ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DEScargado = $this->_addform->createElement('text', 'DEScargado',
                array('label' => '*' . $this->language->_('Despacho: Cargado'),
                 'id' => 'idDESCargado', 'onKeyPress' => "keyCalendar(event,'calDEScargado');"));
        $DEScargado ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DESfactura = $this->_addform->createElement('text', 'DESfactura',
                    array('label' => $this->language->_('Despacho: Factura')));
        $DESfactura ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 50))
                        ->setRequired(False);

        $DEsfechaFactura = $this->_addform->createElement('text', 'DEsfechaFactura',
                array('label' => $this->language->_('Despacho: Fecha Factura'),
                 'id' => 'idDESFechaFactura', 'onKeyPress' => "keyCalendar(event,'calDEsfechaFactura');"));
        $DEsfechaFactura ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);


        $decoradorDestinacion = array(
                                    'ViewHelper',
                                    'Errors',
                                    array('HtmlTag', array('tag' => 'div', 'id' => 'iddestautocomplete'))
                                    );

        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );

        $decoradorGiro = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idgirosautocomplete'))
                                );

        $decoradorCliente = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idclientesautocomplete'))
                                );

        $decoradorCarga = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idcargasautocomplete'))
                                );

        $decoradorTransporte = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idtransportesautocomplete'))
                                );

        $decoradorMoneda = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idmonedasautocomplete'))
                                );

        $decoradorOpp = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idoppsautocomplete'))
                                );

        // Add elements to form:
        $this->_addform ->addElement($orden)
                        ->addElement($codDestinacion)
                        ->addElement('hidden', 'autodes', array( 'decorators' => $decoradorDestinacion))
                        ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera))
                        ->addElement($codGiro)
                        ->addElement('hidden', 'autogiros', array( 'decorators' => $decoradorGiro))
                        ->addElement($codCliente)
                        ->addElement('hidden', 'autocliente', array( 'decorators' => $decoradorCliente))
                        ->addElement($codCarga)
                        ->addElement('hidden', 'autocarga', array( 'decorators' => $decoradorCarga))
                        ->addElement($codTransporte)
                        ->addElement('hidden', 'autotrans', array( 'decorators' => $decoradorTransporte))
                        ->addElement($codMoneda)
                        ->addElement('hidden', 'automon', array( 'decorators' => $decoradorMoneda))
                        ->addElement($codOpp)
                        ->addElement('hidden', 'autoopp', array( 'decorators' => $decoradorOpp))
                        ->addElement($codCanal)
                        ->addElement($referencia)
                        ->addElement($fechaIngreso)
                        ->addElement($originalCopia)
                        ->addElement($desMercaderias)
                        ->addElement($valorFactura)
                        ->addElement($docTransporte)
                        ->addElement($ingresoPuerto)
                        ->addElement($DESnroDoc)
                        ->addElement($DESvencimiento)
                        ->addElement($DESbl)
                        ->addElement($DESdeclaracion)
                        ->addElement($DESpresentado)
                        ->addElement($DESsalido)
                        ->addElement($DEScargado)
                        ->addElement($DESfactura)
                        ->addElement($DEsfechaFactura)
                        ->addElement('hidden', 'AddImportacionTrack', array('values' => 'logPost'))
                        ->addElement('submit', 'Ingresar', array('label' => 'Agregar'));


        return $this->_addform;
    }

    private function getImportacionSearchForm()
    {
        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        $this->_searchform = new Zend_Form();
        $this->_searchform->setAction($this->_baseUrl)->setMethod('get');

        $searchOrden = $this->_searchform->createElement('text', 'searchOrden',
                array('label' => $this->language->_('Órden')));
        $searchOrden    ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11));

        $searchCliente = $this->_searchform->createElement('text', 'searchCliente',
                array('label' => $this->language->_('Cliente')));
        $searchCliente ->addValidator($alnumWithWS);

        $searchCarga = $this->_searchform->createElement('text', 'searchCarga',
                array('label' => $this->language->_('Carga')));
        $searchCarga ->addValidator('alnum');

        $decoradorSearchOrden = array(
                                      'ViewHelper',
                                      'Errors',
                                      array('HtmlTag', array('tag' => 'div', 'id' => 'divbusquedaorden'))
                                     );

        $decoradorSearchCliente = array(
                                        'ViewHelper',
                                        'Errors',
                                        array('HtmlTag', array('tag' => 'div', 'id' => 'divbusquedacliente'))
                                       );

        $decoradorSearchCarga = array(
                                      'ViewHelper',
                                      'Errors',
                                      array('HtmlTag', array('tag' => 'div', 'id' => 'divbusquedacarga'))
                                     );

    // Add elements to form:
            $this->_searchform  ->addElement($searchOrden)
            ->addElement('hidden', 'decobusqueda', array( 'decorators' => $decoradorSearchOrden))
            ->addElement($searchCliente)
            ->addElement('hidden', 'decocliente', array( 'decorators' => $decoradorSearchCliente))
            ->addElement($searchCarga)
            ->addElement('hidden', 'decocarga', array( 'decorators' => $decoradorSearchCarga))
            ->addElement('hidden', 'SearchImportacionTrack', array('values' => 'logPost'))
                        //->addElement('hidden', 'searchOrden', array('id' => 'idsearchOrden'))
                        //->addElement('hidden', 'searchCliente', array('id' => 'idsearchCliente'))
                        //->addElement('hidden', 'searchCarga', array('id' => 'idsearchCarga'))
            ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

    return $this->_searchform;
    }
    
    private function getImportacionModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Levanto la exportacion para completar el form.*/
        $importacionesTable = new Importaciones();
        $row = $importacionesTable->getImportacionByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
                        ->setMethod('post')
                        ->setName('form');

        
        $orden = $this->_modform->createElement('text', 'orden',
                    array('label' => '*' . $this->language->_('Órden')));
        $orden  ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11))
				->setValue($row->orden() )
                ->setRequired(true);

        $codDestinacion = $this->_modform->createElement('text', 'nameDestinacion',
                array('label' => '*' . $this->language->_('Destinación'), 'id' => 'idnameDestinacion'));
        $codDestinacion ->setRequired(true)
						->setValue($row->codDestinacionName() )
                        ->addValidator(new CV_Validate_Destinacion());


        $codBandera = $this->_modform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
					->setValue($row->codBanderaName() )
                    ->addValidator(new CV_Validate_Bandera());

        $canalesTable = new Canales();
        $canalesOptions =  $canalesTable->getCanalesArray();

        $codCanal = $this->_modform->createElement('select', 'codCanal');
        $codCanal   ->setRequired(true)
					->setValue($row->codCanal() )
                    ->setLabel('*' . $this->language->_('Canal'))
                    ->setMultiOptions($canalesOptions);

        $codGiro = $this->_modform->createElement('text', 'nameGiro',
                array('label' => $this->language->_('Giro'), 'id' => 'idnameGiro'));
        $codGiro ->setRequired(False)
	 			 ->setValue($row->codGiroName() )
                 ->addValidator(new CV_Validate_Giro());

        $codCliente = $this->_modform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true)
					->setValue($row->codClienteName() )
                    ->addValidator(new CV_Validate_Cliente());

        $codCarga = $this->_modform->createElement('text', 'nameCarga',
                array('label' =>'*' .  $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga ->setRequired(true)
				  ->setValue($row->codCargaName() )
                  ->addValidator(new CV_Validate_Carga());

        $codTransporte = $this->_modform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  ->setRequired(true)
						->setValue($row->codTransporteName() )
						->addValidator(new CV_Validate_Transporte());

        $codMoneda = $this->_modform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda ->setRequired(true)
				   ->setValue($row->codMonedaName() )
                   ->addValidator(new CV_Validate_Moneda());

        $codOpp = $this->_modform->createElement('text', 'nameOpp',
                array('label' => $this->language->_('Opp'), 'id' => 'idnameOpp'));
        $codOpp ->setRequired(False)
				->setValue($row->codOppName() )
                ->addValidator(new CV_Validate_Opp());

        $referencia = $this->_modform->createElement('text', 'referencia',
                array('label' => '*' . $this->language->_('Referencia')));
        $referencia ->addValidator($alnumWithWS)
					->setValue($row->referencia() )
                    ->addValidator('stringLength', false, array(1, 150))
                    ->setRequired(True);

        $fechaIngreso = $this->_modform->createElement('text', 'fechaIngreso',
                array('label' =>'*' .  $this->language->_('Fecha de Ingreso'),
                'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->addValidator(new CV_Validate_Fecha())
						->setValue($row->fechaIngreso() )
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $oriCpy = array( 'c' => $this->language->_('Copia'), 'o' => $this->language->_('Original'));

        $originalCopia = $this->_modform->createElement('select', 'originalCopia');
        $originalCopia  ->setOrder(20)
						->setValue($row->originalCopia() )
                        ->setLabel('*' . $this->language->_('Original/Copia'))
                        ->setRequired(true)
                        ->setMultiOptions($oriCpy);


        $desMercaderias = $this->_modform->createElement('text', 'desMercaderias',
                array('label' => '*' . $this->language->_('Descripción Mercadería')));
        $desMercaderias ->addValidator($alnumWithWS)
						->setValue($row->desMercaderias() )
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(True);

        $valorFactura = $this->_modform->createElement('text', '$valorFactura',
                array('label' => '*' . $this->language->_('Valor Factura')));
        $valorFactura   ->addValidator('float')
						->setValue($row->valorFactura() )
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(True);

        $docTransporte = $this->_modform->createElement('text', 'docTransporte',
                    array('label' => '*' . $this->language->_('Documentación Transporte')));
        $docTransporte  ->addValidator($alnumWithWS)
						->setValue($row->docTransporte() )
                        ->addValidator('stringLength', false, array(1, 30))
                        ->setRequired(true);


        $ingresoPuerto = $this->_modform->createElement('text', 'ingresoPuerto',
                array('label' => $this->language->_('Ingreso a Puerto'),
                 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->addValidator(new CV_Validate_Fecha())
						->setValue($row->ingresoPuerto() )
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $DESnroDoc = $this->_modform->createElement('text', 'DESnroDoc',
                    array('label' => '*' . $this->language->_('Despacho: Número de Documento')));
        $DESnroDoc  ->addValidator($alnumWithWS)
					->setValue($row->DESnroDoc() )
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(True);

        $DESvencimiento = $this->_modform->createElement('text', 'DESvencimiento',
                array('label' => $this->language->_('Despacho: Vencimiento'),
                 'id' => 'idDESVencimineto', 'onKeyPress' => "keyCalendar(event,'calDesVencimiento');"));
        $DESvencimiento ->addValidator(new CV_Validate_Fecha())
						->setValue($row->DESvencimiento() )
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $DESbl = $this->_modform->createElement('text', 'DESbl',
                    array('label' => $this->language->_('Despacho: B/L')));
        $DESbl  ->addValidator($alnumWithWS)
				->setValue($row->DESbl() )
                ->addValidator('stringLength', false, array(1, 50))
                ->setRequired(False);

        $DESdeclaracion = $this->_modform->createElement('text', 'DESdeclaracion',
                    array('label' => '*' . $this->language->_('Despacho: Declaración')));
        $DESdeclaracion ->addValidator($alnumWithWS)
						->setValue($row->DESdeclaracion() )
                        ->addValidator('stringLength', false, array(1, 10))
                        ->setRequired(True);

        $DESpresentado = $this->_modform->createElement('text', 'DESpresentado',
                array('label' => '*' . $this->language->_('Despacho: Presentado'),
                 'id' => 'idDESPresentado', 'onKeyPress' => "keyCalendar(event,'calDesPresentado');"));
        $DESpresentado ->addValidator(new CV_Validate_Fecha())
					   ->setValue($row->DESpresentado() )
                       ->addValidator('stringLength', false, array(1, 12))
                       ->setRequired(True);

        $DESsalido = $this->_modform->createElement('text', 'DESsalido',
                array('label' => '*' . $this->language->_('Despacho: Salido'),
                 'id' => 'idDESSalido', 'onKeyPress' => "keyCalendar(event,'calDESsalido');"));
        $DESsalido ->addValidator(new CV_Validate_Fecha())
				   ->setValue($row->DESsalido() )
                   ->addValidator('stringLength', false, array(1, 12))
                   ->setRequired(True);

        $DEScargado = $this->_modform->createElement('text', 'DEScargado',
                array('label' => '*' . $this->language->_('Despacho: Cargado'),
                 'id' => 'idDESCargado', 'onKeyPress' => "keyCalendar(event,'calDEScargado');"));
        $DEScargado ->addValidator(new CV_Validate_Fecha())
					->setValue($row->DEScargado() )
					->addValidator('stringLength', false, array(1, 12))
					->setRequired(True);

        $DESfactura = $this->_modform->createElement('text', 'DESfactura',
                    array('label' => $this->language->_('Despacho: Factura')));
        $DESfactura ->addValidator($alnumWithWS)
					->setValue($row->DESfactura() )
					->addValidator('stringLength', false, array(1, 50))
					->setRequired(False);

        $DEsfechaFactura = $this->_modform->createElement('text', 'DEsfechaFactura',
                array('label' => $this->language->_('Despacho: Fecha Factura'),
                 'id' => 'idDESFechaFactura', 'onKeyPress' => "keyCalendar(event,'calDEsfechaFactura');"));
        $DEsfechaFactura ->addValidator(new CV_Validate_Fecha())
						 ->setValue($row->DEsfechaFactura() )
 						 ->addValidator('stringLength', false, array(1, 12))
						 ->setRequired(False);


        $decoradorDestinacion = array(
                                    'ViewHelper',
                                    'Errors',
                                    array('HtmlTag', array('tag' => 'div', 'id' => 'iddestautocomplete'))
                                    );

        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );

        $decoradorGiro = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idgirosautocomplete'))
                                );

        $decoradorCliente = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idclientesautocomplete'))
                                );

        $decoradorCarga = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idcargasautocomplete'))
                                );

        $decoradorTransporte = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idtransportesautocomplete'))
                                );

        $decoradorMoneda = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idmonedasautocomplete'))
                                );

        $decoradorOpp = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idoppsautocomplete'))
                                );

        // Add elements to form:
        $this->_modform ->addElement($orden)
                        ->addElement($codDestinacion)
                        ->addElement('hidden', 'autodes', array( 'decorators' => $decoradorDestinacion))
                        ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera))
                        ->addElement($codGiro)
                        ->addElement('hidden', 'autogiros', array( 'decorators' => $decoradorGiro))
                        ->addElement($codCliente)
                        ->addElement('hidden', 'autocliente', array( 'decorators' => $decoradorCliente))
                        ->addElement($codCarga)
                        ->addElement('hidden', 'autocarga', array( 'decorators' => $decoradorCarga))
                        ->addElement($codTransporte)
                        ->addElement('hidden', 'autotrans', array( 'decorators' => $decoradorTransporte))
                        ->addElement($codMoneda)
                        ->addElement('hidden', 'automon', array( 'decorators' => $decoradorMoneda))
                        ->addElement($codOpp)
                        ->addElement('hidden', 'autoopp', array( 'decorators' => $decoradorOpp))
                        ->addElement($codCanal)
                        ->addElement($referencia)
                        ->addElement($fechaIngreso)
                        ->addElement($originalCopia)
                        ->addElement($desMercaderias)
                        ->addElement($valorFactura)
                        ->addElement($docTransporte)
                        ->addElement($ingresoPuerto)
                        ->addElement($DESnroDoc)
                        ->addElement($DESvencimiento)
                        ->addElement($DESbl)
                        ->addElement($DESdeclaracion)
                        ->addElement($DESpresentado)
                        ->addElement($DESsalido)
                        ->addElement($DEScargado)
                        ->addElement($DESfactura)
                        ->addElement($DEsfechaFactura)
                        ->addElement('hidden', 'ModImportacionTrack', array('values' => 'logPost'))
                        ->addElement('submit', 'Ingresar', array('label' => 'Agregar'));

        return $this->_modform;
    }

}
?>
