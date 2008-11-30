<?php

class Trifiori_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract
{
    public function __construct()
    {
        $acl = new Zend_Acl();

        $acl->addRole(new Zend_Acl_Role('guest'));
        $acl->addRole(new Zend_Acl_Role('user'));
        $acl->addRole(new Zend_Acl_Role('admin'));

        $acl->add(new Zend_Acl_Resource('default'));
        $acl->add(new Zend_Acl_Resource('user'));
        $acl->add(new Zend_Acl_Resource('admin'));

        $acl->add(new Zend_Acl_Resource('mobile'));

        /* Guest */
        $acl->allow('guest', 'default');
        $acl->allow('guest', 'mobile');
        $acl->allow('guest', 'mobile', array('index'));
        $acl->deny('guest', 'mobile', array('admin', 'user'));
        $acl->deny('guest', 'user');
        $acl->deny('guest', 'admin');

        /* Usuario */
        $acl->allow('user', 'default');
        $acl->allow('user', 'user');
        $acl->allow('user', 'mobile');
        $acl->allow('user', 'mobile', array('index', 'user'));
        $acl->deny('user', 'mobile', array('admin'));
        $acl->deny('user', 'admin');

        /* Administrador */
        $acl->allow('admin', 'default');
        $acl->allow('admin', 'user');
        $acl->allow('admin', 'admin');
        $acl->allow('admin', 'mobile');

        Zend_Registry::set('acl', $acl);
    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $acl = Zend_Registry::get('acl');
        $username = Zend_Registry::get('name');
        
        if ( !$acl->isAllowed($username, $module, $controller) )
        {
            if ($module == 'mobile')
            {
                $request->setModuleName('mobile')->setControllerName('index')
                    ->setActionName('index');
            }
            else
            {
                $request->setModuleName('default')->setControllerName('index')
                    ->setActionName('index');
            }
        }
    }
}
?>
