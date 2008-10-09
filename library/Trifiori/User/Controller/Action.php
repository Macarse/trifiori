<?php
abstract class Trifiori_User_Controller_Action extends Zend_Controller_Action
{
    protected $_baseUrl;

    public function init()
    {
        $this->_helper->layout->setLayout('user');

        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }
    public function logoutAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_helper->redirector->gotoUrl('default');
    }
}

