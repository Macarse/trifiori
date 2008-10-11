<?php
abstract class Trifiori_User_Controller_CRUD extends Zend_Controller_Action
{
    protected $_baseUrl;

    public function init()
    {
        if (!isset($this->_baseUrl))
        {
            $this->_baseUrl = $this->_helper->url->url(array());
        }
}

