<?php
class user_MainPageController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $this->view->var = 'Soy la pagina principal de usuario';
    }
}
