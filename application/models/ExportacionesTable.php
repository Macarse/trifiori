<?php
class Exportaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'EXPORTACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'ExportacionesModel';

    public function removeExportacion( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_EXP = ?', $id);
        $this->delete( $where );
    }

    public function getExportacionByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_EXP = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addExportacion( $orden, $codTransporte, $codCliente,
                                    $codBandera, $codMoneda, $codGiro,
                                    $codDestinacion, $codCarga, $referencia,
                                    $fechaIngreso, $desMercaderias,
                                    $valorFactura, $vencimiento, $ingresoPuerto,
                                    $PERnroDoc, $PERpresentado, $PERfactura,
                                    $PERfechaFactura
                                    )
    {
        $data = array(  'ORDEN'             => $orden,
                        'CODIGO_TRA'        => $codTransporte,
                        'CODIGO_CLI'        => $codCliente,
                        'CODIGO_BAN'        => $codBandera,
                        'CODIGO_MON'        => $codMoneda,
                        'CODIGO_GIR'        => $codGiro,
                        'CODIGO_DES'        => $codDestinacion,
                        'CODIGO_CAR'        => $codCarga,
                        'REFERENCIA'        => $referencia,
                        'FECHAINGRESO'      => $fechaIngreso,
                        'DESCMERCADERIA'    => $desMercaderias,
                        'VALORFACTURA'      => $valorFactura,
                        'VENCIMIENTO'       => $vencimiento,
                        'INGRESOPUERTO'     => $ingresoPuerto,
                        'PER_NRODOC'        => $PERnroDoc,
                        'PER_PRESENTADO'    => $PERpresentado,
                        'PER_FACTURA'       => $PERfactura,
                        'PER_FECHAFACTURA'  => $PERfechaFactura
                    );

        $this->insert($data);

        return True;
    }

    public function modifyExportacion( $id, $orden, $codTransporte, $codCliente,
                                    $codBandera, $codMoneda, $codGiro,
                                    $codDestinacion, $codCarga, $referencia,
                                    $fechaIngreso, $desMercaderias,
                                    $valorFactura, $vencimiento, $ingresoPuerto,
                                    $PERnroDoc, $PERpresentado, $PERfactura,
                                    $PERfechaFactura )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_EXP = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array(
                        'ORDEN'             => $orden,
                        'CODIGO_TRA'        => $codTransporte,
                        'CODIGO_CLI'        => $codCliente,
                        'CODIGO_BAN'        => $codBandera,
                        'CODIGO_MON'        => $codMoneda,
                        'CODIGO_GIR'        => $codGiro,
                        'CODIGO_DES'        => $codDestinacion,
                        'CODIGO_CAR'        => $codCarga,
                        'REFERENCIA'        => $referencia,
                        'FECHAINGRESO'      => $fechaIngreso,
                        'DESCMERCADERIA'    => $desMercaderias,
                        'VALORFACTURA'      => $valorFactura,
                        'VENCIMIENTO'       => $vencimiento,
                        'INGRESOPUERTO'     => $ingresoPuerto,
                        'PER_NRODOC'        => $PERnroDoc,
                        'PER_PRESENTADO'    => $PERpresentado,
                        'PER_FACTURA'       => $PERfactura,
                        'PER_FECHAFACTURA'  => $PERfechaFactura), $where );

        return True;
    }

}

?>
