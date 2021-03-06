<?php
class user_OppsController extends Trifiori_User_Controller_Action
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
        $this->_helper->redirector->gotoUrl('user/opps/listopps');
    }

    public function addoppsAction()
    {
        $this->view->headTitle($this->language->_("Agregar Opp"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddOppTrack']))
            {
                $this->_addform = $this->getOppAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $oppsTable = new Opps();
                        $oppsTable->addOpp( $values['name'],
                                            $values['declaracionOk'],
                                            $values['pedidoDinero'],
                                            $values['otrosOpp'],
                                            $values['fraccionado'],
                                            $values['estampillas'],
                                            $values['impuestosInternos']
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

        $this->view->oppAddForm = $this->getOppAddForm();
    }

    public function listoppsAction()
    {
        $this->view->headTitle($this->language->_("Listar Opps"));

        $this->view->paginator = null;

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();
        $this->view->sort = ( isset($_GET["sort"] ) ) ? $_GET["sort"] : 'asc' ;
        $this->view->sortby = ( isset($_GET["sortby"] ) ) ? $_GET["sortby"] : '' ;

        $this->_searchform = $this->getOPPSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $table = new Opps();

                if (isset($_GET["consulta"]))
                {
                    if (isset($_GET["sortby"]))
                    {
                        if (isset($_GET["sort"]))
                        {
                            $opps = $table->searchOpp($_GET["consulta"], $_GET["sortby"], $_GET["sort"]);
                            $mySortType = $_GET["sort"];
                        }
                        else
                        {
                            $opps = $table->searchOpp($_GET["consulta"], $_GET["sortby"], null);
                            $mySortType = null;
                        }
                        $mySortBy = $_GET["sortby"];
                    }
                    else
                    {
                        $opps = $table->searchOpp($_GET["consulta"], null, null);
                        $mySortType = null;
                        $mySortBy = null;
                    }
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                    Zend_Registry::set('sortby', $mySortBy);
                    Zend_Registry::set('sorttype', $mySortType);
                }
                else
                {
                    $opps = $table->searchOpp("", "", "");

                    Zend_Registry::set('sortby', "");
                    Zend_Registry::set('sorttype', "");
                    Zend_Registry::set('busqueda', "");
                }

                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($opps, $table));

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
        $this->view->oppSearchForm = $this->getOPPSearchForm();
    }

    public function removeoppsAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/opps/listopps');
        }
        else
        {
            try
            {
                $oppsTable = new Opps();
                $oppsTable->removeOpp( $this->getRequest()->getParam('id') );
                $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
                $this->_flashMessenger->addMessage(
                    $this->language->_("No se pudo eliminar. " .
                                        "Error en la Base de datos.")
                                                );
            }
        }

        $this->_helper->redirector->gotoUrl('user/opps/listopps');
    }

    public function modoppsAction()
    {
        $this->view->headTitle($this->language->_("Modificar Opp"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->oppModForm = $this->getOppModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/opps/listopps');
            }
        }
        else
        {
            $this->_helper->redirector->gotoUrl('user/opps/listopps');
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModOppTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $oppsTable = new Opps();
                        $oppsTable->modifyOpp(  $this->_id,
                                                $values['name'],
                                                $values['declaracionOk'],
                                                $values['pedidoDinero'],
                                                $values['otrosOpp'],
                                                $values['fraccionado'],
                                                $values['estampillas'],
                                                $values['impuestosInternos']
                                                );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->_flashMessenger->addMessage(
                            $this->language->_("No se pudo modificar. " .
                                        "Error en la Base de datos.")
                                                );
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/opps/listopps');
                }
            }
        }
    }

    private function getOppModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Levanto el usuario para completar el form.*/

        try
        {
            $oppsTable = new Opps();
            $row = $oppsTable->getOppByID( $id );
        }
        catch (Zend_Exception $e)
        {
            return NULL;
        }

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/opps/listopps');
        }

        $this->_modform = new Zend_Form();
        $this->_modform ->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $name = $this->_modform->createElement('text', 'name',
            array('label' => '*' . $this->language->_('Número')));
        $name   ->setValue($row->name() )
                ->addValidator('int')
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $siNo = array( 's' => $this->language->_('Sí'), 'n' => $this->language->_('No'));

        $declaracionOk = $this->_modform->createElement('select', 'declaracionOk');
        $declaracionOk  ->setValue($row->declaracionOkchar() )
                        ->setOrder(1)
                        ->setLabel('*' . $this->language->_('Declaración Ok'))
                        ->setRequired(true)
                        ->setMultiOptions($siNo);


        $pedidoDinero = $this->_modform->createElement('text', 'pedidoDinero',
                        array('label' => '*' . $this->language->_('Fecha de Pedido de Dinero'),
                        'id' => 'idpedidoDinero', 'onKeyPress' => "keyCalendar(event,'calpedidoDinero');"
                        ));
        $pedidoDinero   ->setValue($row->pedidoDinero() )
                        ->addValidator(new CV_Validate_Fecha())
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $otrosOpp = $this->_modform->createElement('text', 'otrosOpp',
                                                array('label' => $this->language->_('Otros Opp')));
        $otrosOpp   ->setValue($row->otrosOpp() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        $fraccionado = $this->_modform->createElement('text', 'fraccionado',
                                array('label' => $this->language->_('Fraccionado Opp')));
        $fraccionado ->setValue($row->fraccionado() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False);


        $estampillas = $this->_modform->createElement('text', 'estampillas',
                                array('label' => $this->language->_('Estampillas')));
        $estampillas ->setValue($row->estampillas() )
                   ->addValidator($alnumWithWS)
                   ->addValidator('stringLength', false, array(1, 150))
                   ->setRequired(False);

        $impuestosInternos = $this->_modform->createElement('text', 'impuestosInternos',
                                array('label' => $this->language->_('Impuestos Internos')));
        $impuestosInternos  ->setValue($row->estampillas() )
                            ->addValidator($alnumWithWS)
                            ->addValidator('stringLength', false, array(1, 150))
                            ->setRequired(False);

        // Add elements to form:
        $this->_modform ->addElement($name)
                        ->addElement($declaracionOk)
                        ->addElement($pedidoDinero)
                        ->addElement($otrosOpp)
                        ->addElement($fraccionado)
                        ->addElement($estampillas)
                        ->addElement($impuestosInternos)
                        ->addElement('hidden', 'ModOppTrack', array('values' => 'logPost'))
                        ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getOppAddForm()
    {

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        $this->_addform = new Zend_Form();
        $this->_addform ->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . $this->language->_('Número')));
        $name  ->addValidator('int')
                ->addValidator(new CV_Validate_OPPExiste())
                ->addValidator('stringLength', false, array(1, 11))
                ->setRequired(true);

        $siNo = array( 's' => $this->language->_('Sí'), 'n' => $this->language->_('No'));

        $declaracionOk = $this->_addform->createElement('select', 'declaracionOk');
        $declaracionOk  ->setOrder(1)
                        ->setLabel('*' . $this->language->_('Declaración Ok'))
                        ->setRequired(true)
                        ->setMultiOptions($siNo);


        $pedidoDinero = $this->_addform->createElement('text', 'pedidoDinero',
                            array('label' => '*' . $this->language->_('Fecha de Pedido de Dinero'),
                            'id' => 'idpedidoDinero', 'onKeyPress' => "keyCalendar(event,'calpedidoDinero');"
                            ));
        $pedidoDinero   ->addValidator(new CV_Validate_Fecha())
                        ->addDecorator('Description', array('escape' => false,   'placement'=> 'prepend', 'tag' => '') )
                        ->setDescription("<div class='imgCalendar'><img src='/images/calendar.gif' onClick=\"show_hide_div('calpedidoDinero')\"></div>")
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $otrosOpp = $this->_addform->createElement('text', 'otrosOpp',
                            array('label' => $this->language->_('Otros Opp')));
        $otrosOpp   ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        $fraccionado = $this->_addform->createElement('text', 'fraccionado',
                            array('label' => $this->language->_('Fraccionado Opp')));

        $fraccionado    ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 150))
                        ->setRequired(False);


        $estampillas = $this->_addform->createElement('text', 'estampillas',
                            array('label' => $this->language->_('Estampillas')));

        $estampillas    ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 150))
                        ->setRequired(False);


        $impuestosInternos = $this->_addform->createElement('text', 'impuestosInternos',
                            array('label' => $this->language->_('Impuestos Internos')));

        $impuestosInternos  ->addValidator($alnumWithWS)
                            ->addValidator('stringLength', false, array(1, 150))
                            ->setRequired(False);

        // Add elements to form:
        $this->_addform ->addElement($name)
                        ->addElement($declaracionOk)
                        ->addElement($pedidoDinero)
                        ->addElement($otrosOpp)
                        ->addElement($fraccionado)
                        ->addElement($estampillas)
                        ->addElement($impuestosInternos)
                        ->addElement('hidden', 'AddOppTrack', array('values' => 'logPost'))
                        ->addElement('submit', 'Modificar', array('label' => $this->language->_('Agregar')));


        return $this->_addform;
    }

    private function getOPPSearchForm()
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

        $opps = $this->_searchform->createElement('text', 'consulta',
                array('label' => $this->language->_('Número')));
        $opps   ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 150));

        // Add elements to form:
        $this->_searchform->addElement($opps)
                ->addElement('hidden', 'SearchOPPTrack', array('values' => 'logPost'))
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

            try
            {
                $model = new Opps();
                $data = $model->fetchAll("NUMERO_OPP LIKE '" .  $this->_name . "%' AND DELETED LIKE '0'");

                foreach ($data as $row)
                {
                    array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
                }

                $arr = array("Resultset" => array("Result" => $aux));
            }
            catch (Zend_Exception $e)
            {
                $arr = array();
            }

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
