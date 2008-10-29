<?php

class OppsModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'OppsTable';

    public function id()
    {
        return $this->CODIGO_OPP;
    }

    public function declaracionOk()
    {
        if ($this->DECLARACION_OK_OPP === 's')
            return 'Sí';
        else
            return 'No';
    }

    public function declaracionOkchar()
    {
        return $this->DECLARACION_OK_OPP;
    }

    public function pedidoDinero()
    {
        return $this->PEDIDO_DE_DINERO_OPP;
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