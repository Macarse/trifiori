<?php
class user_TransportesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_searchform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
    }

    public function addtransportesAction()
    {
        $this->view->headTitle("Agregar Transporte");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddTransporteTrack']))
            {
                $this->_addform = $this->getTransporteAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $transportesTable = new Transportes();
                        $transportesTable->addTransporte(   $values['codBandera'],
                                                            $values['codMedio'],
                                                            $values['name'],
                                                            $values['observaciones']
                                                        );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->transporteAddForm = $this->getTransporteAddForm();
    }

    public function listtransportesAction()
    {
        $this->view->headTitle("Listar Transportes");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Transportes();
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

    public function removetransportesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }
        else
        {
            try
            {
            $transportesTable = new Transportes();
            $transportesTable->removeTransporte( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
    }

    public function modtransportesAction()
    {
        $this->view->headTitle("Modificar Transporte");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->transporteModForm = $this->getTransporteModForm($this->_id)) == null)
            {
               $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModTransporteTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $transportesTable = new Transportes();
                        $transportesTable->modifyTransporte(    $this->_id,
                                                                $values['codBandera'],
                                                                $values['codMedio'],
                                                                $values['name'],
                                                                $values['observaciones']
                                                            );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
                }
            }
        }
    }

    private function getTransporteAddForm()
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


       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codBandera = $this->_addform->createElement('select', 'codBandera');
        $codBandera ->setRequired(true)
                    ->setOrder(1)
                    ->setLabel('*' . 'Bandera')
                    ->setMultiOptions($banderasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $mediosTable = new Medios();
        $mediosOptions =  $mediosTable->getMediosArray();

        $codMedio = $this->_addform->createElement('select', 'codMedio');
        $codMedio   ->setRequired(true)
                    ->setOrder(2)
                    ->setLabel('*' . 'Medio')
                    ->setMultiOptions($mediosOptions);


        $name = $this->_addform->createElement('text', 'name', array('label' => '*' . 'Nombre'));
        $name->addValidator($alnumWithWS)
                 ->addValidator('stringLength', false, array(1, 100))
                 ->setRequired(true);

        $observaciones = $this->_addform->createElement('text', 'observaciones',
                                                         array('label' => 'Observaciones')
                                                        );
        $observaciones  ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 400))
                        ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($name)
                       ->addElement($codBandera)
                       ->addElement($codMedio)
                       ->addElement($observaciones)
             ->addElement('hidden', 'AddTransporteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_addform;
    }

    private function getTransporteModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Levanto el transporte para completar el form.*/
        $transportesTable = new Transportes();
        $row = $transportesTable->getTransporteByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codBandera = $this->_modform->createElement('select', 'codBandera');
        $codBandera ->setValue( $row->codBandera() )
                    ->setRequired(true)
                    ->setOrder(1)
                    ->setLabel('*' . 'Bandera')
                    ->setMultiOptions($banderasOptions);

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $mediosTable = new Medios();
        $mediosOptions =  $mediosTable->getMediosArray();

        $codMedio = $this->_modform->createElement('select', 'codMedio');
        $codMedio   ->setValue( $row->codMedio() )
                    ->setRequired(true)
                    ->setOrder(2)
                    ->setLabel('*' . 'Medio')
                    ->setMultiOptions($mediosOptions);

        $name = $this->_modform->createElement('text', 'name', array('label' => '*' . 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 400))
             ->setRequired(true);

        $observaciones = $this->_modform->createElement('text', 'observaciones',
                                                         array('label' => 'Observaciones')
                                                        );
        $observaciones  ->setValue($row->observaciones() )
                        ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 400))
                        ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($name)
                       ->addElement($codBandera)
                       ->addElement($codMedio)
                       ->addElement($observaciones)
             ->addElement('hidden', 'ModTransporteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

   public function getdataTransportesAction() {

       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->layout()->disableLayout();

       //$model = $this->getModelInstance();
       $model = new Transportess();

       /* fetch id from request, value validation omitted */
       $id = $this->getRequest()->getParam('id');

       /* [Json response] */

       $responseData = $model->getTransportesArray();

       try {

           $responseDataJsonEncoded = Zend_Json::encode($responseData);
           $this->getResponse()->setHeader('Content-Type', 'application/json')
                               ->setBody($responseDataJsonEncoded);

       } catch(Zend_Json_Exception $e) {
           // handle and generate HTTP error code response, see below
       }
   }
   
   	private function getTransporteSearchForm()
    {      
        $alnumWithWS = new Zend_Validate_Alnum(True);
        
        if (null !== $this->_searchform)
        {
            return $this->_searchform;
        }

        $this->_searchform = new Zend_Form();
        $this->_searchform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

        $transporte = $this->_searchform->createElement('text', 'cliente', array('label' => $this->language->_('Nombre')));
        $transporte       ->addValidator($alnumWithWS)
                     ->addValidator('stringLength', false, array(1, 100));

        // Add elements to form:
        $this->_searchform->addElement($transporte)
             ->addElement('hidden', 'SearchTransporteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }

	public function getdataAction() {
       $arr = array();
	   $aux = array();
	   
       $this->_helper->viewRenderer->setNoRender();
       $this->_helper->layout()->disableLayout();
	   
	   if ( $this->getRequest()->getParam('query') != null )
        {
            $this->_name = $this->getRequest()->getParam('query');

		   $model = new Transportes();
		   $data = $model->fetchAll("NOMBRE_BUQ LIKE '" .  $this->_name . "%'");
		   
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
