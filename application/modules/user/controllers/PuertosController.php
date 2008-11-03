<?php
class user_PuertosController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
    }

    public function addpuertosAction()
    {
        $this->view->headTitle("Agregar Puerto");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddPuertoTrack']))
            {
                $this->_addform = $this->getPuertoAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $puertosTable = new Puertos();
                        $puertosTable->addPuerto($values['name'],
                                                 $values['ubicacion']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->puertoAddForm = $this->getPuertoAddForm();
    }

    public function listpuertosAction()
    {
        $this->view->headTitle("Listar Puertos");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Puertos();
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

    public function removepuertosAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
        }
        else
        {
            try
            {
            $puertosTable = new Puertos();
            $puertosTable->removePuerto( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
    }

    public function modpuertosAction()
    {
        $this->view->headTitle("Modificar Puerto");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->puertoModForm = $this->getPuertoModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModPuertoTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $puertosTable = new Puertos();
                        $puertosTable->modifyPuerto(    $this->_id,
                                                        $values['name'],
                                                        $values['ubicacion']
                                                    );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
                }
            }
        }
    }

    private function getPuertoModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $puertosTable = new Puertos();
        $row = $puertosTable->getPuertoByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/puertos/listpuertos');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 200))
             ->setRequired(true);

        $ubicacion = $this->_modform->createElement('text', 'ubicacion', array('label' => 'Ubicación'));
        $ubicacion  ->setValue($row->ubicacion() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($name)
             ->addElement($ubicacion)
             ->addElement('hidden', 'ModPuertoTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getPuertoAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $name = $this->_addform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 150))
                 ->setRequired(true);

        $ubicacion = $this->_addform->createElement('text', 'ubicacion', array('label' => 'Ubicación'));
        $ubicacion  ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($name)
             ->addElement($ubicacion)
             ->addElement('hidden', 'AddPuertoTrack', array('values' => 'logPost'))
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

		   $model = new Cargas();
		   $data = $model->fetchAll("NOMBRE_PUE LIKE '" .  $this->_name . "%'");
		   
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