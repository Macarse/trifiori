<?php

class ExportacionesModel extends Zend_Db_Table_Row_Abstract
{
    protected $_tableClass = 'ExportacionesTable';

    public function id()
    {
        return $this->CODIGO_EXP;
    }

    public function orden()
    {
        return $this->ORDEN;
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

    public function codBanderaName()
    {
        $row = null;
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


    public function codDestinacionName()
    {
        $row = null;
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

    public function referencia()
    {
        return $this->REFERENCIA;
    }

    public function fechaIngreso()
    {
        return $this->FECHAINGRESO;
    }

    public function desMercaderias()
    {
        return $this->DESCMERCADERIA;
    }

    public function valorFactura()
    {
        return $this->VALORFACTURA;
    }

    public function vencimiento()
    {
        return $this->VENCIMIENTO;
    }

    public function ingresoPuerto()
    {
        return $this->INGRESOPUERTO;
    }

    public function PERnroDoc()
    {
        return $this->PER_NRODOC;
    }

    public function PERpresentado()
    {
        return $this->PER_PRESENTADO;
    }

    public function PERfactura()
    {
        return $this->PER_FACTURA;
    }

    public function PERfechaFactura()
    {
        return $this->PER_FECHAFACTURA;
    }

}

?>