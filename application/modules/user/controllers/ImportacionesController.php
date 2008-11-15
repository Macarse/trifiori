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
                                                            $values['codDestinacion'],
                                                            $values['nameBandera'],
                                                            $values['codCanal'],
                                                            $values['codGiro'],
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

        try
        {
            $table = new Importaciones();;
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
            if (($this->view->exportacionModForm = $this->getImportacionModForm($this->_id)) == null)
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
                                                                $values['codDestinacion'],
                                                                $values['nameBandera'],
                                                                $values['codCanal'],
                                                                $values['codGiro'],
                                                                $values['nameCliente'],
                                                                $values['nameCarga'],
                                                                $values['nameTransporte'],
                                                                $values['codMoneda'],
                                                                $values['codOpp'],
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
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $codDestinacion = $this->_addform->createElement('text', 'codDestinacion',
                array('label' => '*' . $this->language->_('Destinación'), 'id' => 'idnameDestinacion'));
        $codDestinacion ->setRequired(true);

        $codBandera = $this->_addform->createElement('text', 'nameBandera',
                array('label' =>'*' .  $this->language->_('Bandera'), 'id' => 'idnameBandera'));
        $codBandera ->setRequired(true);

        $canalesTable = new Canales();
        $canalesOptions =  $canalesTable->getCanalesArray();

        $codCanal = $this->_addform->createElement('select', 'codCanal');
        $codCanal   ->setRequired(true)
                    ->setOrder(4)
                    ->setLabel('*' . $this->language->_('Canal'))
                    ->setMultiOptions($canalesOptions);

        $codGiro = $this->_addform->createElement('text', 'codGiro',
                array('label' => $this->language->_('Giro'), 'id' => 'idnameGiro'));
        $codGiro ->setRequired(False);

        $codCliente = $this->_addform->createElement('text', 'nameCliente',
                array('label' => '*' . $this->language->_('Cliente'), 'id' => 'idnameCliente'));
        $codCliente ->setRequired(true);

        $codCarga = $this->_addform->createElement('text', 'nameCarga',
                array('label' =>'*' .  $this->language->_('Carga'), 'id' => 'idnameCarga'));
        $codCarga ->setRequired(true);

        $codTransporte = $this->_addform->createElement('text', 'nameTransporte',
                array('label' => '*' . $this->language->_('Transporte'), 'id' => 'idnameTransporte'));
        $codTransporte  ->setRequired(true);

        $codMoneda = $this->_addform->createElement('text', 'nameMoneda',
                array('label' => '*' . $this->language->_('Moneda'), 'id' => 'idnameMoneda'));
        $codMoneda ->setRequired(true);

        $codOpp = $this->_addform->createElement('text', 'nameOpp',
                array('label' => $this->language->_('Opp'), 'id' => 'idnameOpp'));
        $codOpp ->setRequired(False);

        $referencia = $this->_addform->createElement('text', 'referencia',
                array('label' => '*' . $this->language->_('Referencia')));
        $referencia ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 150))
                    ->setRequired(True);

        $fechaIngreso = $this->_addform->createElement('text', 'fechaIngreso',
                array('label' =>'*' .  $this->language->_('Fecha de Ingreso'),
                'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->addValidator('date')
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
        $ingresoPuerto  ->addValidator('date')
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
        $DESvencimiento ->addValidator('date')
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
        $DESpresentado ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DESsalido = $this->_addform->createElement('text', 'DESsalido',
                array('label' => '*' . $this->language->_('Despacho: Salido'),
                 'id' => 'idDESSalido', 'onKeyPress' => "keyCalendar(event,'calDESsalido');"));
        $DESsalido ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $DEScargado = $this->_addform->createElement('text', 'DEScargado',
                array('label' => '*' . $this->language->_('Despacho: Cargado'),
                 'id' => 'idDESCargado', 'onKeyPress' => "keyCalendar(event,'calDEScargado');"));
        $DEScargado ->addValidator('date')
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
        $DEsfechaFactura ->addValidator('date')
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
                        ->addElement($codCanal)
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
//                         ->addElement('hidden', 'codDestinacion', array('id' => 'idcodDestinacion'))
//                         ->addElement('hidden', 'codBandera', array('id' => 'idcodBandera'))
//                         ->addElement('hidden', 'codGiro', array('id' => 'idcodGiro'))
//                         ->addElement('hidden', 'codCliente', array('id' => 'idcodCliente'))
//                         ->addElement('hidden', 'codCarga', array('id' => 'idcodCarga'))
//                         ->addElement('hidden', 'codTransporte', array('id' => 'idcodTransporte'))
//                         ->addElement('hidden', 'codMoneda', array('id' => 'idcodMoneda'))
//                         ->addElement('hidden', 'codOpp', array('id' => 'idcodOpp'))
                        ->addElement('submit', 'Ingresar', array('label' => 'Agregar'));


        return $this->_addform;
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
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $orden = $this->_modform->createElement('text', 'orden', array('label' => $this->language->_('Órden')));
        $orden  ->setValue($row->orden() )
                ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $transportesTable = new Transportes();
        $transportesOptions =  $transportesTable->getTransportesArray();

        $codTransporte = $this->_modform->createElement('select', 'codTransporte');
        $codTransporte  ->setValue($row->codTransporte() )
                        ->setRequired(true)
                        ->setOrder(1)
                        ->setLabel('Transporte')
                        ->setMultiOptions($transportesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $clientesTable = new Clientes();
        $clientesOptions =  $clientesTable->getClientesArray();

        $codCliente = $this->_modform->createElement('select', 'codCliente');
        $codCliente ->setValue($row->codCliente() )
                    ->setRequired(true)
                    ->setOrder(2)
                    ->setLabel('Cliente')
                    ->setMultiOptions($clientesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codBandera = $this->_modform->createElement('select', 'codBandera');
        $codBandera ->setValue($row->codBandera() )
                    ->setRequired(true)
                    ->setOrder(3)
                    ->setLabel('Bandera')
                    ->setMultiOptions($banderasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $monedasTable = new Monedas();
        $monedasOptions =  $monedasTable->getMonedasArray();

        $codMoneda = $this->_modform->createElement('select', 'codMoneda');
        $codMoneda  ->setValue($row->codMoneda() )
                    ->setRequired(true)
                    ->setOrder(4)
                    ->setLabel('Moneda')
                    ->setMultiOptions($monedasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $girosTable = new Giros();
        $girosOptions =  $girosTable->getGirosArray();

        $codGiro = $this->_modform->createElement('select', 'codGiro');
        $codGiro    ->setValue($row->codGiro() )
                    ->setRequired(False)
                    ->setOrder(5)
                    ->setLabel('Giro')
                    ->setMultiOptions($girosOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $destinacionesTable = new Destinaciones();
        $destinacionesOptions =  $destinacionesTable->getDestinacionesArray();

        $codDestinacion = $this->_modform->createElement('select', 'codDestinacion');
        $codDestinacion ->setValue($row->codDestinacion() )
                        ->setRequired(True)
                        ->setOrder(6)
                        ->setLabel('Destino')
                        ->setMultiOptions($destinacionesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $cargasTable = new Cargas();
        $cargasOptions =  $cargasTable->getCargasArray();

        $codCarga = $this->_modform->createElement('select', 'codCarga');
        $codCarga   ->setValue($row->codCarga() )
                    ->setRequired(True)
                    ->setOrder(7)
                    ->setLabel('Carga')
                    ->setMultiOptions($cargasOptions);


        $referencia = $this->_modform->createElement('text', 'referencia',
                array('label' => $this->language->_('Referencia')));
        $referencia ->setValue($row->referencia() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $fechaIngreso = $this->_modform->createElement('text', 'fechaIngreso',
                array('label' => $this->language->_('Fecha de Ingreso'), 'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->setValue($row->fechaIngreso() )
                        ->addValidator('date')
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
                array('label' => $this->language->_('Vencimiento'), 'id' => 'idVencimiento', 'onKeyPress' => "keyCalendar(event,'calVencimiento');"));
        $vencimiento    ->setValue($row->vencimiento() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD validator*/
        $PERnroDoc = $this->_modform->createElement('text', 'PERnroDoc',
                array('label' => $this->language->_('Número de Permiso')));
        $PERnroDoc  ->setValue($row->PERnroDoc() )
                    ->addValidator('stringLength', false, array(1, 30))
                    ->setRequired(True);

        $ingresoPuerto = $this->_modform->createElement('text', 'ingresoPuerto',
                array('label' => $this->language->_('Ingreso a Puerto'), 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->setValue($row->ingresoPuerto() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $PERpresentado = $this->_modform->createElement('text', 'PERpresentado',
                array('label' => $this->language->_('Permiso Presentado'), 'id' => 'idPerPre', 'onKeyPress' => "keyCalendar(event,'calPerPre');"));
        $PERpresentado  ->setValue($row->PERpresentado() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD Validator*/
        $PERfactura = $this->_modform->createElement('text', 'PERfactura',
                array('label' => $this->language->_('Permiso Factura')));
        $PERfactura ->setValue($row->PERfactura() )
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $PERfechaFactura = $this->_modform->createElement('text', 'PERfechaFactura',
                array('label' => $this->language->_('Permiso Fecha de Factura'), 'id' => 'idPerFecFac', 'onKeyPress' => "keyCalendar(event,'calFecFac');"));
        $PERfechaFactura    ->setValue($row->PERfechaFactura() )
                            ->addValidator('date')
                            ->addValidator('stringLength', false, array(1, 12))
                            ->setRequired(False);

        // Add elements to form:
        $this->_modform ->addElement($orden)
                        ->addElement($codTransporte)
                        ->addElement($codCliente)
                        ->addElement($codBandera)
                        ->addElement($codMoneda)
                        ->addElement($codGiro)
                        ->addElement($codDestinacion)
                        ->addElement($codCarga)
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
                        ->addElement('hidden', 'ModImportacionTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

}
?>
