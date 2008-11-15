<?php
class admin_LogController extends Trifiori_Admin_Controller_Action
{
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('admin/log/listlogs');
    }

    public function listlogsAction()
    {
        $this->view->headTitle($this->language->_("Últimas Modificaciones"));

        unset($this->view->error);

        try
        {
            $where = "MSG like '%ALTERANDO%'";
            $table = new Log();
            $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($table->select()->where($where), $table));
            
            Zend_Registry::set('busqueda', "");
            
            if (isset($_GET["page"]))
            {
                $paginator->setCurrentPageNumber($_GET["page"]);
            }
            else
            {
                $paginator->setCurrentPageNumber(1);
            }
            $paginator->setItemCountPerPage(10);
            $this->view->paginator = $paginator;
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }
}
