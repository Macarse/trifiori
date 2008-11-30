<?php
abstract class Trifiori_Mobile_Controller_Action extends Zend_Controller_Action
{
    protected $_baseUrl;

    public function init()
    {
//        $this->_helper->layout->setLayout('common');

        $this->language = Zend_Registry::getInstance()->language;
        $this->view->language = Zend_Registry::getInstance()->language;

        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }

    }
}

