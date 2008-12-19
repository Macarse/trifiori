<?php
class user_ProveedoresController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;
    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
        parent::init();
    }

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
    }

    public function addproveedoresAction()
    {
        $this->view->headTitle($this->language->_("Agregar Proveedor"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddProveedorTrack']))
            {
                $this->_addform = $this->getProveedorAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $proveedoresTable = new Proveedores();
                        $proveedoresTable->addProveedor($values['name'],
                                                        $values['adress'],
                                                        $values['tel'],
                                                        $values['fax'],
                                                        $values['mail']
                                                        );
                        $this->view->message = $this->language->_("Inserción exitosa.");
                        $this->_addform = null;
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $this->language->_("Error en la Base de datos.");
                    }
                }
            }
        }

        $this->view->proveedorAddForm = $this->getProveedorAddForm();
    }

    public function listproveedoresAction()
    {
        $this->view->headTitle($this->language->_("Listar Proveedores"));

        $this->view->paginator = null;
        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();
        $this->view->sort = ( isset($_GET["sort"] ) ) ? $_GET["sort"] : 'asc' ;
        $this->view->sortby = ( isset($_GET["sortby"] ) ) ? $_GET["sortby"] : '' ;

        $this->_searchform = $this->getProveedorSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $proveedoresT = new Proveedores();

                if (isset($_GET["consulta"]))
                {
                    if (isset($_GET["sortby"]))
                    {
                        if (isset($_GET["sort"]))
                        {
                            $proveedores = $proveedoresT->searchProveedor($_GET["consulta"], $_GET["sortby"], $_GET["sort"]);
                            $mySortType = $_GET["sort"];
                        }
                        else
                        {
                            $proveedores = $proveedoresT->searchProveedor($_GET["consulta"], $_GET["sortby"], null);
                            $mySortType = null;
                        }
                        $mySortBy = $_GET["sortby"];
                    }
                    else
                    {
                        $proveedores = $proveedoresT->searchProveedor($_GET["consulta"], null, null);
                        $mySortType = null;
                        $mySortBy = null;
                    }
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                    Zend_Registry::set('sortby', $mySortBy);
                    Zend_Registry::set('sorttype', $mySortType);
                }
                else
                {
                    $proveedores = $proveedoresT->searchProveedor("", "", "");

                    Zend_Registry::set('sortby', "");
                    Zend_Registry::set('sorttype', "");
                    Zend_Registry::set('busqueda', "");
                }
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($proveedores, $proveedoresT));

                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }

                $paginator->setItemCountPerPage(15);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $this->language->_("Error en la Base de datos.");
            }
        }
        $this->view->proveedorSearchForm = $this->getProveedorSearchForm();
    }

    public function removeproveedoresAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
        }
        else
        {
            try
            {
                $proveedoresTable = new Proveedores();
                $proveedoresTable->removeProveedor( $this->getRequest()->getParam('id') );
                $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
                $this->_flashMessenger->addMessage(
                        $this->language->_("No se puedo eliminar. Error en la Base de datos.")
                                                );
            }
        }

        $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
    }

    public function modproveedoresAction()
    {
        $this->view->headTitle($this->language->_("Modificar Proveedor"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->proveedorModForm = $this->getProveedorModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
            }
        }
        else
        {
            $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
        }
        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModProveedorTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $proveedoresTable = new Proveedores();
                        $proveedoresTable->modifyProveedor(    $this->_id,
                                                        $values['name'],
                                                        $values['adress'],
                                                        $values['tel'],
                                                        $values['fax'],
                                                        $values['mail']
                                                    );
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->_flashMessenger->addMessage(
                            $this->language->_("No se puedo modificar. Error en la Base de datos.")
                                                        );
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
                }
            }
        }
    }

    private function getProveedorModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        try
        {
            /*Levanto el usuario para completar el form.*/
            $proveedoresTable = new Proveedores();
            $row = $proveedoresTable->getProveedorByID( $id );
        }
        catch (Zend_Exception $error)
        {
            return NULL;
        }

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
        }

        $this   ->_modform = new Zend_Form();
        $this   ->_modform->setAction($this->_baseUrl)
                ->setName('form')
                ->setMethod('post');

        $name = $this->_modform->createElement('text', 'name',
                array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 100))
             ->setRequired(true);

        $adress = $this->_modform->createElement('text', 'adress',
                array('label' => '*' . $this->language->_('Dirección')));
        $adress  ->setValue($row->adress() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 200))
                    ->setRequired(True);

        $tel = $this->_modform->createElement('text', 'tel',
                array('label' => '*' . $this->language->_('Teléfono')));
        $tel    ->setValue($row->tel() )
                ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(True);

        $fax = $this->_modform->createElement('text', 'fax',
                array('label' => $this->language->_('Fax')));
        $fax    ->setValue($row->fax() )
                ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(False);

        $mail = $this->_modform->createElement('text', 'mail',
                array('label' => $this->language->_('E-mail')));
        $mail   ->setValue($row->mail() )
                ->addValidator('stringLength', false, array(1, 100))
                ->addValidator('EmailAddress')
                ->setRequired(False);


        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($adress)
             ->addElement($tel)
             ->addElement($fax)
             ->addElement($mail)
             ->addElement('hidden', 'ModProveedorTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getProveedorAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $name = $this->_addform->createElement('text', 'name',
            array('label' => '*' . $this->language->_('Nombre')));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 150))
                 ->addValidator(new CV_Validate_ProveedorExiste())
                 ->setRequired(true);

        $adress = $this->_addform->createElement('text', 'adress',
            array('label' => '*' . $this->language->_('Dirección')));
        $adress ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 200))
                ->setRequired(True);

        $tel = $this->_addform->createElement('text', 'tel',
            array('label' => '*' . $this->language->_('Teléfono')));
        $tel    ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(True);

        $fax = $this->_addform->createElement('text', 'fax',
            array('label' => $this->language->_('Fax')));
        $fax    ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(False);

        $mail = $this->_addform->createElement('text', 'mail',
            array('label' => $this->language->_('E-mail')));
        $mail   ->addValidator('stringLength', false, array(1, 100))
                ->addValidator('EmailAddress')
                ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($adress)
             ->addElement($tel)
             ->addElement($fax)
             ->addElement($mail)
             ->addElement('hidden', 'AddProveedorTrack', array('values' => 'logPost'))
                ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));

        return $this->_addform;
    }

    private function getProveedorSearchForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform  ->setAction($this->_baseUrl)
                            ->setName('form')
                            ->setMethod('get');

        $proveedor = $this->_searchform->createElement('text', 'consulta',
                array('label' => $this->language->_('Nombre')));
        $proveedor  ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
        $this->_searchform->addElement($proveedor)
             ->addElement('hidden', 'SearchProveedorTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }

    public function getdataAction()
    {
        $arr = array();
        $aux = array();

        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();

        if ( $this->getRequest()->getParam('query') != null )
        {
            $this->_name = $this->getRequest()->getParam('query');


            try
            {
                $model = new Proveedores();
                $data = $model->fetchAll("NOMBRE_TRA LIKE '" .  $this->_name . "%' AND DELETED LIKE '0'");

                foreach ($data as $row)
                {
                    array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
                }

                $arr = array("Resultset" => array("Result" => $aux));
            }
            catch (Zend_Exception $error)
            {
                $arry = array();
            }

            try
            {
                $responseDataJsonEncoded = Zend_Json::encode($arr);
                $this->getResponse()->setHeader('Content-Type', 'application/json')
                                    ->setBody($responseDataJsonEncoded);

            }
            catch(Zend_Json_Exception $e)
            {
                // handle and generate HTTP error code response, see below
                $this->getResponse()->setHeader('Content-Type', 'application/json')
                                    ->setBody('[{Error}]');
            }
        }
   }
}
?>
