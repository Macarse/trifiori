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

    public function getExportacionByOrden( $id )
    {
        $where = $this->getAdapter()->quoteInto('ORDEN = ?', $id);
        $row = $this->fetchRow( $where );

        return $row;
    }

    protected function translateDate($value)
    {

        if ($value == '')
            return $value;

        $lang = Zend_Registry::getInstance()->language->getLocale();

        if( $lang == 'es' )
        {
            $date = new Zend_Date($value,'dd-MM-YYYY');
        }
        else if( $lang == 'en' )
        {
            $date = new Zend_Date($value,'MM-dd-YYYY');
        }

        $retVal = $date->get('YYYY-MM-dd');

        return $retVal;
    }

    public function addExportacion( $orden, $nameTransporte, $nameCliente,
                                    $nameBandera, $nameMoneda,
                                    $nameDestinacion, $nameCarga, $referencia,
                                    $fechaIngreso, $desMercaderias,
                                    $valorFactura, $vencimiento, $ingresoPuerto,
                                    $PERnroDoc, $PERpresentado, $PERfactura,
                                    $PERfechaFactura
                                    )
    {

    $fechaIngreso = $this->translateDate($fechaIngreso);
    $vencimiento = $this->translateDate($vencimiento);
    $ingresoPuerto = $this->translateDate($ingresoPuerto);
    $PERpresentado = $this->translateDate($PERpresentado);
    $PERfechaFactura = $this->translateDate($PERfechaFactura);


		// Tengo que obtener los codigos
		//clientes
		$clientes = new Clientes();
		try
		{
			$codCliente = $clientes->getClienteByName($nameCliente);
			if ($codCliente != NULL)
			{
				$codCliente = $codCliente->id();
			}
			else
			{
				throw new Exception('No existe el cliente');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Cargas
		$cargas = new Cargas();
		try
		{
			$codCarga = $cargas->getCargaByNroPaq($nameCarga);
			if ($codCarga != NULL)
			{
				$codCarga = $codCarga->id();
			}
			else
			{
				throw new Exception('No existe la carga');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Banderas
		$banderas = new Banderas();
		try
		{
			$codBandera = $banderas->getBanderaByName($nameBandera);
			if ($codBandera != NULL)
			{
				$codBandera = $codBandera->id();
			}
			else
			{
				throw new Exception('No existe la Bandera');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Monedas
		$monedas = new Monedas();
		try
		{
			$codMoneda = $monedas->getMonedaByName($nameMoneda);
			if ($codMoneda != NULL)
			{
				$codMoneda = $codMoneda->id();
			}
			else
			{
				throw new Exception('No existe la Moneda');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Transportes
		$transporte = new Transportes();
		try
		{
			$codTransporte = $transporte->getTransporteByName($nameTransporte);
			if ($codTransporte != NULL)
			{
				$codTransporte = $codTransporte->id();
			}
			else
			{
				throw new Exception('No existe el Transporte');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Destinaciones
		$destinacion = new Destinaciones();
		try
		{
			$codDestinacion = $destinacion->getDestinacionByDesc($nameDestinacion);
			if ($codDestinacion != NULL)
			{
				$codDestinacion = $codDestinacion->id();
			}
			else
			{
				throw new Exception('No existe la destinacion');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}

	
        $data = array(  'ORDEN'             => $orden,
                        'CODIGO_TRA'        => $codTransporte,
                        'CODIGO_CLI'        => $codCliente,
                        'CODIGO_BAN'        => $codBandera,
                        'CODIGO_MON'        => $codMoneda,
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
    
    public function searchExportacion( $busqueda )
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
            $query = $this->select()->where("CAST(ORDEN AS CHAR(100)) LIKE '%" . $orden . "%'");
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
            $query = $this->select()->where("CAST(ORDEN AS CHAR(100)) LIKE '%" . $orden . "%'")
                ->where("CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                        WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }
        else if ($carga == null)
        {
            $query = $this->select()->where("CAST(ORDEN AS CHAR(100)) LIKE '%" . $orden . "%'")
                ->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                        WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')");            
        }
        else
        {
            $query = $this->select()->where("CAST(ORDEN AS CHAR(100)) LIKE '%" . $orden . "%'")
                ->where("CODIGO_CLI IN (SELECT CODIGO_CLI FROM CLIENTES
                        WHERE NOMBRE_CLI LIKE '%" . $cliente . "%')
                        AND CODIGO_CAR IN (SELECT CODIGO_CAR FROM CARGAS
                        WHERE NROPAQUETE_CAR LIKE '%" . $carga . "%')");
        }

        return $query;
    }

    public function modifyExportacion( $id, $orden, $nameTransporte, $nameCliente,
                                    $nameBandera, $nameMoneda,
                                    $nameDestinacion, $nameCarga, $referencia,
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

        $fechaIngreso = $this->translateDate($fechaIngreso);
        $vencimiento = $this->translateDate($vencimiento);
        $ingresoPuerto = $this->translateDate($ingresoPuerto);
        $PERpresentado = $this->translateDate($PERpresentado);
        $PERfechaFactura = $this->translateDate($PERfechaFactura);

		// Tengo que obtener los codigos		
		//clientes
		$clientes = new Clientes();
		try
		{
			$codCliente = $clientes->getClienteByName($nameCliente);
			if ($codCliente != NULL)
			{
				$codCliente = $codCliente->id();
			}
			else
			{
				throw new Exception('No existe el cliente');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Cargas
		$cargas = new Cargas();
		try
		{
			$codCarga = $cargas->getCargaByNroPaq($nameCarga);
			if ($codCarga != NULL)
			{
				$codCarga = $codCarga->id();
			}
			else
			{
				throw new Exception('No existe la carga');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Banderas
		$banderas = new Banderas();
		try
		{
			$codBandera = $banderas->getBanderaByName($nameBandera);
			if ($codBandera != NULL)
			{
				$codBandera = $codBandera->id();
			}
			else
			{
				throw new Exception('No existe la Bandera');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Monedas
		$monedas = new Monedas();
		try
		{
			$codMoneda = $monedas->getMonedaByName($nameMoneda);
			if ($codMoneda != NULL)
			{
				$codMoneda = $codMoneda->id();
			}
			else
			{
				throw new Exception('No existe la Moneda');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}
		
		//Transportes
		$transporte = new Transportes();
		try
		{
			$codTransporte = $transporte->getTransporteByName($nameTransporte);
			if ($codTransporte != NULL)
			{
				$codTransporte = $codTransporte->id();
			}
			else
			{
				throw new Exception('No existe el Transporte');
				return False;
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}

		//Destinaciones
		$destinacion = new Destinaciones();
		try
		{
			$codDestinacion = $destinacion->getDestinacionByDesc($nameDestinacion);
			if ($codDestinacion != NULL)
			{
				$codDestinacion = $codDestinacion->id();
			}
			else
			{
				throw new Exception('No existe la destinacion');
				return False;
			}
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
