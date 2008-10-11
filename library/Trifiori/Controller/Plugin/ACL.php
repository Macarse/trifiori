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

        /* Guest */
        $acl->allow('guest', 'default');
        $acl->deny('guest', 'user');
        $acl->deny('guest', 'admin');

        /* Usuario */
        $acl->allow('user', 'default');
        $acl->allow('user', 'user');
        $acl->deny('user', 'admin');

        /* Administrador */
        $acl->allow('admin', 'default');
        $acl->allow('admin', 'user');
        $acl->allow('admin', 'admin');

        Zend_Registry::set('acl', $acl);
    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {
        $module = $request->getModuleName();
        $acl = Zend_Registry::get('acl');
        $username = Zend_Registry::get('name');
        
        if ( !$acl->isAllowed($username, $module) )
        {
            $request->setModuleName('default')->setControllerName('index')
                ->setActionName('index');
        }
    }
}
?>