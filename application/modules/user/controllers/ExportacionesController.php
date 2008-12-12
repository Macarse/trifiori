<?php
class user_ExportacionesController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
    }

    public function addexportacionesAction()
    {
        $this->view->headTitle($this->language->_("Agregar Exportación"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddExportacionTrack']))
            {
                $this->_addform = $this->getExportacionAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $exportacionesTable = new Exportaciones();
                        $exportacionesTable->addExportacion(
                                                            $values['orden'],
                                                            $values['nameTransporte'],
                                                            $values['nameCliente'],
                                                            $values['nameBandera'],
                                                            $values['nameMoneda'],
                                                            $values['nameDestinacion'],
                                                            $values['nameCarga'],
                                                            $values['referencia'],
                                                            $values['fechaIngreso'],
                                                            $values['desMercaderias'],
                                                            $values['valorFactura'],
                                                            $values['vencimiento'],
                                                            $values['ingresoPuerto'],
                                                            $values['PERnroDoc'],
                                                            $values['PERpresentado'],
                                                            $values['PERfactura'],
                                                            $values['PERfechaFactura']
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

        $this->view->exportacionAddForm = $this->getExportacionAddForm();
    }

    public function listexportacionesAction()
    {
        $this->view->headTitle($this->language->_("Listar Exportaciones"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getExportacionSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $exportacionesTable = new Exportaciones();
                $expo = $exportacionesTable->searchExportacion($_GET);
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
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($expo, $exportacionesTable));
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
        $this->view->exportacionSearchForm = $this->getExportacionSearchForm();
    }

    public function detailsAction()
    {
        $id = $_GET["id"];
        $results = null;
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        try
        {
            $table = new Exportaciones();
            $where = "CODIGO_EXP = " . $id;
            $results = $table->fetchAll($where);
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }

        echo "<div class=\"hd\">" . $this->language->_("Detalles de Exportación") . "</div>";
        
        echo "<div class=\"bd\">";
        if ($results != null)
        {
            foreach ($results as $result)
            {
                echo "<b>" . $this->language->_("Órden: ") . "</b>" . $result->orden() . "<br />";
                echo "<b>" . $this->language->_("Transporte: ") . "</b>" . $result->codTransporteName() .  "<br />";
                echo "<b>" . $this->language->_("Cliente: ") . "</b>" . $result->codClienteName() . "<br />";
                echo "<b>" . $this->language->_("Bandera: ") . "</b>" . $result->codBanderaName() .  "<br />";
                echo "<b>" . $this->language->_("Moneda: ") . "</b>" . $result->codMonedaName() .  "<br />";
                echo "<b>" . $this->language->_("Giro: ") . "</b>" . $result->codGiroName() .  "<br />";
                echo "<b>" . $this->language->_("Destinación: ") . "</b>" . $result->codDestinacionName() .  "<br />";
                echo "<b>" . $this->language->_("Carga: ") . "</b>" . $result->codCargaName() .  "<br />";
                echo "<b>" . $this->language->_("Fecha de Ingreso: ") . "</b>" . $result->fechaIngreso() . "<br />";
                echo "<b>" . $this->language->_("Descripción de la mercadería: ") . "</b>" . $result->desMercaderias() .  "<br />";
                echo "<b>" . $this->language->_("Valor de la factura: ") . "</b>" . $result->valorFactura() .  "<br />";
                echo "<b>" . $this->language->_("Fecha de vencimiento: ") . "</b>" . $result->vencimiento() .  "<br />";
                echo "<b>" . $this->language->_("Fecha de ingreso al puerto: ") . "</b>" . $result->ingresoPuerto() .  "<br />";
                echo "<b>" . $this->language->_("Número de permiso: ") . "</b>" . $result->PERnroDoc() .  "<br />";
                echo "<b>" . $this->language->_("Fecha en que fue presentado: ") . "</b>" . $result->PERpresentado() .  "<br />";
                echo "<b>" . $this->language->_("Número de factura: ") . "</b>" . $result->PERfactura() .  "<br />";
                echo "<b>" . $this->language->_("Fecha de la factura: ") . "</b>" . $result->PERfechaFactura() .  "<br />";   
            }
        }
        echo "</div>";
        echo "<div class=\"ft\">" . $this->language->_("Trifiori 2008") . "</div>";
    }

    public function removeexportacionesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
        }
        else
        {
            try
            {
            $exportacionesTable = new Exportaciones();
            $exportacionesTable->removeExportacion( $this->getRequest()->getParam('id') );
            $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
            $this->_flashMessenger->addMessage($this->language->_($error));
            }
        }

        $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
    }

    public function modexportacionesAction()
    {
        $this->view->headTitle($this->language->_("Modificar Exportación"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->exportacionModForm = $this->getExportacionModForm($this->_id)) == null)
            {
               $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModExportacionTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $exportacionesTable = new Exportaciones();
                        $exportacionesTable->modifyExportacion( $this->_id,
                                                                $values['orden'],
                                                                $values['nameTransporte'],
                                                                $values['nameCliente'],
                                                                $values['nameBandera'],
                                                                $values['nameMoneda'],
                                                                $values['nameDestinacion'],
                                                                $values['nameCarga'],
                                                                $values['referencia'],
                                                                $values['fechaIngreso'],
                                                                $values['desMercaderias'],
                                                                $values['valorFactura'],
                                                                $values['vencimiento'],
                                                                $values['ingresoPuerto'],
                                                                $values['PERnroDoc'],
                                                                $values['PERpresentado'],
                                                                $values['PERfactura'],
                                                                $values['PERfechaFactura']
                                                            );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->_flashMessenger->addMessage($this->language->_($error));
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
                }
            }
        }
    }

    private function getExportacionAddForm()
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
                array('label' => '*' . $this->language->_('Órden'), 'id' => 'idnameOrden'));
        $orden  ->addValidator('int')
                ->addValidator(new CV_Validate_ExportacionExiste())
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $codTransporte = $this->_addform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  ->setRequired(true)
                        ->addValidator(new CV_Validate_Transporte());

        $codCliente = $this->_addform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true)
                    ->addValidator(new CV_Validate_Cliente());

        $codBandera = $this->_addform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
                    ->addValidator(new CV_Validate_Bandera());

        $codMoneda = $this->_addform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda  ->setRequired(true)
                    ->addValidator(new CV_Validate_Moneda());

        $codDestinacion = $this->_addform->createElement('text', 'nameDestinacion',
                array('label' => '*' . $this->language->_('Destinación'), 'id' => 'idnameDestinacion'));
        $codDestinacion ->setRequired(true)
                        ->addValidator(new CV_Validate_Destinacion());

        $codCarga = $this->_addform->createElement('text', 'nameCarga',
                array('label' =>'*' .  $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga   ->setRequired(true)
                    ->addValidator(new CV_Validate_Carga());

        $referencia = $this->_addform->createElement('text', 'referencia',
                array('label' => $this->language->_('Referencia')));
        $referencia ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $fechaIngreso = $this->_addform->createElement('text', 'fechaIngreso',
                array('label' =>'*' .  $this->language->_('Fecha de Ingreso'),
                'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $desMercaderias = $this->_addform->createElement('text', 'desMercaderias',
                array('label' => '*' . $this->language->_('Descripción Mercadería')));
        $desMercaderias ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(False);


        $valorFactura = $this->_addform->createElement('text', '$valorFactura',
                array('label' => $this->language->_('Valor Factura')));
        $valorFactura   ->addValidator('float')
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(False);


        $vencimiento = $this->_addform->createElement('text', 'vencimiento',
                array('label' => '*' . $this->language->_('Vencimiento'),
                'id' => 'idVencimiento', 'onKeyPress' => "keyCalendar(event,'calVencimiento');"));
        $vencimiento   ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $ingresoPuerto = $this->_addform->createElement('text', 'ingresoPuerto',
                array('label' => $this->language->_('Ingreso a Puerto'),
                 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        /*TODO: ADD Validator*/
        $PERnroDoc = $this->_addform->createElement('text', 'PERnroDoc',
                array('label' => '*' . $this->language->_('Número de Permiso')));
        $PERnroDoc  ->addValidator('stringLength', false, array(1, 30))
                    ->setRequired(True);

        $PERpresentado = $this->_addform->createElement('text', 'PERpresentado',
                array('label' => '*' . $this->language->_('Permiso Presentado'),
                'id' => 'idPerPre', 'onKeyPress' => "keyCalendar(event,'calPerPre');"));
        $PERpresentado   ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD Validator*/
        $PERfactura = $this->_addform->createElement('text', 'PERfactura',
                array('label' => $this->language->_('Permiso Factura')));
        $PERfactura ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $PERfechaFactura = $this->_addform->createElement('text', 'PERfechaFactura',
                array('label' => $this->language->_('Permiso Fecha de Factura'),
                'id' => 'idPerFecFac', 'onKeyPress' => "keyCalendar(event,'calFecFac');"));
        $PERfechaFactura    ->addValidator(new CV_Validate_Fecha())
                            ->addValidator('stringLength', false, array(1, 12))
                            ->setRequired(False);

        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );

        $decoradorCliente = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idclientesautocomplete'))
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

        $decoradorDestinacion = array(
                                    'ViewHelper',
                                    'Errors',
                                    array('HtmlTag', array('tag' => 'div', 'id' => 'iddestautocomplete'))
                                    );

        $decoradorCarga = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idcargasautocomplete'))
                                );

        // Add elements to form:
        $this->_addform ->addElement('hidden', 'AddExportacionTrack', array('values' => 'logPost'))
                        ->addElement($orden)
                        ->addElement($codTransporte)
                        ->addElement('hidden', 'autotrans', array( 'decorators' => $decoradorTransporte))
                        ->addElement($codCliente)
                        ->addElement('hidden', 'autocliente', array( 'decorators' => $decoradorCliente))
                        ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera))
                        ->addElement($codMoneda)
                        ->addElement('hidden', 'automon', array( 'decorators' => $decoradorMoneda))
                        ->addElement($codDestinacion)
                        ->addElement('hidden', 'autodes', array( 'decorators' => $decoradorDestinacion))
                        ->addElement($codCarga)
                        ->addElement('hidden', 'autocarga', array( 'decorators' => $decoradorCarga))
                        ->addElement($referencia)
                        ->addElement($fechaIngreso)
                        ->addElement($desMercaderias)
                        ->addElement($valorFactura)
                        ->addElement($vencimiento)
                        ->addElement($ingresoPuerto)
                        ->addElement($PERnroDoc)
                        ->addElement($PERpresentado)
                        ->addElement($PERfactura)
                        ->addElement($PERfechaFactura)
                        ->addElement('hidden', 'codCarga', array('id' => 'idcodCarga'))
                        ->addElement('hidden', 'codDestinacion', array('id' => 'idcodDestinacion'))
                        ->addElement('hidden', 'codMoneda', array('id' => 'idcodMoneda'))
                        ->addElement('hidden', 'codTransporte', array('id' => 'idcodTransporte'))
                        ->addElement('hidden', 'codCliente', array('id' => 'idcodCliente'))
                        ->addElement('hidden', 'codBandera', array('id' => 'idcodBandera'))
                        ->addElement('submit', 'Ingresar', array('label' => 'Agregar'));

        return $this->_addform;
    }

   private function getExportacionSearchForm()
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
                        ->addElement('hidden', 'SearchExportacionTrack', array('values' => 'logPost'))
                        //->addElement('hidden', 'searchOrden', array('id' => 'idsearchOrden'))
                        //->addElement('hidden', 'searchCliente', array('id' => 'idsearchCliente'))
                        //->addElement('hidden', 'searchCarga', array('id' => 'idsearchCarga'))
                        ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }

    private function generateEstadisticas( $type , $from, $to)
    {
            $model = new Exportaciones();
            $data = $model->getEstadisticas($type , $from, $to);

            if ($data == null)
                return false;

            $xml = "<?xml version=\"1.0\" ?>\n\n<chart><chart_data>";
            $rowCant = "<row><null/>";
            $rowName = "<row><string></string>";
            $rowSeparacion = "";

            foreach($data as $row)
            {
                $rowCant .= "<number>" . $row['cantidad']. "</number>";
                $rowName .= "<string>" . $row['nombre'] . "</string>";
                $rowSeparacion .= "<number>4</number>";
            }

            $rowCant .= "</row>";
            $rowName .= "</row>";

            $xml .= $rowName . $rowCant;

            $xml .= "	</chart_data>	<chart_grid_h thickness='0' />	<chart_label shadow='low' color='000000' alpha='65' size='10' position='inside' as_percentage='false' />	<chart_pref select='false' drag='true' rotation_x='40' min_x='20' max_x='90' />	<chart_rect x='200' y='30' width='350' height='300' positive_alpha='0' />	<chart_transition type='spin' delay='.5' duration='0.20' order='category' />	<chart_type>3d pie</chart_type>	<draw>		<rect bevel='bg' layer='background' x='0' y='0' width='600' height='400' fill_color='4c5577' line_thickness='0' />		<text shadow='low' color='0' alpha='5' size='40' x='-160' y='340' width='600' height='50' h_align='center' v_align='middle'>Estadisticas</text>		<rect shadow='low' layer='background' x='-50' y='70' width='500' height='200' rotation='-5' fill_alpha='0' line_thickness='80' line_alpha='5' line_color='0' />	</draw>	<filter>		<shadow id='low' distance='2' angle='45' color='0' alpha='50' blurX='4' blurY='4' />		<bevel id='bg' angle='180' blurX='100' blurY='100' distance='5' highlightAlpha='0' shadowAlpha='15' type='inner' />		<bevel id='bevel1' angle='45' blurX='5' blurY='5' distance='1' highlightAlpha='25' highlightColor='ffffff' shadowAlpha='50' type='inner' />	</filter>		<legend bevel='bevel1' transition='dissolve' delay='0' duration='1' x='0' y='45' width='50' height='210' margin='10' fill_color='0' fill_alpha='20' line_color='000000' line_alpha='0' line_thickness='0' layout='horizontal' bullet='circle' size='12' color='ffffff' alpha='85' />	<series_explode>" . $rowSeparacion . "</series_explode></chart>";

        $fh = fopen("xml/expo_" . $type . ".xml", 'w') or die("can't open file");
        fwrite($fh, $xml);
        fclose($fh);

        return true;
    }

    private function getFormEstadisticas()
    {
        $this->_estform = new Zend_Form();
		$this->_estform->setAction($this->_baseUrl)
                                ->setMethod('get')
                                ->setName('form');

        $fechaDesde = $this->_estform->createElement('text', 'fechaDesde',
                    array('label' => '*' . $this->language->_('Fecha desde'),
                    'id' => 'idFechaDesde', 'onKeyPress' => "keyCalendar(event,'calFechaDesde');"));
        $fechaDesde     ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $fechaHasta = $this->_estform->createElement('text', 'fechaHasta',
                    array('label' => '*' . $this->language->_('Fecha hasta'),
                    'id' => 'idFechaHasta', 'onKeyPress' => "keyCalendar(event,'calFechaHasta');"));
        $fechaHasta     ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

     // Add elements to form:
        $this->_estform->addElement($fechaDesde)
             ->addElement($fechaHasta)
             ->addElement('hidden', 'estadisticasTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Generar', array('label' => $this->language->_('Generar')));

        return $this->_estform;

    }

    public function estadisticasAction()
    {
        $this->view->headTitle($this->language->_("Estadisticas"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
    
        $this->view->showNoRec = $this->view->showStat = false;

        if ( $this->getRequest()->getParam('type') != null )
        {
            $this->_estform = $this->getFormEstadisticas();
            $this->view->type = $this->_estType = $this->getRequest()->getParam('type');
            if (($this->_estType == 'pais') || ($this->_estType == 'destinacion') || ($this->_estType == 'cliente'))
            {
                if ( isset($_GET['estadisticasTrack']) )
                {
                    if ($this->_estform->isValid($_GET))
                    {
                        $this->view->showStat = true;
                        $values = $this->_estform->getValues();
                        if (!$this->generateEstadisticas($this->_estType, $values['fechaDesde'], $values['fechaHasta']))
                            $this->view->showNoRec = true;
                    }
                }
                $this->view->estform = $this->getFormEstadisticas();

            }
        }
    }

    private function getExportacionModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Levanto la exportacion para completar el form.*/
        $exportacionesTable = new Exportaciones();
        $row = $exportacionesTable->getExportacionByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
        }

        $this->_modform = new Zend_Form();
		$this->_modform->setAction($this->_baseUrl)
                                ->setMethod('post')
                                ->setName('form');

        $orden = $this->_modform->createElement('text', 'orden',
            array('label' => '*' . $this->language->_('Órden'), 'id' => 'idnameOrden'));
        $orden  ->setValue($row->orden() )
                ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $codTransporte = $this->_modform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  -> setRequired(true)
						->setValue($row->codTransporteName() )
					   ->addValidator(new CV_Validate_Transporte());

        $codCliente = $this->_modform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true)
					->setValue($row->codClienteName() )
					->addValidator(new CV_Validate_Cliente());

        $codBandera = $this->_modform->createElement('text', 'nameBandera',
                array('label' => '*' . $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true)
					->setValue($row->codBanderaName() )
					->addValidator(new CV_Validate_Bandera());

        $codMoneda = $this->_modform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda ->setRequired(true)
					->setValue($row->codMonedaName() )
					->addValidator(new CV_Validate_Moneda());

        $codDestinacion = $this->_modform->createElement('text', 'nameDestinacion',
                array('label' => '*' . $this->language->_('Destinación'), 'id' => 'idnameDestinacion'));
        $codDestinacion ->setRequired(true)
					->setValue($row->codDestinacionName() )
					->addValidator(new CV_Validate_Destinacion());

        $codCarga = $this->_modform->createElement('text', 'nameCarga',
                array('label' => '*' . $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga ->setRequired(true)
					->setValue($row->codCargaName() )
					->addValidator(new CV_Validate_Carga());


        $decoradorBandera = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idbanderasautocomplete'))
                                );

        $decoradorCliente = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idclientesautocomplete'))
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

        $decoradorDestinacion = array(
                                    'ViewHelper',
                                    'Errors',
                                    array('HtmlTag', array('tag' => 'div', 'id' => 'iddestautocomplete'))
                                    );

        $decoradorCarga = array(
                                'ViewHelper',
                                'Errors',
                                array('HtmlTag', array('tag' => 'div', 'id' => 'idcargasautocomplete'))
                                );

        $referencia = $this->_modform->createElement('text', 'referencia',
                                                     array('label' => $this->language->_('Referencia')));
        $referencia ->setValue($row->referencia() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $fechaIngreso = $this->_modform->createElement('text', 'fechaIngreso',
                    array('label' => '*' . $this->language->_('Fecha de Ingreso'),
                    'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->setValue($row->fechaIngreso() )
                        ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $desMercaderias = $this->_modform->createElement('text', 'desMercaderias',
                        array('label' => $this->language->_('Descripción Mercadería')));
        $desMercaderias ->setValue($row->desMercaderias() )
                        ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(False);


        $valorFactura = $this->_modform->createElement('text', '$valorFactura',
                        array('label' => $this->language->_('Valor Factura')));
        $valorFactura   ->setValue($row->valorFactura() )
                        ->addValidator('float')
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(False);


        $vencimiento = $this->_modform->createElement('text', 'vencimiento',
                        array('label' => '*' . $this->language->_('Vencimiento'),
                        'id' => 'idVencimiento', 'onKeyPress' => "keyCalendar(event,'calVencimiento');"));
        $vencimiento    ->setValue($row->vencimiento() )
                        ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD validator*/
        $PERnroDoc = $this->_modform->createElement('text', 'PERnroDoc',
                        array('label' => '*' . $this->language->_('Número de Permiso')));
        $PERnroDoc  ->setValue($row->PERnroDoc() )
                    ->addValidator('stringLength', false, array(1, 30))
                    ->setRequired(True);

        $ingresoPuerto = $this->_modform->createElement('text', 'ingresoPuerto',
                    array('label' => $this->language->_('Ingreso a Puerto'),
                    'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->setValue($row->ingresoPuerto() )
                        ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $PERpresentado = $this->_modform->createElement('text', 'PERpresentado',
                    array('label' => '*' . $this->language->_('Permiso Presentado'),
                    'id' => 'idPerPre', 'onKeyPress' => "keyCalendar(event,'calPerPre');"));
        $PERpresentado  ->setValue($row->PERpresentado() )
                        ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD Validator*/
        $PERfactura = $this->_modform->createElement('text', 'PERfactura',
                    array('label' => $this->language->_('Permiso Factura')));
        $PERfactura ->setValue($row->PERfactura() )
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $PERfechaFactura = $this->_modform->createElement('text', 'PERfechaFactura',
                    array('label' => $this->language->_('Permiso Fecha de Factura'),
                    'id' => 'idPerFecFac', 'onKeyPress' => "keyCalendar(event,'calFecFac');"));
        $PERfechaFactura    ->setValue($row->PERfechaFactura() )
                            ->addValidator(new CV_Validate_Fecha())
                            ->addValidator('stringLength', false, array(1, 12))
                            ->setRequired(False);

        // Add elements to form:
        $this->_modform ->addElement('hidden', 'ModExportacionTrack', array('values' => 'logPost'))
                        ->addElement($orden)
                        ->addElement($codTransporte)
                        ->addElement('hidden', 'autotrans', array( 'decorators' => $decoradorTransporte))
                        ->addElement($codCliente)
                        ->addElement('hidden', 'autocliente', array( 'decorators' => $decoradorCliente))
                        ->addElement($codBandera)
                        ->addElement('hidden', 'autobanderas', array( 'decorators' => $decoradorBandera ))
                        ->addElement($codMoneda)
                        ->addElement('hidden', 'automon', array( 'decorators' => $decoradorMoneda ))
                        ->addElement($codDestinacion)
                        ->addElement('hidden', 'autodes', array( 'decorators' => $decoradorDestinacion))
                        ->addElement($codCarga)
                        ->addElement('hidden', 'autocarga', array( 'decorators' => $decoradorCarga))
                        ->addElement($referencia)
                        ->addElement($fechaIngreso)
                        ->addElement($desMercaderias)
                        ->addElement($valorFactura)
                        ->addElement($vencimiento)
                        ->addElement($ingresoPuerto)
                        ->addElement($PERnroDoc)
                        ->addElement($PERpresentado)
                        ->addElement($PERfactura)
                        ->addElement($PERfechaFactura)
                        ->addElement('hidden', 'codBandera', array('id' => 'idcodBandera', 'value' => $row->codBandera()))
                        ->addElement('hidden', 'codDestinacion', array('id' => 'idcodDestinacion', 'value' => $row->codDestinacion() ))
                        ->addElement('hidden', 'codGiro', array('id' => 'idcodGiro', 'value' => $row->codGiro() ))
                        ->addElement('hidden', 'codMoneda', array('id' => 'idcodMoneda', 'value' => $row->codMoneda()))
                        ->addElement('hidden', 'codTransporte', array('id' => 'idcodTransporte', 'value' => $row->codTransporte() ))
                        ->addElement('hidden', 'codCliente', array('id' => 'idcodCliente', 'value' => $row->codCliente() ))
                        ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
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

            $model = new Exportaciones();
            $data = $model->fetchAll("ORDEN LIKE '" .  $this->_name . "%'");

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
