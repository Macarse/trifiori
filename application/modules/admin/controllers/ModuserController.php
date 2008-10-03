<?php
class admin_ModuserController extends Zend_Controller_Action
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
        $this->view->headTitle("Modificar Usuario");
        $this->view->baseUrl = $this->_baseUrl;
    }

    public function finduserAction()
    {
        /*TODO: Acá se crea el buscador o se usa un método de Users*/
        $this->view->buscador = "Esto es el buscador";
    }


    public function listuserAction()
    {
        $table = new Users();
        $this->view->users = $table->fetchAll();
    }

    public function removeuserAction()
    {
        if ( $this->getRequest()->getParam('id') === null )
        {
            /*TODO: No sé si está bien hardcodearlo así. Preguntar.*/
            $this->_helper->redirector->gotoUrl('admin/moduser');
        }
        else
        {
            $UserTable = new Users();
            $UserTable->removeUser( $this->getRequest()->getParam('id') );
        }

        $this->_helper->redirector->gotoUrl('admin/moduser');
    }

}
