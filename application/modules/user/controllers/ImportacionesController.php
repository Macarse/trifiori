<?php
class user_ImportacionesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
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
        $this->view->headTitle("Agregar Importación");

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
                                                            $values['codBandera'],
                                                            $values['codCanal'],
                                                            $values['codGiro'],
                                                            $values['codCliente'],
                                                            $values['codCarga'],
                                                            $values['codTransporte'],
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
                        $this->view->message = $this->language->_("Inserción exitosa.");
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
        $this->view->headTitle("Listar Importaciones");

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
        $this->view->headTitle("Modificar Importación");

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
                                                                $values['codBandera'],
                                                                $values['codCanal'],
                                                                $values['codGiro'],
                                                                $values['codCliente'],
                                                                $values['codCarga'],
                                                                $values['codTransporte'],
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
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $orden = $this->_addform->createElement('text', 'orden', array('label' => 'Órden'));
        $orden  ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $transportesTable = new Transportes();
        $transportesOptions =  $transportesTable->getTransportesArray();

        $codTransporte = $this->_addform->createElement('select', 'codTransporte');
        $codTransporte  ->setRequired(true)
                        ->setOrder(1)
                        ->setLabel('Transporte')
                        ->setMultiOptions($transportesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $clientesTable = new Clientes();
        $clientesOptions =  $clientesTable->getClientesArray();

        $codCliente = $this->_addform->createElement('select', 'codCliente');
        $codCliente ->setRequired(true)
                    ->setOrder(2)
                    ->setLabel('Cliente')
                    ->setMultiOptions($clientesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codBandera = $this->_addform->createElement('select', 'codBandera');
        $codBandera ->setRequired(true)
                    ->setOrder(3)
                    ->setLabel('Bandera')
                    ->setMultiOptions($banderasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $monedasTable = new Monedas();
        $monedasOptions =  $monedasTable->getMonedasArray();

        $codMoneda = $this->_addform->createElement('select', 'codMoneda');
        $codMoneda  ->setRequired(true)
                    ->setOrder(4)
                    ->setLabel('Moneda')
                    ->setMultiOptions($monedasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $girosTable = new Giros();
        $girosOptions =  $girosTable->getGirosArray();

        $codGiro = $this->_addform->createElement('select', 'codGiro');
        $codGiro    ->setRequired(False)
                    ->setOrder(5)
                    ->setLabel('Giro')
                    ->setMultiOptions($girosOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $destinacionesTable = new Destinaciones();
        $destinacionesOptions =  $destinacionesTable->getDestinacionesArray();

        $codDestinacion = $this->_addform->createElement('select', 'codDestinacion');
        $codDestinacion ->setRequired(True)
                        ->setOrder(6)
                        ->setLabel('Destino')
                        ->setMultiOptions($destinacionesOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/

        $cargasTable = new Cargas();
        $cargasOptions =  $cargasTable->getCargasArray();

        $codCarga = $this->_addform->createElement('select', 'codCarga');
        $codCarga   ->setRequired(True)
                    ->setOrder(7)
                    ->setLabel('Carga')
                    ->setMultiOptions($cargasOptions);


        $referencia = $this->_addform->createElement('text', 'referencia',
                                                     array('label' => 'Referencia'));
        $referencia ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $fechaIngreso = $this->_addform->createElement('text', 'fechaIngreso',
                                                     array('label' => 'Fecha de Ingreso', 'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $desMercaderias = $this->_addform->createElement('text', 'desMercaderias',
                                                     array('label' => 'Descripción Mercadería'));
        $desMercaderias ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(False);


        $valorFactura = $this->_addform->createElement('text', '$valorFactura',
                                                     array('label' => 'Valor Factura'));
        $valorFactura   ->addValidator('float')
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(False);


        $vencimiento = $this->_addform->createElement('text', 'vencimiento',
                                                     array('label' => 'Vencimiento', 'id' => 'idVencimiento', 'onKeyPress' => "keyCalendar(event,'calVencimiento');"));
        $vencimiento   ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        $ingresoPuerto = $this->_addform->createElement('text', 'ingresoPuerto',
                                                     array('label' => 'Ingreso a Puerto', 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        /*TODO: ADD Validator*/
        $PERnroDoc = $this->_addform->createElement('text', 'PERnroDoc',
                                                     array('label' => 'Número de Permiso'));
        $PERnroDoc  ->addValidator('stringLength', false, array(1, 30))
                    ->setRequired(True);

        $PERpresentado = $this->_addform->createElement('text', 'PERpresentado',
                                                     array('label' => 'Permiso Presentado', 'id' => 'idPerPre', 'onKeyPress' => "keyCalendar(event,'calPerPre');"));
        $PERpresentado   ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD Validator*/
        $PERfactura = $this->_addform->createElement('text', 'PERfactura',
                                                     array('label' => 'Permiso Factura'));
        $PERfactura ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $PERfechaFactura = $this->_addform->createElement('text', 'PERfechaFactura',
                                                     array('label' => 'Permiso Fecha de Factura', 'id' => 'idPerFecFac', 'onKeyPress' => "keyCalendar(event,'calFecFac');"));
        $PERfechaFactura    ->addValidator('date')
                            ->addValidator('stringLength', false, array(1, 12))
                            ->setRequired(False);

        // Add elements to form:
        $this->_addform ->addElement($orden)
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
                        ->addElement('hidden', 'AddImportacionTrack', array('values' => 'logPost'))
                        ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

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

        $orden = $this->_modform->createElement('text', 'orden', array('label' => 'Órden'));
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
                                                     array('label' => 'Referencia'));
        $referencia ->setValue($row->referencia() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $fechaIngreso = $this->_modform->createElement('text', 'fechaIngreso',
                                                     array('label' => 'Fecha de Ingreso', 'id' => 'idFechaIngreso', 'onKeyPress' => "keyCalendar(event,'calFechaIngreso');"));
        $fechaIngreso   ->setValue($row->fechaIngreso() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $desMercaderias = $this->_modform->createElement('text', 'desMercaderias',
                                                     array('label' => 'Descripción Mercadería'));
        $desMercaderias ->setValue($row->desMercaderias() )
                        ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 200))
                        ->setRequired(False);


        $valorFactura = $this->_modform->createElement('text', '$valorFactura',
                                                     array('label' => 'Valor Factura'));
        $valorFactura   ->setValue($row->valorFactura() )
                        ->addValidator('float')
                        ->addValidator('stringLength', false, array(1, 40))
                        ->setRequired(False);


        $vencimiento = $this->_modform->createElement('text', 'vencimiento',
                                                     array('label' => 'Vencimiento', 'id' => 'idVencimiento', 'onKeyPress' => "keyCalendar(event,'calVencimiento');"));
        $vencimiento    ->setValue($row->vencimiento() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD validator*/
        $PERnroDoc = $this->_modform->createElement('text', 'PERnroDoc',
                                                     array('label' => 'Número de Permiso'));
        $PERnroDoc  ->setValue($row->PERnroDoc() )
                    ->addValidator('stringLength', false, array(1, 30))
                    ->setRequired(True);

        $ingresoPuerto = $this->_modform->createElement('text', 'ingresoPuerto',
                                                     array('label' => 'Ingreso a Puerto', 'id' => 'idIngPuerto', 'onKeyPress' => "keyCalendar(event,'calIngPuerto');"));
        $ingresoPuerto  ->setValue($row->ingresoPuerto() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(False);

        $PERpresentado = $this->_modform->createElement('text', 'PERpresentado',
                                                     array('label' => 'Permiso Presentado', 'id' => 'idPerPre', 'onKeyPress' => "keyCalendar(event,'calPerPre');"));
        $PERpresentado  ->setValue($row->PERpresentado() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);

        /*TODO: ADD Validator*/
        $PERfactura = $this->_modform->createElement('text', 'PERfactura',
                                                     array('label' => 'Permiso Factura'));
        $PERfactura ->setValue($row->PERfactura() )
                    ->addValidator('stringLength', false, array(1, 40))
                    ->setRequired(False);

        $PERfechaFactura = $this->_modform->createElement('text', 'PERfechaFactura',
                                                     array('label' => 'Permiso Fecha de Factura', 'id' => 'idPerFecFac', 'onKeyPress' => "keyCalendar(event,'calFecFac');"));
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
                        ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

}
?>
