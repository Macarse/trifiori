<?php
class Importaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'IMPORTACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'ImportacionesModel';

    public function removeImportacion( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_IMP = ?', $id);
        $this->delete( $where );
    }

    public function getImportacionByID( $id )
    {
        $where = $this->getAdapter()->quoteInto('CODIGO_IMP = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    public function addImportacion( $orden, $codDestinacion, $codBandera,
                                    $codCanal, $codGiro, $codCliente,
                                    $codCarga, $codTransporte, $codMoneda,
                                    $codOpp, $referencia, $fechaIngreso,
                                    $originalCopia, $desMercaderias,
                                    $valorFactura, $docTransporte,
                                    $ingresoPuerto, $DESnroDoc,
                                    $DESvencimiento, $DESbl, $DESdeclaracion,
                                    $DESpresentado, $DESsalido, $DEScargado,
                                    $DESfactura, $DEsfechaFactura
                                    )
    {
        $data = array(  'ORDEN_IMP'             => $orden,
                        'CODIGO_DES'            => $codDestinacion,
                        'CODIGO_BAN'            => $codBandera,
                        'CODIGO_CAN'            => $codCanal,
                        'CODIGO_GIR'            => $codGiro,
                        'CODIGO_CLI'            => $codCliente,
                        'CODIGO_CAR'            => $codCarga,
                        'CODIGO_TRA'            => $codTransporte,
                        'CODIGO_MON'            => $codMoneda,
                        'CODIGO_OPP'            => $codOpp,
                        'REFERENCIA_IMP'        => $referencia,
                        'FECHAINGRESO_IMP'      => $fechaIngreso,
                        'ORIGINALOCOPIA_IMP'    => $originalCopia,
                        'DESCMERCADERIA_IMP'    => $desMercaderias,
                        'VALORFACTURA_IMP'      => $valorFactura,
                        'DOCTRANSPORTE_IMP'     => $docTransporte,
                        'INGRESOPUERTO_IMP'     => $ingresoPuerto,
                        'DES_NRODOC'            => $DESnroDoc,
                        'DES_VENCIMIENTO'       => $DESvencimiento,
                        'DES_B_L'               => $DESbl,
                        'DES_DECLARACION'       => $DESdeclaracion,
                        'DES_PRESENTADO'        => $DESpresentado,
                        'DES_SALIDO'            => $DESsalido,
                        'DES_CARGADO'           => $DEScargado,
                        'DES_FACTURA'           => $DESfactura,
                        'DES_FECHAFACTURA'      => $DEsfechaFactura
                    );

        $this->insert($data);

        return True;
    }

    public function modifyImportacion( $id, $orden, $codDestinacion, $codBandera,
                                    $codCanal, $codGiro, $codCliente,
                                    $codCarga, $codTransporte, $codMoneda,
                                    $codOpp, $referencia, $fechaIngreso,
                                    $originalCopia, $desMercaderias,
                                    $valorFactura, $docTransporte,
                                    $ingresoPuerto, $DESnroDoc,
                                    $DESvencimiento, $DESbl, $DESdeclaracion,
                                    $DESpresentado, $DESsalido, $DEScargado,
                                    $DESfactura, $DEsfechaFactura )
    {
        try
        {
            $where = $this->getAdapter()->quoteInto('CODIGO_IMP = ?', $id);
            $row = $this->fetchRow($where);
        }
        catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
        }

        $this->update(array(
                        'ORDEN_IMP'             => $orden,
                        'CODIGO_DES'            => $codDestinacion,
                        'CODIGO_BAN'            => $codBandera,
                        'CODIGO_CAN'            => $codCanal,
                        'CODIGO_GIR'            => $codGiro,
                        'CODIGO_CLI'            => $codCliente,
                        'CODIGO_CAR'            => $codCarga,
                        'CODIGO_TRA'            => $codTransporte,
                        'CODIGO_MON'            => $codMoneda,
                        'CODIGO_OPP'            => $codOpp,
                        'REFERENCIA_IMP'        => $referencia,
                        'FECHAINGRESO_IMP'      => $fechaIngreso,
                        'ORIGINALOCOPIA_IMP'    => $originalCopia,
                        'DESCMERCADERIA_IMP'    => $desMercaderias,
                        'VALORFACTURA_IMP'      => $valorFactura,
                        'DOCTRANSPORTE_IMP'     => $docTransporte,
                        'INGRESOPUERTO_IMP'     => $ingresoPuerto,
                        'DES_NRODOC'            => $DESnroDoc,
                        'DES_VENCIMIENTO'       => $DESvencimiento,
                        'DES_B_L'               => $DESbl,
                        'DES_DECLARACION'       => $DESdeclaracion,
                        'DES_PRESENTADO'        => $DESpresentado,
                        'DES_SALIDO'            => $DESsalido,
                        'DES_CARGADO'           => $DEScargado,
                        'DES_FACTURA'           => $DESfactura,
                        'DES_FECHAFACTURA'      => $DEsfechaFactura
                        ), $where );

        return True;
    }

}

?>
