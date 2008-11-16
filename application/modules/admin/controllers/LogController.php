<?php
class admin_LogController extends Trifiori_Admin_Controller_Action
{
    protected $_id;
    protected $_searchform;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('admin/log/listlogs');
    }

    public function listlogsAction()
    {
        $this->view->headTitle($this->language->_("Últimas Modificaciones"));

        unset($this->view->error);
        
        $this->_searchform = $this->getLogSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $where = "MSG like '%ALTERANDO%'";
                $table = new Log();
                
                if (isset($_GET["consulta"]))
                {
                    $log = $table->searchLog($_GET["consulta"]);
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                }
                else
                {
                    $log = $table->select()->where($where)->order("CODIGOLOG DESC");
                    Zend_Registry::set('busqueda', "");
                }
                
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($log, $table));
                
                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }
                $paginator->setItemCountPerPage(20);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->logSearchForm = $this->getLogSearchForm();
    }
    
    private function getLogSearchForm()
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

        $logs = $this->_searchform->createElement('text', 'consulta',
                array('label' => $this->language->_('Mensaje')));
        $logs   ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
                $this->_searchform->addElement($logs)
                ->addElement('hidden', 'SearchLogTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }
}
