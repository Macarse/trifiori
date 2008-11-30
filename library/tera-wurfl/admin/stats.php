<?php
require_once('../tera_wurfl_config.php');
require_once(WURFL_PARSER_FILE);
// connect to DB
$dbcon = mysql_connect(DB_HOST,DB_USER,DB_PASS) or die("Could not connect to MySQL Server (".DB_HOST."): ".mysql_error());
// select DB
mysql_select_db(DB_SCHEMA,$dbcon) or die("Connected to MySQL Server but could not select database (".DB_SCHEMA."): ".mysql_error($dbcon));
// check tables
$tablesres = mysql_query("SHOW TABLES");
$required_tables = array(DB_DEVICE_TABLE,DB_PATCH_TABLE,DB_HYBRID_TABLE);
$tables = array();
while($table = mysql_fetch_row($tablesres))$tables[]=$table[0];
foreach($required_tables as $req_table){
	if(!in_array($req_table,$tables)){
		echo "Required table '$req_table' was missing in database (".print_r($tables,true)."), creating...<br />";
		emptyWurflDevTable($req_table);
	}
}
function showBool($var){
	if($var === true)return("true");
	if($var === false)return("false");
	return($var);
}
function showLogLevel($num){
	$log_arr = array("LOG_EMERG","LOG_ALERT","LOG_CRIT","LOG_ERR","LOG_WARNING","LOG_NOTICE","LOG_INFO","LOG_DEBUG");
	return($log_arr[$num]);
}
function tableStats($table){
	$stats = array();
	$fields = array();
	$fieldnames = array();
	$fieldsres = mysql_query("SHOW COLUMNS FROM ".$table);
	while($row = mysql_fetch_assoc($fieldsres)){
		$fields[] = 'CHAR_LENGTH(`'.$row['Field'].'`)';
		$fieldnames[]=$row['Field'];
	}
	mysql_free_result($fieldsres);
	$bytesizequery = "SUM(".implode('+',$fields).") AS `bytesize`";
	$query = "SELECT COUNT(*) AS `rowcount`, $bytesizequery FROM `$table`";
	$res = mysql_query($query);
	$stats['rows'] = mysql_result($res,0,'rowcount');
	$stats['bytesize'] = mysql_result($res,0,'bytesize');
	mysql_free_result($res);
	if(in_array("actual_device_root",$fieldnames)){
		$res = mysql_query("SELECT COUNT(*) AS `devcount` FROM `$table` WHERE actual_device_root=1");
		$stats['actual_devices'] = mysql_result($res,0,'devcount');
		mysql_free_result($res);
	}
	return($stats);
}
function filesize_format($bytes){
  $bytes=(float)$bytes;
  if ($bytes<1024){
  $numero=number_format($bytes, 0, '.', ',')." Bytes";
  return $numero;
  }
  if ($bytes<1048576){
     $numero=number_format($bytes/1024, 2, '.', ',')." KB";
  return $numero;
  }
  if ($bytes>=1048576){
     $numero=number_format($bytes/1048576, 2, '.', ',')." MB";
  return $numero;
  }
}
$devicestats = tableStats(DB_DEVICE_TABLE);
$patchstats = tableStats(DB_PATCH_TABLE);
$hybridstats = tableStats(DB_HYBRID_TABLE);
$cachestats = tableStats(DB_CACHE_TABLE);

