<?php

class ImportacionesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'ImportacionesTable';

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
        return $this->CODIGO_IMP;
    }

    public function orden()
    {
        return $this->ORDEN_IMP;
    }

    public function codDestinacionName()
    {
        $row = NULL;
        $destinacionesTable = new Destinaciones();

        $row = $destinacionesTable->getDestinacionByID($this->CODIGO_DES);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codDestinacion()
    {
        return $this->CODIGO_DES;
    }

    public function codBanderaName()
    {
        $row = NULL;
        $banderasTable = new Banderas();

        $row = $banderasTable->getBanderaByID($this->CODIGO_BAN);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codBandera()
    {
        return $this->CODIGO_BAN;
    }

    public function codCanalName()
    {
        $row = NULL;
        $CanalesTable = new Canales();

        $row = $CanalesTable->getCanalByID($this->CODIGO_CAN);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codCanal()
    {
        return $this->CODIGO_CAN;
    }

    public function codGiroName()
    {
        $row = null;
        $girosTable = new Giros();

        $row = $girosTable->getGiroByID($this->CODIGO_GIR);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codGiro()
    {
        return $this->CODIGO_GIR;
    }

    public function codClienteName()
    {
        $row = null;
        $clientesTable = new Clientes();

        $row = $clientesTable->getClienteByID($this->CODIGO_CLI);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codCliente()
    {
        return $this->CODIGO_CLI;
    }

    public function codCargaName()
    {
        $row = null;
        $cargasTable = new Cargas();

        $row = $cargasTable->getCargaByID($this->CODIGO_CAR);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->nroPaquete();
    }

    public function codCarga()
    {
        return $this->CODIGO_CAR;
    }

    public function codTransporteName()
    {
        $row = null;
        $transportesTable = new Transportes();

        $row = $transportesTable->getTransporteByID($this->CODIGO_TRA);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codTransporte()
    {
        return $this->CODIGO_TRA;
    }

    public function codMonedaName()
    {
        $row = null;
        $monedasTable = new Monedas();

        $row = $monedasTable->getMonedaByID($this->CODIGO_MON);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Name';
        else
            return $row->name();
    }

    public function codMoneda()
    {
        return $this->CODIGO_MON;
    }

    public function codOppName()
    {
        $row = NULL;
        $oppsTable = new Opps();

        $row = $oppsTable->getOppByID($this->CODIGO_OPP);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
            return 'No Opp';
        else
            return $row->pedidoDinero();
    }
    
    public function codOppNum()
    {
        $row = NULL;
        $oppsTable = new Opps();

        $row = $oppsTable->getOppByID($this->CODIGO_OPP);

        /*TODO: esto es por inconsistencias en la DB :(*/
        if ($row == NULL)
                return 'No Opp';
        else
            return $row->name();
    }
    
    public function codOpp()
    {
        return $this->CODIGO_OPP;
    }

    public function referencia()
    {
        return $this->REFERENCIA_IMP;
    }

    public function fechaIngreso()
    {
        return $this->localizeDate($this->FECHAINGRESO_IMP);
    }

    public function originalCopia()
    {
        return $this->ORIGINALOCOPIA_IMP;
    }

    public function desMercaderias()
    {
        return $this->DESCMERCADERIA_IMP;
    }

    public function valorFactura()
    {
        return $this->VALORFACTURA_IMP;
    }

    public function docTransporte()
    {
        return $this->DOCTRANSPORTE_IMP;
    }

    public function ingresoPuerto()
    {
        return $this->localizeDate($this->INGRESOPUERTO_IMP);
    }

    public function DESnroDoc()
    {
        return $this->DES_NRODOC;
    }

    public function DESvencimiento()
    {
        return $this->localizeDate($this->DES_VENCIMIENTO);
    }

    public function DESbl()
    {
        return $this->DES_B_L;
    }

    public function DESdeclaracion()
    {
        return $this->DES_DECLARACION;
    }

    public function DESpresentado()
    {
        return $this->localizeDate($this->DES_PRESENTADO);
    }

    public function DESsalido()
    {
        return $this->localizeDate($this->DES_SALIDO);
    }

    public function DEScargado()
    {
        return $this->localizeDate($this->DES_CARGADO);
    }

    public function DESfactura()
    {
        return $this->DES_FACTURA;
    }

    public function DEsfechaFactura()
    {
        return $this->localizeDate($this->DES_FECHAFACTURA);
    }

}

?>