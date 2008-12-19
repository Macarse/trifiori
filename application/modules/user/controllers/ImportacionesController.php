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
                        $this->view->error = $this->language->_("Error en la Base de datos.");
                    }
                }
            }
        }


        if (($this->view->importacionAddForm = $this->getImportacionAddForm()) == NULL)
        {
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
        }
    }

    public function listimportacionesAction()
    {
        $this->view->headTitle($this->language->_("Listar Importaciones"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();
        $this->view->sort = ( isset($_GET["sort"] ) ) ? $_GET["sort"] : 'asc' ;
        $this->view->sortby = ( isset($_GET["sortby"] ) ) ? $_GET["sortby"] : '' ;

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

                if (isset($_GET["sortby"]))
                    Zend_Registry::set('sortby', $_GET["sortby"]);
                else
                    Zend_Registry::set('sortby', "");

                if (isset($_GET["sort"]))
                    Zend_Registry::set('sorttype', $_GET["sort"]);
                else
                    Zend_Registry::set('sorttype', "");

                Zend_Registry::set('busqueda', $busqueda);
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($impo, $importacionesTable));

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
                $this->_flashMessenger->addMessage(
                                $this->language->_("No se pudo eliminar. ") .
                                $this->language->_("Error en la Base de datos.")
                                                );
            }
        }

        $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
    }

    public function pdfAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        if ( $this->getRequest()->getParam('id') != null )
        {
            $id = $this->getRequest()->getParam('id');

            try
            {
                $importacionesTable = new Importaciones();
                $row = $importacionesTable->getImportacionByID($id);
            }
            catch (Zend_Exception $error)
            {
                $this->_flashMessenger->addMessage(
                                $this->language->_("No se pudo eliminar.") .
                                $this->language->_("Error en la Base de datos.")
                                                );
                $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
            }

            if (count($row))
            {
                $pdf = $this->generatePDF($row);

                $pdfDocument = $pdf->render();
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename=' .
                    $row->orden() . '.pdf');
                echo $pdfDocument;

            }
            else
            {
                $this->_flashMessenger->addMessage($this->language->_('ID inexistente'));
                $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
            }


        }
        else
        {
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
        }
    }

    private function generatePDF($row)
    {
        $x = 70;
        $y = 700;
        $dif = 20;

        $pdf = new Zend_Pdf();

        $pdf->properties['Title'] = $this->language->_("Importaciones");
        $pdf->properties['Author'] = 'Trifiori';
        $pdf->properties['Keywords'] = 'Trifiori';
        $pdf->properties['Creator'] = 'Trifiori';
        $pdf->properties['Producer'] = 'Trifiori';

        // Reverse page order
        $pdf->pages = array_reverse($pdf->pages);
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $pdf->pages[] = ($page1 = $pdf->newPage('A4'));

//         Usar esto cuando Zend_PDF funcione con img sin dependencias.
//         $image = Zend_Pdf_Image::imageWithPath('PATH');
//         $page1->drawImage($image, 100, 100, 400, 300);


        // Apply font and draw text
        $page1->setFont($font, 24);

        $page1->drawText($this->language->_("Importaciones"), 250, 800, 'UTF-8');

        // Apply font and draw text
        $page1->setFont($font, 14);

        $page1->drawText($this->language->_("Órden: ") .
                $row->orden(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Transporte: ") .
                $row->codTransporteName(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Cliente: ") .
                $row->codClienteName(), $x, $y, 'UTF-8');


        $y -= $dif;

        $page1->drawText($this->language->_("Bandera: ") .
                $row->codBanderaName(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Moneda: ") .
                $row->codMonedaName(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Giro: ") .
                $row->codGiroName(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Destinación: ") .
                $row->codDestinacionName(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Carga: ") .
                $row->codCargaName(), $x, $y, 'UTF-8');

        $y -= $dif;


        $page1->drawText($this->language->_("Fecha de Ingreso: ") .
                $row->fechaIngreso(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Descripción de la mercadería: ") .
                $row->desMercaderias(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Valor de la factura: ") .
                $row->valorFactura(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Fecha de vencimiento: ") .
                $row->DESvencimiento(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Fecha de ingreso al puerto: ") .
                $row->ingresoPuerto(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("OPP: ") .
                $row->codOppNum(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Fecha en que fue presentado: ") .
                $row->DESpresentado(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Número de factura: ") .
                $row->DESfactura(), $x, $y, 'UTF-8');

        $y -= $dif;

        $page1->drawText($this->language->_("Fecha de la factura: ") .
                $row->DEsfechaFactura(), $x, $y, 'UTF-8');

        $y -= $dif;

        return $pdf;
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
        else
        {
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
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
                        $this->_flashMessenger->addMessage(
                                $this->language->_("No se puedo modificar.") .
                                $this->language->_("Error en la Base de datos.")
                                                );
                        $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
                    }


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
                    array('label' => '*' . $this->language->_('Órden'), 'id' => 'idnameOrden'));
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

        try
        {
            $canalesTable = new Canales();
            $canalesOptions =  $canalesTable->getCanalesArray();
        }
        catch (Zend_Exception $e)
        {
            return NULL;
        }

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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calFechaIngreso')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calIngPuerto')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $DESnroDoc = $this->_addform->createElement('text', 'DESnroDoc',
                    array('label' => '*' . $this->language->_('Despacho: Número de Documento')));
        $DESnroDoc  ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(True);


        $DESvencimiento = $this->_addform->createElement('text', 'DESvencimiento',
                array('label' => $this->language->_('Despacho: Vencimiento'),
                 'id' => 'idDESVencimiento', 'onKeyPress' => "keyCalendar(event,'calDesVencimiento');"));
        $DESvencimiento ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDesVencimiento')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDesPresentado')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DESsalido = $this->_addform->createElement('text', 'DESsalido',
                array('label' => '*' . $this->language->_('Despacho: Salido'),
                 'id' => 'idDESSalido', 'onKeyPress' => "keyCalendar(event,'calDESsalido');"));
        $DESsalido ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDESsalido')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DEScargado = $this->_addform->createElement('text', 'DEScargado',
                array('label' => '*' . $this->language->_('Despacho: Cargado'),
                 'id' => 'idDESCargado', 'onKeyPress' => "keyCalendar(event,'calDEScargado');"));
        $DEScargado ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDEScargado')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDEsfechaFactura')\"></div>")
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
        $this->_addform ->addElement('hidden', 'AddImportacionTrack', array('values' => 'logPost'))
                        ->addElement($orden)
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
                        ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));


        return $this->_addform;
    }

    private function generateEstadisticas( $type , $from, $to)
    {
        try
        {
            $model = new Importaciones();
            $data = $model->getEstadisticas($type , $from, $to);
        }
        catch(Zend_Exception $e)
        {
            $this->_flashMessenger->addMessage(
                                $this->language->_("No se pudieron generar las estadísticas." .
                                    "Error en la Base de datos.")
                                                );
            $this->_helper->redirector->gotoUrl('user/importaciones/listimportaciones');
        }

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

        $fh = fopen("xml/impo_" . $type . ".xml", 'w') or die("can't open file");
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calFechaDesde')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12));

        $fechaHasta = $this->_estform->createElement('text', 'fechaHasta',
                    array('label' => '*' . $this->language->_('Fecha hasta'),
                    'id' => 'idFechaHasta', 'onKeyPress' => "keyCalendar(event,'calFechaHasta');"));
        $fechaHasta     ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calFechaHasta')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12));

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


    public function detailsAction()
    {
        $id = $_GET["id"];
        $results = null;
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        try
        {
            $table = new Importaciones();
            $where = "CODIGO_IMP = " . $id;
            $results = $table->fetchAll($where);


            echo "<div class=\"hd\">" . $this->language->_("Detalles de Importación") . "</div>";
    
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
                    echo "<b>" . $this->language->_("Fecha de vencimiento: ") . "</b>" . $result->DESvencimiento() .  "<br />";
                    echo "<b>" . $this->language->_("Fecha de ingreso al puerto: ") . "</b>" . $result->ingresoPuerto() .  "<br />";
                    echo "<b>" . $this->language->_("OPP: ") . "</b>" . $result->codOppNum() .  "<br />";
                    echo "<b>" . $this->language->_("Fecha en que fue presentado: ") . "</b>" . $result->DESpresentado() .  "<br />";
                    echo "<b>" . $this->language->_("Número de factura: ") . "</b>" . $result->DESfactura() .  "<br />";
                    echo "<b>" . $this->language->_("Fecha de la factura: ") . "</b>" . $result->DEsfechaFactura() .  "<br />";
                }
            }
            echo "</div>";
            echo "<div class=\"ft\">" . $this->language->_("Trifiori 2008") . "</div>";

        }
        catch (Zend_Exception $error)
        {
            echo "<div class=\"hd\">" . $this->language->_("Detalles de Exportación") . "</div>";
            echo "<div class=\"bd\"> " . $this->language->_("Error en la Base de datos.") . " </div>";
            $this->_flashMessenger->addMessage(
                                $this->language->_("No se puedo generar detalles." .
                                    "Error en la Base de datos.")
                                                );
        }
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
            array('label' => '*' . $this->language->_('Órden'), 'id' => 'idnameOrden'));
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

        try
        {
            $canalesTable = new Canales();
            $canalesOptions =  $canalesTable->getCanalesArray();
        }
        catch (Zend_Exception $e)
        {
            return NULL;
        }


        $codCanal = $this->_modform->createElement('select', 'codCanal');
        $codCanal   ->setRequired(true)
                    ->setValue($row->codCanal() )
                    ->setLabel('*' . $this->language->_('Canal'))
                    ->setMultiOptions($canalesOptions);

        $codGiro = $this->_modform->createElement('text', 'nameGiro',
                array('label' => $this->language->_('Giro'), 'id' => 'idnameGiro'));
        $codGiro    ->setRequired(False)
                    ->setValue($row->codGiroName() )
                    ->addValidator(new CV_Validate_Giro());

        $codCliente = $this->_modform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true)
                    ->setValue($row->codClienteName() )
                    ->addValidator(new CV_Validate_Cliente());

        $codCarga = $this->_modform->createElement('text', 'nameCarga',
                array('label' =>'*' .  $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga   ->setRequired(true)
                    ->setValue($row->codCargaName() )
                    ->addValidator(new CV_Validate_Carga());

        $codTransporte = $this->_modform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  ->setRequired(true)
                        ->setValue($row->codTransporteName() )
                        ->addValidator(new CV_Validate_Transporte());

        $codMoneda = $this->_modform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda  ->setRequired(true)
                    ->setValue($row->codMonedaName() )
                    ->addValidator(new CV_Validate_Moneda());

        $codOpp = $this->_modform->createElement('text', 'nameOpp',
                array('label' => $this->language->_('Opp'), 'id' => 'idnameOpp'));
        $codOpp ->setRequired(False)
                ->setValue($row->codOppNum() )
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calFechaIngreso')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calIngPuerto')\"></div>")
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
                 'id' => 'idDESVencimiento', 'onKeyPress' => "keyCalendar(event,'calDesVencimiento');"));
        $DESvencimiento ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDesVencimiento')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDesPresentado')\"></div>")
                        ->setValue($row->DESpresentado() )
                       ->addValidator('stringLength', false, array(1, 12))
                       ->setRequired(True);

        $DESsalido = $this->_modform->createElement('text', 'DESsalido',
                array('label' => '*' . $this->language->_('Despacho: Salido'),
                 'id' => 'idDESSalido', 'onKeyPress' => "keyCalendar(event,'calDESsalido');"));
        $DESsalido ->addValidator(new CV_Validate_Fecha())
                    ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                    ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDESsalido')\"></div>")
                    ->setValue($row->DESsalido() )
                   ->addValidator('stringLength', false, array(1, 12))
                   ->setRequired(True);

        $DEScargado = $this->_modform->createElement('text', 'DEScargado',
                array('label' => '*' . $this->language->_('Despacho: Cargado'),
                 'id' => 'idDESCargado', 'onKeyPress' => "keyCalendar(event,'calDEScargado');"));
        $DEScargado ->addValidator(new CV_Validate_Fecha())
                    ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                    ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDEScargado')\"></div>")
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
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calDEsfechaFactura')\"></div>")
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
        $this->_modform ->addElement('hidden', 'ModImportacionTrack', array('values' => 'logPost'))
                        ->addElement($orden)
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
                ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

}
?>
