<?php
class Exportaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'EXPORTACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'ExportacionesModel';

    public function removeExportacion( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_EXP = ?', $id);
        //$this->delete( $where );
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

        $this->update(array('DELETED'    => '1'), $where );
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

        $row = $this->getExportacionByOrden( $orden );
        if (count($row))
        {
            $this->updateExportacion ($row->id(), $orden, $nameTransporte, $nameCliente,
                                    $nameBandera, $nameMoneda,
                                    $nameDestinacion, $nameCarga, $referencia,
                                    $fechaIngreso, $desMercaderias,
                                    $valorFactura, $vencimiento, $ingresoPuerto,
                                    $PERnroDoc, $PERpresentado, $PERfactura,
                                    $PERfechaFactura);
        }
        else
        {
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

    }

    public function getEstadisticas( $type , $from, $to)
    {
           //$db = Zend_Db::factory();
           $registry = Zend_Registry::getInstance();
           $select = $registry->database;

            $fdesde = $this->translateDate($from);
            $fhasta = $this->translateDate($to);

            switch($type)
            {
                case 'pais':
                   $select = $select->select()
                     ->from(array('exp' => 'EXPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('band' => 'BANDERAS'),
                            'exp.CODIGO_BAN = band.CODIGO_BAN',
                            array('nombre' => 'NOMBRE_BAN'))
                     ->where('exp.FECHAINGRESO >= ?', $fdesde)
                     ->where('exp.FECHAINGRESO <= ?', $fhasta)
                     ->group('nombre');
                    break;
                case 'destinacion':
                   $select = $select->select()
                     ->from(array('exp' => 'EXPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('des' => 'DESTINACIONES'),
                            'exp.CODIGO_DES = des.CODIGO_DES',
                            array('nombre' => 'DESCRIPCION_DES'))
                     ->where('exp.FECHAINGRESO >= ?', $fdesde)
                     ->where('exp.FECHAINGRESO <= ?', $fhasta)
                     ->group('nombre');
                    break;
                case 'cliente':
                   $select = $select->select()
                     ->from(array('exp' => 'EXPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('cli' => 'CLIENTES'),
                            'exp.CODIGO_CLI = cli.CODIGO_CLI',
                            array('nombre' => 'NOMBRE_CLI'))
                     ->where('exp.FECHAINGRESO >= ?', $fdesde)
                     ->where('exp.FECHAINGRESO <= ?', $fhasta)
                     ->group('nombre');

                    break;

            }
         
            $results = $this->getAdapter()->fetchAll($select);

            return $results;

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
        
        if (!isset($busqueda["sortby"]))
        {
            $mySortby = "ORDEN";
        }
        else
        {
            switch ($busqueda["sortby"])
            {
                case 'orden':
                    $mySortby = "ORDEN";
                    break;
                case 'trans':
                    $mySortby = "NOMBRE_BUQ";
                    break;
                case 'cliente':
                    $mySortby = "NOMBRE_CLI";
                    break;
                case 'destinacion':
                    $mySortby = "DESCRIPCION_DES";
                    break;
                case 'carga':
                    $mySortby = "NROPAQUETE_CAR";
                    break;
                case 'feching':
                    $mySortby = "FECHAINGRESO";
                    break;
                case 'descmer':
                    $mySortby = "DESCMERCADERIA";
                    break;
                default:
                    $mySortby = "ORDEN";
                    break;
            }
        }
        
        if (isset($busqueda["sort"]))
        {
            if ($busqueda["sort"] == "desc")
                $mySorttype = "DESC";
            else
                $mySorttype = "ASC";
        }
        else
        {
            $mySorttype = "ASC";
        }
            
        $cliente = mysql_real_escape_string($busqueda["searchCliente"]);
        $orden = mysql_real_escape_string($busqueda["searchOrden"]);
        $carga = mysql_real_escape_string($busqueda["searchCarga"]);
        
        $where = "CLIENTES.NOMBRE_CLI LIKE '%" . $cliente . "%' AND CARGAS.NROPAQUETE_CAR LIKE '%"
            . $carga . "%'";
        
        if ($orden != null)
            $where = $where . " AND CAST(ORDEN AS CHAR(100)) LIKE '%" . $orden . "%'";
            
        $select = $this->select();
                
        $select->from($this, array('CODIGO_EXP', 'ORDEN', 'CODIGO_TRA', 'CODIGO_CLI', 'CODIGO_DES', 'CODIGO_CAR',
            'FECHAINGRESO', 'DESCMERCADERIA'));
        $select->setIntegrityCheck(false)
                ->join('CLIENTES', 'CLIENTES.CODIGO_CLI = EXPORTACIONES.CODIGO_CLI', array())
                ->join('TRANSPORTES', 'TRANSPORTES.CODIGO_BUQ = EXPORTACIONES.CODIGO_TRA', array())
                ->join('DESTINACIONES', 'DESTINACIONES.CODIGO_DES = EXPORTACIONES.CODIGO_DES', array())
                ->join('CARGAS', 'CARGAS.CODIGO_CAR = EXPORTACIONES.CODIGO_CAR', array())
                ->where($where)
                ->where("EXPORTACIONES.DELETED LIKE '0'")
                ->order($mySortby . " " . $mySorttype);

        return $select;
    }

    public function updateExportacion( $id, $orden, $nameTransporte, $nameCliente,
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
                        'PER_FECHAFACTURA'  => $PERfechaFactura,
                        'DELETED'           => '0'), $where );

        return True;
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
