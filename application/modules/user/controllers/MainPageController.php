<?php
class user_MainPageController extends Trifiori_User_Controller_Action
{
    protected $_acl;
    protected $_username;
    
    public function init()
    {
        parent::init();
        $_acl = Zend_Registry::getInstance()->accesslist;
        $_username = Zend_Registry::getInstance()->name;

        if (! $_acl->isAllowed($_username, 'user'))
        {
            $this->_helper->redirector->gotoUrl('default/index');
        }
    }
    
    public function indexAction()
    {
        $this->view->var = 'Soy la pagina principal de usuario';
    }
}
