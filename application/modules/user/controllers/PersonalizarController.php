<?php
class user_PersonalizarController extends Trifiori_User_Controller_Action
{
    protected $_modform;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('user/personalizar/modpersonalizar');
    }

    public function modpersonalizarAction()
    {
        $this->view->headTitle($this->language->_("Personalizar"));

        /*Errors from the past are deleted*/
        unset($this->view->error);

        /* Levanto el id del usuario */
        $this->user = Zend_Auth::getInstance()->getIdentity()->USUARIO_USU;
        $userTable = new Users();
        $row = $userTable->getUserByName( $this->user );

        /*Si el ID no corresponde con la db, hacerlo volver a la pagina principal*/
        if (($this->view->personalizarModForm = $this->getPersonalizarModForm($row)) == null)
        {
            $this->_helper->redirector->gotoUrl('user/main-page');
        }

        /*Si viene algo por post, validarlo.*/
        if ($this->getRequest()->isPost())
        {
            if (isset($_POST['ModPersonalizarTrack']))
            {
                if ($this->_modform->isValid($_POST))
                {
                    // process user
                    $values = $this->_modform->getValues();

                    try
                    {
                        $userTable->modifyUser( $row->id(),
                                                $row->name(),
                                                $row->user(),
                                                "",
                                                $values['lang'],
                                                $values['css']
                                                );
                    }
                    catch (Zend_Exception $error)
                    {
                    $this->view->error = $error;
                    }

                    /*TODO: Esto acá está mal. Si hay un error en la db nunca te enterás*/
                    /*Se actualizó, volver a mostrar lista de users*/
                    $this->_helper->redirector->gotoUrl('user/personalizar/modpersonalizar');
                }
            }
        }
    }

    private function getPersonalizarModForm( $row )
    {
        /*Esto hace una especie de singleton del form a nivel controlador*/
        if (null !== $this->_modform)
        {
            return $this->_modform;
        }

        if ( $row === null )
        {
            /*TODO: Hardcodeado ok?*/
            $this->_helper->redirector->gotoUrl('user/main-page');
        }

        $this->_modform = new Zend_Form();
        $this->_modform->setAction($this->_baseUrl)
						->setName('form')
						->setMethod('post');

       /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $cssTable = new Css();
        $cssOptions =  $cssTable->getCssArray();

        $css = $this->_modform->createElement('select', 'css');
        $css    ->setValue($row->codCss() )
                ->setRequired(true)
                ->setOrder(1)
                ->setLabel('*' . $this->language->_('Css'))
                ->setMultiOptions($cssOptions);


        /*TODO: Si la db está muerta devuelve NULL.
        Ver qué hacer en ese caso.*/
        $LangTable = new Lang();
        $langOptions =  $LangTable->getLangArray();

        $lang = $this->_modform->createElement('select', 'lang');
        $lang   ->setValue($row->langNum() )
                ->setRequired(true)
                ->setOrder(2)
                ->setLabel($this->language->_('*' . 'Idioma'))
                ->setMultiOptions($langOptions);


        // Add elements to form:
        $this->_modform ->addElement($css)
                        ->addElement($lang)
             ->addElement('hidden', 'ModPersonalizarTrack', array('values' => 'logPost'))
             ->addElement('submit', 'Modificar', array('label' => $this->language->_('Ingresar')));

        return $this->_modform;
    }

}
?>
