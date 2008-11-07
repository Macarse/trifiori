<?php
class user_ClientesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
    }

    public function addclientesAction()
    {
        $this->view->headTitle("Agregar Cliente");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddClienteTrack']))
            {
                $this->_addform = $this->getClienteAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $clientesTable = new Clientes();
                        $clientesTable->addCliente( $values['name'],
                                                    $values['dir'],
                                                    $values['CP'],
                                                    $values['localidad'],
                                                    $values['cuit'],
                                                    $values['tipoIVA'],
                                                    $values['tipoCliente']
                                                    );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->clienteAddForm = $this->getClienteAddForm();
    }

    public function listclientesAction()
    {
        $this->view->headTitle("Listar Clientes");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Clientes();
            $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($table->select(), $table));
            $paginator->setCurrentPageNumber($this->_getParam('page'));
            $paginator->setItemCountPerPage(15);
            $this->view->paginator = $paginator;
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }

    public function removeclientesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
        }
        else
        {
            try
            {
            $clientesTable = new Clientes();
            $clientesTable->removeCliente( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
    }

    public function modclientesAction()
    {
        $this->view->headTitle("Modificar Cliente");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->clienteModForm = $this->getClienteModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModClienteTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $clientesTable = new Clientes();
                        $clientesTable->modifyCliente(  $this->_id,
                                                        $values['name'],
                                                        $values['dir'],
                                                        $values['CP'],
                                                        $values['localidad'],
                                                        $values['cuit'],
                                                        $values['tipoIVA'],
                                                        $values['tipoCliente']
                                                    );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
                }
            }
        }
    }

    private function getClienteModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $clientesTable = new Clientes();
        $row = $clientesTable->getClienteByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/clientes/listclientes');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 200))
             ->setRequired(true);

        $dir = $this->_modform->createElement('text', 'dir', array('label' => 'Dirección'));
        $dir ->setValue($row->adress() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 200))
             ->setRequired(false);

        $CP = $this->_modform->createElement('text', 'CP', array('label' => 'Código Postal'));
        $CP ->setValue($row->codPostal() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 15))
             ->setRequired(false);

        $localidad = $this->_modform->createElement('text', 'localidad', array('label' => 'Localidad'));
        $localidad ->setValue($row->localidad() )
                   ->addValidator($alnumWithWS)
                   ->addValidator('stringLength', false, array(1, 150))
                   ->setRequired(false);

        // Validar CUIT 
        $cuit = $this->_modform->createElement('text', 'cuit', array('label' => 'CUIT'));
        $cuit ->setValue($row->CUIT() )
                   ->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 13))
                   ->setRequired(false);


        $tipoIVA = $this->_modform->createElement('select', 'tipoIVA');
        $tipoIVA    ->setValue( $row->tipoIVA() )
                    ->setRequired(false)
                    ->setOrder(1)
                    ->setLabel('Tipo IVA')
                    ->setMultiOptions(array('Responsable Inscripto' => 'Responsable Inscripto',
                                            'Responsable No Inscripto' => 'Responsable No Inscripto'
                                            ));

        $tipoCliente = $this->_modform->createElement('select', 'tipoCliente');
        $tipoCliente    ->setValue( $row->tipoCliente() )
                        ->setRequired(false)
                        ->setOrder(2)
                        ->setLabel('Tipo Cliente')
                        ->setMultiOptions(array('Alto Volumen' => 'Alto Volumen',
                                                'Bajo Volumen' => 'Bajo Volumen',
                                                'Promedio' => 'Promedio'
                                            ));

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($dir)
             ->addElement($CP)
             ->addElement($localidad)
             ->addElement($cuit)
             ->addElement($tipoIVA)
             ->addElement($tipoCliente)
             ->addElement('hidden', 'ModClienteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getClienteAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 200))
             ->setRequired(true);

        $dir = $this->_addform->createElement('text', 'dir', array('label' => 'Dirección'));
        $dir ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 200))
             ->setRequired(false);

        $CP = $this->_addform->createElement('text', 'CP', array('label' => 'Código Postal'));
        $CP  ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 15))
             ->setRequired(false);

        $localidad = $this->_addform->createElement('text', 'localidad', array('label' => 'Localidad'));
        $localidad ->addValidator($alnumWithWS)
                   ->addValidator('stringLength', false, array(1, 150))
                   ->setRequired(false);

        $cuit = $this->_addform->createElement('text', 'cuit', array('label' => 'CUIT'));
        $cuit   ->addValidator('alnum')
                ->addValidator('stringLength', false, array(1, 13))
                ->setRequired(false);


        $tipoIVA = $this->_addform->createElement('select', 'tipoIVA');
        $tipoIVA    ->setRequired(false)
                    ->setOrder(1)
                    ->setLabel('Tipo IVA')
                    ->setMultiOptions(array('Responsable Inscripto' => 'Responsable Inscripto',
                                            'Responsable No Inscripto' => 'Responsable No Inscripto'
                                            ));

        $tipoCliente = $this->_addform->createElement('select', 'tipoCliente');
        $tipoCliente    ->setRequired(false)
                        ->setOrder(2)
                        ->setLabel('Tipo Cliente')
                        ->setMultiOptions(array('Alto Volumen' => 'Alto Volumen',
                                                'Bajo Volumen' => 'Bajo Volumen',
                                                'Promedio' => 'Promedio'
                                            ));

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($dir)
             ->addElement($CP)
             ->addElement($localidad)
             ->addElement($cuit)
             ->addElement($tipoIVA)
             ->addElement($tipoCliente)
             ->addElement('hidden', 'AddClienteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));


        return $this->_addform;
    }
	
   public function getdataAction() {
       $arr = array();
	   $aux = array();
	   
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->layout()->disableLayout();
	   
	   if ( $this->getRequest()->getParam('query') != null )
        {
            $this->_name = $this->getRequest()->getParam('query');

		   $model = new Clientes();
		   $data = $model->fetchAll("NOMBRE_CLI LIKE '" .  $this->_name . "%'");
		   
           foreach ($data as $row)
		   {
               array_push($aux, array("id" => $row->id(), "data" => $row->name()));	
	       }
	
		   $arr = array("Resultset" => array("Result" => $aux));
	
		   try {
			   $responseDataJsonEncoded = Zend_Json::encode($arr);
			   $this->getResponse()->setHeader('Content-Type', 'application/json')
								   ->setBody($responseDataJsonEncoded);
	
		   } catch(Zend_Json_Exception $e) {
			   // handle and generate HTTP error code response, see below
			   $this->getResponse()->setHeader('Content-Type', 'application/json')
								   ->setBody('[{Error}]');
		   }
		 }
   }
}
?>
