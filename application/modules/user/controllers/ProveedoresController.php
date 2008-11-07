<?php
class user_ProveedoresController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
    }

    public function addproveedoresAction()
    {
        $this->view->headTitle("Agregar Proveedor");

        /*Errors from the past are deleted*/
        unset($this->view->error);

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
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->proveedorAddForm = $this->getProveedorAddForm();
    }

    public function listproveedoresAction()
    {
        $this->view->headTitle("Listar Proveedores");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Proveedores();
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
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
    }

    public function modproveedoresAction()
    {
        $this->view->headTitle("Modificar Proveedor");

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
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
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

        /*Levanto el usuario para completar el form.*/
        $proveedoresTable = new Proveedores();
        $row = $proveedoresTable->getProveedorByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/proveedores/listproveedores');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 100))
             ->setRequired(true);

        $adress = $this->_modform->createElement('text', 'adress', array('label' => 'Dirección'));
        $adress  ->setValue($row->adress() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 200))
                    ->setRequired(True);

        $tel = $this->_modform->createElement('text', 'tel', array('label' => 'Teléfono'));
        $tel    ->setValue($row->tel() )
                ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(True);

        $fax = $this->_modform->createElement('text', 'fax', array('label' => 'Fax'));
        $fax    ->setValue($row->fax() )
                ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(False);

        $mail = $this->_modform->createElement('text', 'mail', array('label' => 'E-mail'));
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
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getProveedorAddForm()
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
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true);

        $adress = $this->_addform->createElement('text', 'adress', array('label' => 'Dirección'));
        $adress ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 200))
                ->setRequired(True);

        $tel = $this->_addform->createElement('text', 'tel', array('label' => 'Teléfono'));
        $tel    ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(True);

        $fax = $this->_addform->createElement('text', 'fax', array('label' => 'Fax'));
        $fax    ->addValidator('stringLength', false, array(1, 150))
                ->setRequired(False);

        $mail = $this->_addform->createElement('text', 'mail', array('label' => 'E-mail'));
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
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

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

		   $model = new Proveedores();
		   $data = $model->fetchAll("NOMBRE_TRA LIKE '" .  $this->_name . "%'");
		   
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
