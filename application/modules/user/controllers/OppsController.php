<?php
class user_OppsController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/opps/listopps');
    }

    public function addoppsAction()
    {
        $this->view->headTitle("Agregar Opp");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddOppTrack']))
            {
                $this->_addform = $this->getOppAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $oppsTable = new Opps();
                        $oppsTable->addOpp( $values['declaracionOk'],
                                            $values['pedidoDinero'],
                                            $values['otrosOpp'],
                                            $values['fraccionado'],
                                            $values['estampillas'],
                                            $values['impuestosInternos']
                                            );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->oppAddForm = $this->getOppAddForm();
    }

    public function listoppsAction()
    {
        $this->view->headTitle("Listar Opps");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Opps();
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

    public function removeoppsAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/opps/listopps');
        }
        else
        {
            try
            {
            $oppsTable = new Opps();
            $oppsTable->removeOpp( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/opps/listopps');
    }

    public function modoppsAction()
    {
        $this->view->headTitle("Modificar Opp");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->oppModForm = $this->getOppModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/opps/listopps');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModOppTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $oppsTable = new Opps();
                        $oppsTable->modifyOpp(  $this->_id,
                                                $values['declaracionOk'],
                                                $values['pedidoDinero'],
                                                $values['otrosOpp'],
                                                $values['fraccionado'],
                                                $values['estampillas'],
                                                $values['impuestosInternos']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/opps/listopps');
                }
            }
        }
    }

    private function getOppModForm( $id )
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $oppsTable = new Opps();
        $row = $oppsTable->getOppByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/opps/listopps');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');


        $siNo = array( 's' => 'Sí', 'n' => 'No');

        $declaracionOk = $this->_modform->createElement('select', 'declaracionOk');
        $declaracionOk  ->setValue($row->declaracionOkchar() )
                        ->setOrder(1)
                        ->setLabel('Declaración Ok')
                        ->setRequired(true)
                        ->setMultiOptions($siNo);


        $pedidoDinero = $this->_modform->createElement('text', 'pedidoDinero',
                                                     array('label' => 'Pedido de Dinero',
                                                     'id' => 'idpedidoDinero',
                                                     'onKeyPress' => "keyCalendar(event,'calpedidoDinero');"
                                                     ));
        $pedidoDinero   ->setValue($row->pedidoDinero() )
                        ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $otrosOpp = $this->_modform->createElement('text', 'otrosOpp',
                                                array('label' => 'Otros Opp'));
        $otrosOpp   ->setValue($row->otrosOpp() )
                    ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        $fraccionado = $this->_modform->createElement('text', 'fraccionado',
                                                    array('label' => 'Fraccionado Opp'));
        $fraccionado ->setValue($row->fraccionado() )
             ->addValidator($alnumWithWS)
             ->addValidator('stringLength', false, array(1, 150))
             ->setRequired(False);


        $estampillas = $this->_modform->createElement('text', 'estampillas',
                                                    array('label' => 'Estampillas'));
        $estampillas ->setValue($row->estampillas() )
                   ->addValidator($alnumWithWS)
                   ->addValidator('stringLength', false, array(1, 150))
                   ->setRequired(False);


        $impuestosInternos = $this->_modform->createElement('text', 'impuestosInternos',
                                                    array('label' => 'Impuestos Internos'));
        $impuestosInternos  ->setValue($row->estampillas() )
                            ->addValidator($alnumWithWS)
                            ->addValidator('stringLength', false, array(1, 150))
                            ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($declaracionOk)
             ->addElement($pedidoDinero)
             ->addElement($otrosOpp)
             ->addElement($fraccionado)
             ->addElement($estampillas)
             ->addElement($impuestosInternos)
             ->addElement('hidden', 'ModOppTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getOppAddForm()
    {
        $alnumWithWS = new Zend_Validate_Alnum(True);

        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $siNo = array( 's' => 'Sí', 'n' => 'No');

        $declaracionOk = $this->_addform->createElement('select', 'declaracionOk');
        $declaracionOk  ->setOrder(1)
                        ->setLabel('Declaración Ok')
                        ->setRequired(true)
                        ->setMultiOptions($siNo);


        $pedidoDinero = $this->_addform->createElement('text', 'pedidoDinero',
                                                     array('label' => 'Pedido de Dinero',
                                                     'id' => 'idpedidoDinero',
                                                     'onKeyPress' => "keyCalendar(event,'calpedidoDinero');"
                                                     ));
        $pedidoDinero   ->addValidator('date')
                        ->addValidator('stringLength', false, array(1, 12))
                        ->setRequired(True);


        $otrosOpp = $this->_addform->createElement('text', 'otrosOpp',
                                                array('label' => 'Otros Opp'));
        $otrosOpp   ->addValidator($alnumWithWS)
                    ->addValidator('stringLength', false, array(1, 255))
                    ->setRequired(False);

        $fraccionado = $this->_addform->createElement('text', 'fraccionado',
                                                    array('label' => 'Fraccionado Opp'));

        $fraccionado    ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 150))
                        ->setRequired(False);


        $estampillas = $this->_addform->createElement('text', 'estampillas',
                                                    array('label' => 'Estampillas'));

        $estampillas    ->addValidator($alnumWithWS)
                        ->addValidator('stringLength', false, array(1, 150))
                        ->setRequired(False);


        $impuestosInternos = $this->_addform->createElement('text', 'impuestosInternos',
                                                    array('label' => 'Impuestos Internos'));

        $impuestosInternos  ->addValidator($alnumWithWS)
                            ->addValidator('stringLength', false, array(1, 150))
                            ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($declaracionOk)
             ->addElement($pedidoDinero)
             ->addElement($otrosOpp)
             ->addElement($fraccionado)
             ->addElement($estampillas)
             ->addElement($impuestosInternos)
             ->addElement('hidden', 'AddOppTrack', array('values' => 'logPost'))
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

		   $model = new Opps();
		   $data = $model->fetchAll("PEDIDO_DE_DINERO_OPP LIKE '" .  $this->_name . "%'");
		   
           foreach ($data as $row)
		   {
               array_push($aux, array("id" => $row->id(), "data" => $row->pedidoDinero()));	
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
