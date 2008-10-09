<?php
abstract class Trifiori_Admin_Controller_Action extends Zend_Controller_Action
{
    protected $_baseUrl;

    public function init()
    {
        $this->_helper->layout->setLayout('admin');

        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }
}

