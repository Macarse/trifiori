<?php
class user_BanderasController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;
    protected $_flashMessenger = null;

    public function init()
    {
        parent::init();
        $this->_flashMessenger = $this->_helper->getHelper('FlashMessenger');
    }

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
    }

    public function addbanderasAction()
    {
        $this->view->headTitle($this->language->_("Agregar Bandera"));

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddBanderaTrack']))
            {
                $this->_addform = $this->getBanderaAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $banderasTable = new Banderas();
                        $banderasTable->addBandera($values['name']);
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

        $this->view->banderaAddForm = $this->getBanderaAddForm();
    }

    public function listbanderasAction()
    {
        $this->view->headTitle($this->language->_("Listar Banderas"));

        $this->view->paginator = null;

        /*Errors from the past are deleted*/
        unset($this->view->error);
        unset($this->view->message);

        $this->view->message = $this->_flashMessenger->getMessages();

        $this->_searchform = $this->getBanderaSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $banderasT = new Banderas();

                if (isset($_GET["consulta"]))
                {
                    $banderas = $banderasT->searchBandera($_GET["consulta"]);
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                }
                else
                {
                    $banderas = $banderasT->select();
                    Zend_Registry::set('busqueda', "");
                }
                //$banderas = $banderasT->searchBandera($values["bandera"]);
                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($banderas, $banderasT));
                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }
                //$paginator->setCurrentPageNumber($this->_getParam('page'));
                $paginator->setItemCountPerPage(15);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->banderaSearchForm = $this->getBanderaSearchForm();
    }

    public function removebanderasAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
        }
        else
        {
            try
            {
                $banderasTable = new Banderas();
                $banderasTable->removeBandera( $this->getRequest()->getParam('id') );
                $this->_flashMessenger->addMessage($this->language->_("Eliminación exitosa."));
            }
            catch (Zend_Exception $error)
            {
                $this->_flashMessenger->addMessage( "<div class=\"errors\">" .
                    $this->language->_("No se puedo eliminar. Error en la Base de datos.") .
                    "</div>"
                    );
            }
        }

        $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
    }

    public function modbanderasAction()
    {
        $this->view->headTitle($this->language->_("Modificar Bandera"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->banderaModForm = $this->getBanderaModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModBanderaTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $banderasTable = new Banderas();
                        $banderasTable->modifyBandera( $this->_id,
                                            $values['name']);
                        $this->_flashMessenger->addMessage($this->language->_("Modificación exitosa."));
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->_flashMessenger->addMessage("<div class=\"errors\">".
                            $this->language->_("No se pudo modificar. Error en la Base de datos.") .
                            "</div>"
                                            );
                    }

                    $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
                }
            }
        }
    }

    private function getBanderaModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $bandera = new Banderas();
        $row = $bandera->getBanderaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/banderas/listbanderas');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $name = $this->_modform->createElement('text', 'name',
                array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(true);

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement('hidden', 'ModBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Modificar')));

        return $this->_modform;
    }

    private function getBanderaAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform ->setAction($this->_baseUrl)
                        ->setName('form')
                        ->setMethod('post');

        $name = $this->_addform->createElement('text', 'name',
            array('label' => '*' . $this->language->_('Nombre')));
        $name->setValue("");
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement('hidden', 'AddBanderaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => $this->language->_('Agregar')));

        return $this->_addform;
    }

    private function getBanderaSearchForm()
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

        $banderas = $this->_searchform->createElement('text', 'consulta',
            array('label' => $this->language->_('Nombre')));
        $banderas   ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 150));

        // Add elements to form:
        $this->_searchform->addElement($banderas)
             ->addElement('hidden', 'SearchBanderaTrack', array('values' => 'logPost'))
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

            $model = new Banderas();
            $banderas = $model->fetchAll("NOMBRE_BAN LIKE '" .  $this->_name . "%'");

            foreach ($banderas as $row)
            {
                array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
            }

            $arr = array("Resultset" => array("Result" => $aux));

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
