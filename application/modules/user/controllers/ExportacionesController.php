<?php
class user_ExportacionesController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
    }

    public function addexportacionesAction()
    {
        $this->view->headTitle("Agregar Exportaciones");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddExportacionTrack']))
            {
                $this->_addform = $this->getExportacionAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

		    //fecha
		    $date = new Zend_Date;
		    if ($date->isDate($values['date']))
		    	$date->set($values['date']);
		    else
		    {
		    	echo 'fecha invalida';
		    	$this->view->error = 'Invalid Date';
		    }


		/*
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
		   */
                }
            }
        }

        $this->view->exportacionAddForm = $this->getExportacionAddForm();
    }

    public function listexportacionesAction()
    {
        $this->view->headTitle("Listar Exportaciones");

        /*Errors from the past are deleted*/
        unset($this->view->error);
	/*
        try
        {
            $table = new Transportes();
            $this->view->Transportes = $table->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
	*/
    }

    public function removeexportacionesAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
        }
        else
        {
	/*
            try
            {
            $transportesTable = new Transportes();
            $transportesTable->removeTransporte( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
	    */
        }

        $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
    }

    public function modexportacionesAction()
    {
        $this->view->headTitle("Modificar Exportaciones");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->exportacionModForm = $this->getExportacionModForm($this->_id)) == null)
            {
               $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModExportacionTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();
		/*
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
		*/

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/exportaciones/listexportaciones');
                }
            }
        }
    }

    private function getExportacionAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');


       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $date = $this->_addform->createElement('text', 'date', array('label' => 'Fecha', 'id' => 'date')); 
          $date->addValidator('date')
                 ->addValidator('stringLength', false, array(1, 100))
                 ->setRequired(true)
                 ->addFilter('StringToLower');

        // Add elements to form:
        $this->_addform->addElement($date)
             ->addElement('hidden', 'AddExportacionTrack', array('values' => 'logPost'))
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

        /*Levanto el transporte para completar el form.*/
        $transportesTable = new Transportes();
        $row = $transportesTable->getTransporteByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/transportes/listtransportes');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');

        /*Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codBandera = $this->_modform->createElement('select', 'codBandera');
        $codBandera ->setValue( $row->codBandera() )
                    ->setRequired(true)
                    ->setOrder(1)
                    ->setLabel('Bandera')
                    ->setMultiOptions($banderasOptions);

//         /*TODO: MODIFICAR POR COD_MEDIO CUANDO ESTÉ!*/
        $banderasTable = new Banderas();
        $banderasOptions =  $banderasTable->getBanderasArray();

        $codMedio = $this->_modform->createElement('select', 'codMedio');
        $codMedio   ->setValue( $row->codMedio() )
                    ->setRequired(true)
                    ->setOrder(2)
                    ->setLabel('Medio')
                    ->setMultiOptions($banderasOptions);

        $name = $this->_modform->createElement('text', 'name', array('label' => 'Nombre'));
        $name->setValue($row->name() )
             ->addValidator('alnum')
             ->addValidator('stringLength', false, array(1, 400))
             ->setRequired(true)
             ->addFilter('StringToLower');

        $observaciones = $this->_modform->createElement('text', 'observaciones',
                                                         array('label' => 'Observaciones')
                                                        );
        $observaciones  ->setValue($row->observaciones() )
                        ->addValidator('alnum')
                        ->addValidator('stringLength', false, array(1, 400))
                        ->setRequired(False)
                        ->addFilter('StringToLower');

        // Add elements to form:
        $this->_modform->addElement($name)
                       ->addElement($codBandera)
                       ->addElement($codMedio)
                       ->addElement($observaciones)
             ->addElement('hidden', 'ModTransporteTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }


}
?>
