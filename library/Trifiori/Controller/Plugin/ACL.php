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

        /*TODO: MAXII
        En
        http://zfsite.andreinikolov.com/2008/08/part-6-very-simple-acl-plugin-and-simple-ajax-with-jquery/
        lo hace así:
        store ACL object connection in registry
        Zend_Registry::set('acl', $acl);
        */

        /*OLD: Zend_Registry::getInstance()->accesslist = $acl;*/
        Zend_Registry::set('acl', $acl);

    }

    public function preDispatch( Zend_Controller_Request_Abstract $request )
    {

// Comentar Control+D; Descomentar Control+Shift+D; KATE FTW.
//         /*TODO: Ver cómo modificamos esto.*/
//         $acl = Zend_Registry::get('acl');
// 
//         $aclNamespace = new Zend_Session_Namespace( 'ACL' );
// 
//         if ( ! isset( $aclNamespace->accessLevel ) )
//             $aclNamespace->accessLevel = 'guest';
// 
//         /* Check access level */
//         if ( ! $acl->isAllowed( $aclNamespace->accessLevel,
//                         $request->getControllerName(), $request->getActionName() ) )
//         {
//             if ( $aclNamespace->accessLevel == 'guest' )
//             {
//                 /* just allow them to login */
//                 $request->setControllerName( 'login' );
//                 $request->setActionName( 'index' );
//             }
//             else
//             {
//                 /* access denied page */
//                 $request->setControllerName( 'access' );
//                 $request->setActionName( 'denied' );
//             }
//         }
// 
//         /* Load Config File for this controller */
//         $view = Zend_Registry::get('view');
//         $controllerName = $request->getControllerName();
//         $resource = Zend_Registry::get('lang_resource');
// 
//         $view->config_load($resource, $controllerName);

    }
}
?>