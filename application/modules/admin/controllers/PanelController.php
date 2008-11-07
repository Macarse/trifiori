<?php
class admin_PanelController extends Trifiori_Admin_Controller_Action
{
    protected $_form;

    public function init()
    {
        parent::init();
    }

    public function indexAction()
    {
        $this->view->headTitle($this->language->_("Panel del Administrador"));
    }

}
