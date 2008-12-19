<?php
class admin_LogController extends Trifiori_Admin_Controller_Action
{
    protected $_id;
    protected $_searchform;

    public function indexAction()
    {
        $this->_helper->redirector->gotoUrl('admin/log/listlogs');
    }

    public function listlogsAction()
    {
        $this->view->headTitle($this->language->_("Últimas Modificaciones"));

        unset($this->view->error);

        $this->_searchform = $this->getLogSearchForm();
        if ($this->_searchform->isValid($_GET))
        {
            try
            {
                $where = "MSG like '%ALTERANDO%'";
                $table = new Log();

                if (isset($_GET["consulta"]))
                {
                    if (isset($_GET["sortby"]))
                    {
                        if (isset($_GET["sort"]))
                        {
                            $log = $table->searchLog($_GET["consulta"], $_GET["sortby"], $_GET["sort"]);
                            $mySortType = $_GET["sort"];
                        }
                        else
                        {
                            $log = $table->searchLog($_GET["consulta"], $_GET["sortby"], null);
                            $mySortType = null;
                        }
                        $mySortBy = $_GET["sortby"];
                    }
                    else
                    {
                        $log = $table->searchLog($_GET["consulta"], null, null);
                        $mySortType = null;
                        $mySortBy = null;
                    }
                    Zend_Registry::set('busqueda', $_GET["consulta"]);
                    Zend_Registry::set('sortby', $mySortBy);
                    Zend_Registry::set('sorttype', $mySortType);
                }
                else
                {
                    $log = $table->searchLog("", "", "");

                    Zend_Registry::set('sortby', "");
                    Zend_Registry::set('sorttype', "");
                    Zend_Registry::set('busqueda', "");
                }

                if (isset($_GET["email"]) && $_GET["email"] == TRUE )
                {

                    /*Escribo el body del mail*/
                    $body = '';
                    $resultados = $table->fetchAll($log);
//                     TODO: Ver esto con maxi

                    if (count($resultados) > 1)
                    {
                        foreach($resultados as $logRow)
                        {
                            $body .= $this->language->_('N&uacute;mero de identificaci&oacute;n: ');
                            $body .= $logRow->id() . '<br />';
                            $body .= $this->language->_('Nivel de prioridad: ');
                            $body .= $logRow->nivel() . '<br />';
                            $body .= $this->language->_('Mensaje: ');
                            $body .= $logRow->msg() . '<br />';
                            $body .= '<br />';
                        }
                    }
                    else
                    {
                        $body = $this->language->_('No se encontraron logs');
                    }

                    /*Lo envio*/
                    try
                    {
                        $config = Zend_Registry::getInstance()->configuration;

                        $mail = new Zend_Mail();
                        $mail->setBodyHtml($body);
                        $mail->setFrom($config->gmail->email, 'Trifiori Web');
                        $mail->addTo($config->admin->email, $config->admin->name);
                        $mail->setSubject('Trifiori Web');
                        $mail->send(Zend_Registry::getInstance()->mailTransport);
                    }
                    catch (Exception $error)
                    {
                        $this->view->error = $this->language->_("Error al intentar enviar e-mail.");
                    }

                    $log = $table->searchLog($_GET["consulta"], $mySortBy, $mySortType);
                }

                $paginator = new Zend_Paginator(new Trifiori_Paginator_Adapter_DbTable($log, $table));

                if (isset($_GET["page"]))
                {
                    $paginator->setCurrentPageNumber($_GET["page"]);
                }
                else
                {
                    $paginator->setCurrentPageNumber(1);
                }
                $paginator->setItemCountPerPage(20);
                $this->view->paginator = $paginator;
            }
            catch (Zend_Exception $error)
            {
                $this->view->error = $error;
            }
        }
        $this->view->logSearchForm = $this->getLogSearchForm();
    }

    private function getLogSearchForm()
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

        $logs = $this->_searchform->createElement('text', 'consulta',
                array('label' => $this->language->_('Mensaje')));
        $logs   ->addValidator($alnumWithWS)
                ->addValidator('stringLength', false, array(1, 100));


        $email = $this->_searchform->createElement('checkbox', 'email',
                array('label' => $this->language->_('Enviar resultados por email')));

        // Add elements to form:
                $this->_searchform  ->addElement($logs)
                                    ->addElement($email)
                                    ->addElement('hidden', 'SearchLogTrack', array('values' => 'logPost'))
                                    ->addElement('submit', 'Buscar', array('label' => $this->language->_('Buscar')));

        return $this->_searchform;
    }
}
