<?php
class admin_PanelController extends Zend_Controller_Action
{

    protected $_form;

    public function init()
    {
        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }

    public function indexAction()
    {
        $this->view->headTitle("Panel del Administrador");
    }

}
