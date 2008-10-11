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
        $this->view->headTitle("Últimas modificaciones");

        unset($this->view->error);

        try
        {
            $where = "MSG like '%ALTERANDO%'";
            $table = new Log();
            $this->view->Log = $table->fetchAll($where);
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }
}
