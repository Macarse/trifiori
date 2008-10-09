<?php
abstract class Trifiori_User_Controller_CRUD extends Zend_Controller_Action
{
    protected $_baseUrl;
    protected $_acl;
    protected $_username;

    public function init()
    {

        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
        
        $_acl = Zend_Registry::getInstance()->accesslist;
        $_username = Zend_Registry::getInstance()->name;

        if (! $_acl->isAllowed($_username, 'user'))
        {
            $this->_helper->redirector->gotoUrl('default/index');
        }
}

