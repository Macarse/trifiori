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
    
    public function getImportacionByOrden( $id )
    {
        $where = $this->getAdapter()->quoteInto('ORDEN_IMP = ?', $id);
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

    public function searchImportacion( $busqueda )
    {
        if (!isset($busqueda["searchCliente"]))
        {
            $busqueda["searchCliente"] = "";
        }
            
        if (!isset($busqueda["searchOrden"]))
        {
            $busqueda["searchOrden"] = "";
        }
            
        if (!isset($busqueda["searchCarga"]))
        {
            $busqueda["searchCarga"] = "";
        }
            
        $cliente = mysql_real_escape_string($busqueda["searchCliente"]);
        $orden = mysql_real_escape_string($busqueda["searchOrden"]);
        $carga = mysql_real_escape_string($busqueda["searchCarga"]);
            
    
        if ($cliente == null && $orden == null && $carga == null)
        {
            $query = $this->select();
        }
        else if ($cliente == null && $orden == null)
        {
            $query = $this->select()->where("CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                    WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }
        else if ($cliente == null && $carga == null)
        {
            $query = $this->select()->where("CAST(ORDEN_IMP AS CHAR(100)) LIKE '%" . $orden . "%'");
        }
        else if ($orden == null && $carga == null)
        {
            $query = $this->select()->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                    WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')");
        }
        else if ($orden == null)
        {
            $query = $this->select()->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                    WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')
                    AND CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                    WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }
        else if ($cliente == null)
        {
            $query = $this->select()->where("CAST(ORDEN_IMP AS CHAR(100)) LIKE '%" . $orden . "%'")
                    ->where("CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                    WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }
        else if ($carga == null)
        {
            $query = $this->select()->where("CAST(ORDEN_IMP AS CHAR(100)) LIKE '%" . $orden . "%'")
                    ->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                    WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')");            
        }
        else
        {
            $query = $this->select()->where("CAST(ORDEN_IMP AS CHAR(100)) LIKE '%" . $orden . "%'")
                    ->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                    WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')
                    AND CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                    WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }
    
        return $query;
    }
}
?>
