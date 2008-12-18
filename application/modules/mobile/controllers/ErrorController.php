<?php

class Mobile_ErrorController extends Trifiori_Mobile_Controller_Action
{
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
          
        switch ($errors->type)
        {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setRawHeader('HTTP/1.1 404 Not Found');

                $content = $this->language->_("La página solicitada no existe");
                break;
            default:
                // application error
                $content = $this->language->_("Un error inesperado ha ocurrido: "). $errors->exception->getMessage(); 
                break;
        }

        // Clear previous content
        $this->getResponse()->clearBody();

        $this->view->content = $content;
    }
}
