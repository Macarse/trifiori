<?php

class OppsModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'OppsTable';

    protected function localizeDate($value)
    {
        $lang = Zend_Registry::getInstance()->language->getLocale();

        $date = new Zend_Date($value, 'YYYY-MM-dd');

        if( $lang == 'es' )
        {
            $retVal = $date->get('dd-MM-YYYY');
        }
        else if( $lang == 'en' )
        {
            $retVal = $date->get('MM-dd-YYYY');
        }

        return $retVal;
    }

    public function id()
    {
        return $this->CODIGO_OPP;
    }

    public function name()
    {
        return $this->NUMERO_OPP;
    }

    public function declaracionOk()
    {
        if ($this->DECLARACION_OK_OPP === 's')
            return 'OK';
        else
            return '-';
    }

    public function declaracionOkchar()
    {
        return $this->DECLARACION_OK_OPP;
    }

    public function pedidoDinero()
    {
        return $this->localizeDate($this->PEDIDO_DE_DINERO_OPP);
    }

    public function otrosOpp()
    {
        return $this->OTROS_OPP;
    }

    public function fraccionado()
    {
        return $this->FRACCIONADO_OPP;
    }

    public function estampillas()
    {
        return $this->ESTAMPILLAS_OPP;
    }

    public function impuestosInternos()
    {
        return $this->IMPUESTOS_INTERNOS_OPP;
    }
}

?>