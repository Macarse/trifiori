<?php
abstract class Trifiori_Default_Controller_Action extends Zend_Controller_Action
{
    protected $_baseUrl;

    public function init()
    {
        //TODO: Layout principal no setteado.
        //$this->_helper->layout->setLayout('common');

        if (!isset($this->_baseUrl))
        {
            //TODO el redirect no funciona bien.
            $this->_baseUrl = $this->_helper->url->url(array());
        }
    }
}
