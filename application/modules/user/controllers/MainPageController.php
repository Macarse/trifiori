<?php
class user_MainPageController extends Trifiori_User_Controller_Action
{
    public function init()
    {
        parent::init();
    }
    
    public function indexAction()
    {
        $this->view->var = 'Soy la pagina principal de usuario';
    }
}
