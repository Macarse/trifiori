<?php
class Importaciones extends Zend_Db_Table_Abstract
{
    protected $_name = 'IMPORTACIONES';
    protected $_sequence = true;
    protected $_rowClass = 'ImportacionesModel';

    public function removeImportacion( $id )
    {
        //$where = $this->getAdapter()->quoteInto('CODIGO_IMP = ?', $id);
        //$this->delete( $where );
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

        $this->update(array('DELETED'    => '1'), $where );
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

    public function addImportacion( $orden, $nameDestinacion, $nameBandera,
                                    $codCanal, $nameGiro, $nameCliente,
                                    $nameCarga, $nameTransporte, $nameMoneda,
                                    $nameOpp, $referencia, $fechaIngreso,
                                    $originalCopia, $desMercaderias,
                                    $valorFactura, $docTransporte,
                                    $ingresoPuerto, $DESnroDoc,
                                    $DESvencimiento, $DESbl, $DESdeclaracion,
                                    $DESpresentado, $DESsalido, $DEScargado,
                                    $DESfactura, $DEsfechaFactura
                                    )
    {

        $fechaIngreso = $this->translateDate($fechaIngreso);
        $ingresoPuerto = $this->translateDate($ingresoPuerto);
        $DESvencimiento = $this->translateDate($DESvencimiento);
        $DESpresentado = $this->translateDate($DESpresentado);
        $DEScargado = $this->translateDate($DEScargado);
        $DESsalido = $this->translateDate($DESsalido);
        $DEsfechaFactura = $this->translateDate($DEsfechaFactura);

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

		//Giro
		$giro = new Giros();
		try
		{
			$codGiro = $giro->getGiroBySeccion($nameGiro);
			if ($codGiro != NULL)
			{
				$codGiro = $codGiro->id();
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}

		//Opp
		$opp = new Opps();
		try
		{
			$codOpp = $opp->getOppByNumero($nameOpp);
			if ($codOpp != NULL)
			{
				$codOpp = $codOpp->id();
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}


	
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

    public function modifyImportacion( $id, $orden, $nameDestinacion, $nameBandera,
                                    $codCanal, $nameGiro, $nameCliente,
                                    $nameCarga, $nameTransporte, $nameMoneda,
                                    $nameOpp, $referencia, $fechaIngreso,
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

        $fechaIngreso = $this->translateDate($fechaIngreso);
        $ingresoPuerto = $this->translateDate($ingresoPuerto);
        $DESvencimiento = $this->translateDate($DESvencimiento);
        $DESpresentado = $this->translateDate($DESpresentado);
        $DEScargado = $this->translateDate($DEScargado);
        $DESsalido = $this->translateDate($DESsalido);
        $DEsfechaFactura = $this->translateDate($DEsfechaFactura);

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

		//Giro
		$giro = new Giros();
		try
		{
			$codGiro = $giro->getGiroBySeccion($nameGiro);
			if ($codGiro != NULL)
			{
				$codGiro = $codGiro->id();
			}
		}
		catch (Zend_Exception $e)
        {
            throw new Exception($e->getMessage());
            return False;
		}

		//Opp
		$opp = new Opps();
		try
		{
			$codOpp = $opp->getOppByNumero($nameOpp);
			if ($codOpp != NULL)
			{
				$codOpp = $codOpp->id();
			}
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
                     ->from(array('imp' => 'IMPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('band' => 'BANDERAS'),
                            'imp.CODIGO_BAN = band.CODIGO_BAN',
                            array('nombre' => 'NOMBRE_BAN'))
                     ->where('imp.FECHAINGRESO_IMP >= ?', $fdesde)
                     ->where('imp.FECHAINGRESO_IMP <= ?', $fhasta)
                     ->group('nombre');
                    break;
                case 'destinacion':
                   $select = $select->select()
                     ->from(array('imp' => 'IMPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('des' => 'DESTINACIONES'),
                            'imp.CODIGO_DES = des.CODIGO_DES',
                            array('nombre' => 'DESCRIPCION_DES'))
                     ->where('imp.FECHAINGRESO_IMP >= ?', $fdesde)
                     ->where('imp.FECHAINGRESO_IMP <= ?', $fhasta)
                     ->group('nombre');
                    break;
                case 'cliente':
                   $select = $select->select()
                     ->from(array('imp' => 'IMPORTACIONES'),
                            array('cantidad' => 'COUNT(*)'))
                     ->join(array('cli' => 'CLIENTES'),
                            'imp.CODIGO_CLI = cli.CODIGO_CLI',
                            array('nombre' => 'NOMBRE_CLI'))
                     ->where('imp.FECHAINGRESO_IMP >= ?', $fdesde)
                     ->where('imp.FECHAINGRESO_IMP <= ?', $fhasta)
                     ->group('nombre');

                    break;

            }
         
            $results = $this->getAdapter()->fetchAll($select);

            return $results;

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
        
        if (!isset($busqueda["sortby"]))
        {
            $mySortby = "ORDEN_IMP";
        }
        else
        {
            switch ($busqueda["sortby"])
            {
                case 'orden':
                    $mySortby = "ORDEN_IMP";
                    break;
                case 'codCanalName':
                    $mySortby = "DESCRIPCION_CAN";
                    break;
                case 'codClienteName':
                    $mySortby = "NOMBRE_CLI";
                    break;
                case 'codOppName':
                    $mySortby = "NUMERO_OPP";
                    break;
                case 'desMercaderias':
                    $mySortby = "DESCMERCADERIA_IMP";
                    break;
                case 'valorFactura':
                    $mySortby = "VALORFACTURA_IMP";
                    break;
                case 'ingresoPuerto':
                    $mySortby = "FECHAINGRESO_IMP";
                    break;
                default:
                    $mySortby = "ORDEN_IMP";
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
            $where = $where . " AND CAST(ORDEN_IMP AS CHAR(100)) LIKE '%" . $orden . "%'";
            
        $select = $this->select();
                
        $select->from($this, array('CODIGO_IMP', 'ORDEN_IMP', 'CODIGO_CAN', 'CODIGO_CLI', 'CODIGO_OPP', 
            'DESCMERCADERIA_IMP', 'VALORFACTURA_IMP', 'INGRESOPUERTO_IMP'));
        $select->setIntegrityCheck(false)
                ->join('CLIENTES', 'CLIENTES.CODIGO_CLI = IMPORTACIONES.CODIGO_CLI', array())
                ->join('TRANSPORTES', 'TRANSPORTES.CODIGO_BUQ = IMPORTACIONES.CODIGO_TRA', array())
                ->join('CANALES', 'CANALES.CODIGO_CAN = IMPORTACIONES.CODIGO_CAN', array())
                ->join('CARGAS', 'CARGAS.CODIGO_CAR = IMPORTACIONES.CODIGO_CAR', array())
                ->join('OPP', 'OPP.CODIGO_OPP = IMPORTACIONES.CODIGO_OPP', array())
                ->where($where)
                ->where("IMPORTACIONES.DELETED LIKE '0'")
                ->order($mySortby . " " . $mySorttype);

        return $select;
    }
}
?>