if(!is_readable(WURFL_LOG_FILE)){
	$lastloglines = "Empty";
}else{
	$logarr = file(WURFL_LOG_FILE);
	$loglines = 30;
	$end = count($logarr)-1;
	$lastloglines = '';
	for($i=$end;$i>=($end-$loglines);$i--){
		$lastloglines .= htmlspecialchars($logarr[$i])."<br />";
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Tera-WURFL Administration</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="800">
	<tr><td>
<div align="center" class="titlediv">
	<p>		Tera-WURFL Administration<br />
		<span class="version">Version <?php echo "$branch $version"; ?></span></p>
</div>
</td></tr><tr><td>
		<h3><br />
			<a href="index.php">&lt;&lt; Back	to main page </a></h3>
		<table width="800" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<th scope="col">Database Table </th>
			<th scope="col">Statistics</th>
		</tr>
		<tr>
			<td width="145" class="darkrow">DB_DEVICE_TABLE<br />
					<span class="setting"><?=DB_DEVICE_TABLE?></span></td>
			<td width="655" class="darkrow">Rows: <span class="setting"><?=$devicestats['rows']?></span><br />
				Actual Devices: <span class="setting"><?=$devicestats['actual_devices']?></span> <br />
				Table Size: <span class="setting"><?=filesize_format($devicestats['bytesize'])?></span><br />
				Purpose:<br />
				<span class="setting">The device table holds the data from the WURFL file, whether it be local, remote or remote CVS, the whenever a new WURFL is loaded, it is loaded into this table first. The data in this table is never modified, and if patching is disabled, this table is queried for the device in question. </span></td>
		</tr>
		<tr>
			<td class="lightrow">DB_PATCH_TABLE		<br />
				<span class="setting"><?=DB_PATCH_TABLE?></span></td>
			<td class="lightrow">Rows: <span class="setting"><?=$patchstats['rows']?></span><br />
			Actual Devices: <span class="setting"><?=$patchstats['actual_devices']?></span>
				<br />
				Table Size: <span class="setting"><?=filesize_format($patchstats['bytesize'])?></span><br />
				Purpose:<br />
				<span class="setting">The patch table holds the data from the patch file, much like the device table, this data is not modified unless a new patch is loaded. When patching is enabled, this data is merged with the device table to create the hybrid table. </span></td>
		</tr>
		<tr>
			<td class="darkrow">DB_HYBRID_TABLE		<span class="setting"><br />
				<?=DB_HYBRID_TABLE?></span></td>
			<td class="darkrow">Rows: <span class="setting"><?=$hybridstats['rows']?></span><br />
				Actual Devices: <span class="setting"><?=$hybridstats['actual_devices']?></span> <br />
				Table Size: <span class="setting"><?=filesize_format($hybridstats['bytesize'])?></span><br />
				Purpose:<br />
				<span class="setting">The hybrid table is a combination of the device table and the patch table. <strong>People ask about this all the time so pay attention :) </strong>When you apply a patch to the main WURFL database, Tera-WURFL has to resolve conflicts between the main WURFL (the device table) and the patch table. Let's say you know that a Motorola RAZR has MP3 capability but the WURFL says it doesn't. You would figure out what WURFL DEVICE_ID the RAZR has, and you would add an entry in your patch file with that capability set correctly. Now, when you apply the patch, the script realizes that the RAZR exists in both the device table and the patch table - so it takes all the capabilities from the device table and puts them in the hybrid table, then it takes all your changes in the patch table and overwrites the existing ones in the hybrid table, effectively merging the conflicting items. Whenever your change the device or patch data you force Tera-WURFL to rebuild the hybrid table and you are presented with some stats about how many devices from your patch file are <strong>added</strong> and how many are <strong>merged</strong>. If you have patching enabled, this table is queried for the device in question. </span></td>
		</tr>
		<tr>
			<td class="lightrow">DB_CACHE_TABLE		<br />
				<span class="setting"><?=DB_CACHE_TABLE?></span></td>
			<td class="lightrow">Rows: <span class="setting"><?=$cachestats['rows']?></span><br />
				Table Size: <span class="setting"><?=filesize_format($cachestats['bytesize'])?></span><br />
				Purpose:<br />
				<span class="setting">The cache table stores unique user agents and the complete capabilities and device root that were determined the last time it was processed. <strong>Any time the patch or devices table is updated, the cache table is cleared</strong> since the underlying data may have been modified - leaving the cache tainted. </span></td>
		</tr>
	</table>
	<p><br/>
			<br/>
	</p>
	<table width="800" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th scope="col">Tera-WURFL Settings</th>
		</tr>
		<tr>
			<td class="lightrow"><p>-- Database options --<br/>
				DB_HOST <span class="setting">
	<?=DB_HOST?>
	</span>,	database server hostname or IP<br />
				DB_USER <span class="setting">
	<?=DB_USER?>
	</span>,	database username (needs SELECT,INSERT,DELETE,DROP,CREATE)<br />
				DB_PASS <span class="setting">********</span>, database password<br />
				DB_SCHEMA <span class="setting">
	<?=DB_SCHEMA?>
	</span>, database schema (database name)<br />
				DB_TYPE <span class="setting">
	<?=DB_TYPE?>
	</span>, database table type (MyISAM, InnoDB, HEAP, etc...);<br />
				DB_DEVICE_TABLE <span class="setting">
	<?=DB_DEVICE_TABLE?>
	</span>, database table name for the WURFL<br />
				DB_PATCH_TABLE <span class="setting">
	<?=DB_PATCH_TABLE?>
	</span>, database table name for the patch<br />
				DB_HYBRID_TABLE <span class="setting"><?=DB_HYBRID_TABLE?></span>, database table name for the Hybrid of the WURFL and the patch<br />
	DB_CACHE_TABLE <span class="setting"><?=DB_CACHE_TABLE?></span>, database table name for the cache <br />
					DB_MULTI_INSERT <span class="setting">
						<?=showBool(DB_MULTI_INSERT)?>
							</span>, use multiple inserts to speed DB updating<br />
					DB_MAX_INSERTS <span class="setting">
						<?=DB_MAX_INSERTS?>
							</span>, number of inserts per query<br />
					DB_EMPTY_METHOD <span class="setting">
						<?=DB_EMPTY_METHOD?>
							</span>, either DROP_CREATE or EMPTY; method for emptying tables.<br />
					DB_TEMP_EXT <span class="setting">
						<?=DB_TEMP_EXT?>
							</span>, extension that will be used for temporary tables like &quot;mytablename_TEMP&quot;<br />
							<br />
					-- General options --<br />
					WURFL_DL_URL <span class="setting">
						<?=WURFL_DL_URL?>
							</span>, full URL to the current WURFL<br />
					WURFL_CVS_URL <span class="setting">
						<?=WURFL_CVS_URL?>
							</span>, full URL to development (CVS) WURFL<br />
					WURFL_CONFIG <span class="setting">
						<?=showBool(WURFL_CONFIG)?>
							</span>,lets other file know the config is loaded<br />
					DATADIR <span class="setting">
						<?=DATADIR?>
							</span>,	where all data is stored (wurfl.xml, temp files, logs)<br />
					IMAGE_CHECKING <span class="setting">
						<?=showBool(IMAGE_CHECKING)?>
							</span>,checks the IMAGE_DIR for an image that matches the device<br />
					IMAGE_DIR <span class="setting">
						<?=IMAGE_DIR?>
							</span>, relative path to the device images with trailing slash<br />
							WURFL_CACHE_ENABLE <span class="setting"><?=showBool(WURFL_CACHE_ENABLE)?></span>, enables or disables the cache <br />
					WURFL_PATCH_ENABLE <span class="setting">
						<?=showBool(WURFL_PATCH_ENABLE)?>
						</span>, enables or disables the patch<br />
					WURFL_PATCH_FILE <span class="setting">
						<?=WURFL_PATCH_FILE?>
						</span>, optional patch file for WURFL<br />
					WURFL_PARSER_FILE <span class="setting">
						<?=WURFL_PARSER_FILE?>
						</span>, path and filename of wurfl_parser.php<br />
					WURFL_CLASS_FILE <span class="setting">
						<?=WURFL_CLASS_FILE?>
						</span>, path and filename of wurfl_class.php<br />
					WURFL_FILE <span class="setting">
						<?=WURFL_FILE?>
						</span>, path and filename of wurfl.xml<br />
					WURFL_LOG_FILE <span class="setting">
						<?=WURFL_LOG_FILE?>
						</span>, defines full path and filename for logging<br />
					LOG_LEVEL <span class="setting">
						<?=showLogLevel(LOG_LEVEL)?>
							</span>, desired logging level. Use the same constants as for PHP logging</p>
				</td>
		</tr>
	</table>
	<p>&nbsp;</p>
	<table width="800" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th scope="col">Log File (last 30 lines) </th>
		</tr>
		<tr>
			<td class="lightrow"><div class="logfile"><?=$lastloglines?></div>
				<br/></td>
		</tr>
	</table>	<p>&nbsp; </p></td>
</tr></table>
</body>
</html>
