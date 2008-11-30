<?php
class Mobile_UserController extends Trifiori_Mobile_Controller_Action
{
    protected $_form;
    protected $_searchform;

    public function indexAction()
    {
        $this->view->headTitle("Trifiori MOBILE");
    }
    
    public function listcliAction()
    {
        $this->view->headTitle($this->language->_("Listar Clientes"));
    
        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);
    
        $this->_searchform = $this->getClienteSearchForm();
        if ($this->_searchform->isValid($_GET))
        {   
            try
            {
                $clientesT = new Clientes();
            
                if (isset($_GET["consulta"]))
                {
                    $clientes = $clientesT->searchCliente($_GET["consulta"]);
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                }
                else
                {
                    $clientes = $clientesT->select();  
                    Zend_Registry::set('busqueda', "");
                }
            
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($clientes, $clientesT));
            
                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }
                $paginator->setItemCountPerPage(5);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->clienteSearchForm = $this->getClienteSearchForm();
    }
    
    private function getClienteSearchForm()
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

        $cliente = $this->_searchform->createElement('text', 'consulta', array('label' => $this->language->_('Nombre')));
        $cliente       ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 200));

        // Add elements to form:
                $this->_searchform->addElement($cliente)
                ->addElement('hidden', 'SearchClienteTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }
    
    public function listexpoAction()
    {
        $this->view->headTitle($this->language->_("Listar Exportaciones"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

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
                $paginator->setItemCountPerPage(5);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->exportacionSearchForm = $this->getExportacionSearchForm();
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
}

