<?php
class user_CargasController extends Trifiori_User_Controller_Action
{
    protected $_addform;
    protected $_modform;
    protected $_id;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
    }

    public function addcargasAction()
    {
        $this->view->headTitle("Agregar Carga");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['AddCargaTrack']))
            {
                $this->_addform = $this->getCargaAddForm();
                if ($this->_addform->isValid($_POST))
                {
                    $values = $this->_addform->getValues();

                    try
                    {
                        $cargasTable = new Cargas();
                        $cargasTable->addCarga( $values['cantBultos'],
                                                $values['tipoEnvase'],
                                                $values['peso'],
                                                $values['unidad'],
                                                $values['nroPaquete'],
                                                $values['marcaYnum'],
                                                $values['mercIMCO']
                                              );
                    }
                    catch (Zend_Exception $error)
                    {
                        $this->view->error = $error;
                    }
                }
            }
        }

        $this->view->cargaAddForm = $this->getCargaAddForm();
    }

    public function listcargasAction()
    {
        $this->view->headTitle("Listar Cargas");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        try
        {
            $table = new Cargas();
            $this->view->Cargas = $table->fetchAll();
        }
        catch (Zend_Exception $error)
        {
            $this->view->error = $error;
        }
    }

    public function removecargasAction()
    {
        /*TODO: Agregar un "Seguro que desea eliminar?"*/
        if ( $this->getRequest()->getParam('id') === null )
        {
            $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
        }
        else
        {
            try
            {
            $cargasTable = new Cargas();
            $cargasTable->removePuerto( $this->getRequest()->getParam('id') );
            }
            catch (Zend_Exception $error)
            {
            $this->view->error = $error;
            }
        }

        $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
    }

    public function modcargasAction()
    {
        $this->view->headTitle("Modificar Carga");

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /*Si hay parámetros pedir el form*/
        if ( $this->getRequest()->getParam('id') != null )
        {
            $this->_id = $this->getRequest()->getParam('id');

            /*Si el ID no corresponde con la db, hacerlo volver al listado*/
            if (($this->view->cargaModForm = $this->getCargaModForm($this->_id)) == null)
            {
                $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
            }
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModCargaTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $cargasTable = new Cargas();
                        $cargasTable->modifyCarga( $this->_id,
                                                    $values['cantBultos'],
                                                    $values['tipoEnvase'],
                                                    $values['peso'],
                                                    $values['unidad'],
                                                    $values['nroPaquete'],
                                                    $values['marcaYnum'],
                                                    $values['mercIMCO']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
                }
            }
        }
    }

    private function getCargaModForm( $id )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        /*Levanto el usuario para completar el form.*/
        $cargasTable = new Cargas();
        $row = $cargasTable->getCargaByID( $id );

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/cargas/listcargas');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)->setMethod('post');


        $cantBultos = $this->_modform->createElement('text', 'cantBultos',
                                            array('label' => 'Cantidad de Bultos'));
        $cantBultos ->setValue($row->cantBultos() )
                    ->addValidator('digits')
                    ->addValidator('stringLength', false, array(1, 11))
                    ->setRequired(True);


        $tipoEnvase = $this->_modform->createElement('select', 'tipoEnvase');
        $tipoEnvase ->setValue($row->tipoEnvase() )
                    ->setRequired(True)
                    ->setOrder(1)
                    ->setLabel('Tipo Envase')
                    ->setMultiOptions(array('Envase Flexible' => 'Envase Flexible',
                                            'Caja' => 'Caja',
                                            'Frasco' => 'Frasco',
                                            'Tarro' => 'Tarro',
                                            'Lata de Aluminio' => 'Lata de Aluminio',
                                        ));

        $peso = $this->_modform->createElement('text', 'peso', array('label' => 'Peso'));
        $peso   ->setValue($row->peso() )
                ->addValidator('float')
                ->addValidator('stringLength', false, array(1, 10))
                ->setRequired(true);

        $unidad = $this->_modform->createElement('select', 'unidad');
        $unidad ->setValue($row->unidad() )
                ->setRequired(True)
                ->setOrder(2)
                ->setLabel('Unidad')
                ->setMultiOptions(array('Toneladas' => 'Toneladas',
                                        'Kilogramos' => 'Kilogramos',
                                        'Gramos' => 'Gramos'
                                        ));

        $nroPaquete = $this->_modform->createElement('text', 'nroPaquete',
                                            array('label' => 'Número de Paquete'));
        $nroPaquete ->setValue($row->nroPaquete() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 25))
                    ->setRequired(False);


        $marcaYnum = $this->_modform->createElement('text', 'marcaYnum',
                                            array('label' => 'Marca y número'));
        $marcaYnum  ->setValue($row->marcaYnum() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 100))
                    ->setRequired(False);


        $mercIMCO = $this->_modform->createElement('text', 'mercIMCO',
                                            array('label' => 'Merc. IMCO'));
        $mercIMCO   ->setValue($row->mercIMCO() )
                    ->addValidator('alnum')
                    ->addValidator('stringLength', false, array(1, 100))
                    ->setRequired(False);

        // Add elements to form:
        $this->_modform->addElement($cantBultos)
             ->addElement($tipoEnvase)
             ->addElement($peso)
             ->addElement($unidad)
             ->addElement($nroPaquete)
             ->addElement($marcaYnum)
             ->addElement($mercIMCO)
             ->addElement('hidden', 'ModCargaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => 'Ingresar'));

        return $this->_modform;
    }

    private function getCargaAddForm()
    {
        if (null !== $this->_addform)
        {
            return $this->_addform;
        }

        $this->_addform = new Zend_Form();
        $this->_addform->setAction($this->_baseUrl)->setMethod('post');

        $cantBultos = $this->_addform->createElement('text', 'cantBultos',
                                            array('label' => 'Cantidad de Bultos'));
        $cantBultos->addValidator('digits')
                   ->addValidator('stringLength', false, array(1, 11))
                   ->setRequired(True);


        $tipoEnvase = $this->_addform->createElement('select', 'tipoEnvase');
        $tipoEnvase    ->setRequired(True)
                       ->setOrder(1)
                       ->setLabel('Tipo Envase')
                       ->setMultiOptions(array('Envase Flexible' => 'Envase Flexible',
                                                'Caja' => 'Caja',
                                                'Frasco' => 'Frasco',
                                                'Tarro' => 'Tarro',
                                                'Lata de Aluminio' => 'Lata de Aluminio',
                                            ));

        $peso = $this->_addform->createElement('text', 'peso', array('label' => 'Peso'));
        $peso   ->addValidator('float')
                ->addValidator('stringLength', false, array(1, 10))
                ->setRequired(true);

        $unidad = $this->_addform->createElement('select', 'unidad');
        $unidad ->setRequired(True)
                ->setOrder(2)
                ->setLabel('Unidad')
                ->setMultiOptions(array('Toneladas' => 'Toneladas',
                                        'Kilogramos' => 'Kilogramos',
                                        'Gramos' => 'Gramos'
                                        ));

        $nroPaquete = $this->_addform->createElement('text', 'nroPaquete',
                                            array('label' => 'Número de Paquete'));
        $nroPaquete->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 25))
                   ->setRequired(False);


        $marcaYnum = $this->_addform->createElement('text', 'marcaYnum',
                                            array('label' => 'Marca y número'));
        $marcaYnum ->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 100))
                   ->setRequired(False);


        $mercIMCO = $this->_addform->createElement('text', 'mercIMCO',
                                            array('label' => 'Merc. IMCO'));
        $mercIMCO ->addValidator('alnum')
                   ->addValidator('stringLength', false, array(1, 100))
                   ->setRequired(False);

        // Add elements to form:
        $this->_addform->addElement($cantBultos)
             ->addElement($tipoEnvase)
             ->addElement($peso)
             ->addElement($unidad)
             ->addElement($nroPaquete)
             ->addElement($marcaYnum)
             ->addElement($mercIMCO)
             ->addElement('hidden', 'AddCargaTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Ingresar', array('label' => 'Ingresar'));

        return $this->_addform;
    }

}
?>