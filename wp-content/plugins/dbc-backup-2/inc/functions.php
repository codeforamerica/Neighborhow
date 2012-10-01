<?php

function dbcbackup_structure($table, $fp)
{	
	$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
	if (!$is_safe_mode) set_time_limit(600);
	$res ='';
	$res .= "\n";
	$res .= "--\n";
	$res .= "-- Table structure of table ".dbcbackup_backquote($table)."\n";
	$res .= "--\n";
	$res .= "\n";

	if($sql = mysql_query("SHOW CREATE TABLE ".dbcbackup_backquote($table)))
	{
		$res .= "DROP TABLE IF EXISTS ".dbcbackup_backquote($table).";\n";
		$row = mysql_fetch_array($sql);
		$create_table = $row[1];
		unset($row);
		$create_table = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_table);
		$create_table = preg_replace("/ENGINE\s?=/", "TYPE=", $create_table);
		$create_table .= ";\n";
		$res .= $create_table;
		mysql_free_result($sql);
		$status = true;
	}
	else 
	{
		$tmp = mysql_error();
		$res .= "--".$tmp;
		$status = false;
	}

	dbcbackup_write($fp, $res);
	return ($status);
}

/* ------------------ */

function dbcbackup_data($table, $fp)
{
	$is_safe_mode = ini_get('safe_mode') == '1' ? 1 : 0;
	if (!$is_safe_mode) set_time_limit(600);
	$res ='';
	$res .= "\n";
	$res .= "--\n";
	$res .= "-- Dumping data for table ".dbcbackup_backquote($table)."\n";
	$res .= "--\n";
	$res .= "\n";
	dbcbackup_write($fp, $res);
		
	if($sql = mysql_query("SELECT * FROM ".dbcbackup_backquote($table)))
	{
		list($numfields, $fields_meta) = dbcbackup_fields($sql);
		//$res = "LOCK TABLES ".dbcbackup_backquote($table)." WRITE;\n";
		$res = '';
		dbcbackup_write($fp, $res);
		$search       = array("\x00", "\x0a", "\x0d", "\x1a"); //\x08\\x09, not required | Taken from phpMyAdmin.
		$replace      = array('\0', '\n', '\r', '\Z');
		while ($row = mysql_fetch_array($sql))
		{
			$res = "INSERT INTO ".dbcbackup_backquote($table)." VALUES (";	
			$fieldcounter = -1;
			$firstfield = 1;
			while (++$fieldcounter < $numfields)
			{	
				if (!$firstfield){
					$res .= ', ';
				}else{
					$firstfield = 0;
				}
              	if (!isset($row[$fieldcounter]) || is_null($row[$fieldcounter])) {
                    $res     .= 'NULL';
                } elseif ($fields_meta[$fieldcounter]->numeric && $fields_meta[$fieldcounter]->type != 'timestamp'
                        && ! $fields_meta[$fieldcounter]->blob) {
                    $res .= $row[$fieldcounter];
                } elseif (stristr($field_flags[$j], 'BINARY')
                        && isset($GLOBALS['hexforbinary'])
                        && $fields_meta[$fieldcounter]->type != 'datetime'
                        && $fields_meta[$fieldcounter]->type != 'date'
                        && $fields_meta[$fieldcounter]->type != 'time'
                        && $fields_meta[$fieldcounter]->type != 'timestamp'
                       ) {
                    // empty blobs need to be different, but '0' is also empty :-(
                    if (empty($row[$fieldcounter]) && $row[$fieldcounter] != '0') {
                        $res .= '\'\'';
                    } else {
                        $res .= '0x' . bin2hex($row[$fieldcounter]);
                    }
                // something else -> treat as a string
                } else {
                    $res .= "'".str_replace($search, $replace, dbcbackup_addslashes($row["$fieldcounter"]))."'";
                } // end if
			}
			$res .= ");\n";	
			dbcbackup_write($fp, $res);	
		}
		//$res = "UNLOCK TABLES;\n";
		$res = '';
		mysql_free_result($sql);
		$status = true;
	}
	else
	{
		$tmp = mysql_error();
		$res .= "--".$tmp;
		$status = false;
	}
	dbcbackup_write($fp, $res);
	return $status;
}


function dbcbackup_backquote($a_name)
{
	//Add backqouotes to tables and db-names in SQL queries. Taken from phpMyAdmin.
	if (!empty($a_name) && $a_name != '*') 
	{
        if (is_array($a_name)) 
		{
			$result = array();
			reset($a_name);
			while(list($key, $val) = each($a_name)) 
			{
			  $result[$key] = '`' . $val . '`';
			}
			 return $result;
        } 
		else 
		{
			return '`' . $a_name . '`';
        }
    } 
	else 
	{
        return $a_name;
    }
}

/* ------------------ */

function dbcbackup_header()
{
	$header  = "-- Database Cron Backup \n";
	$header .= "-- Version 1.0 for Wordpress 2.5+ \n";
	$header .= "-- Copyright Chris T aka Tefra http://www.t3-design.com \n";
	$header .= "-- Generated: ".date('l dS \of F Y h:i A', time() + (get_option('gmt_offset') * 3600))." \n";
	$header .= "-- MySQL Server: ".mysql_get_host_info()."\n";
	$header .= "-- MySQL Server version: ".mysql_get_server_info()."\n";
	$header .= "-- Database: ".dbcbackup_backquote(DB_NAME)."\n";
	$header .= "-- -------------------------------------------------------- \n";
	return $header;
}

/* ------------------ */

function dbcbackup_fields($result) {
    $fields       = 	array();
    $num_fields   = 	mysql_num_fields($result);
    for ($i = 0; $i < $num_fields; $i++) 
	{
        $fields[] = mysql_fetch_field($result, $i);
    }
    return (array($num_fields, $fields));
}

/* ------------------ */

function dbcbackup_addslashes($a_string = '', $is_like = false, $crlf = false, $php_code = false)
{	//Taken from phpMyAdmin.
	if ($is_like) {
		$a_string = str_replace('\\', '\\\\\\\\', $a_string);
	} else {
		$a_string = str_replace('\\', '\\\\', $a_string);
	}
	if ($crlf) {
		$a_string = str_replace("\n", '\n', $a_string);
		$a_string = str_replace("\r", '\r', $a_string);
		$a_string = str_replace("\t", '\t', $a_string);
	}
	if ($php_code) {
		$a_string = str_replace('\'', '\\\'', $a_string);
	} else {
		$a_string = str_replace('\'', '\'\'', $a_string);
	}
	return $a_string;
}

/* ------------------ */

function dbcbackup_open($fp, $mode='write')
{
	switch(DBC_COMPRESSION)
	{
		case 'bz2':
			if($mode=='write'){
				$fp = $fp.".sql.bz2";
				$file = @bzopen($fp, "w");
			}else{ $file = @bzopen($fp, "r"); }
		break;

		case 'gz':
			if($mode=='write'){
				$fp = $fp.".sql.gz";
				$file = @gzopen($fp, 'w'.DBC_GZIP_LVL);
			}else{ $file = @gzopen($fp, "r"); }
		break;

		default:
			if($mode=='write'){
				$fp = $fp.".sql";
				$file = @fopen($fp, "w");
			}else{ $file = @fopen($fp, "r"); }
		break;
	}
	return array($file, $fp);
}

/* ------------------ */

function dbcbackup_read($fp)
{
	switch(DBC_COMPRESSION)
	{
		case 'bz2':
			$data = bzread($fp, 4096);
		break;

		case 'gz':
			$data = gzread($fp, 4096);
		break;
		
		default:
			$data = fread($fp, 4096);
		break;
	}
	return ($data);
}

/* ------------------ */

function dbcbackup_write($fp, $code)
{	
	switch(DBC_COMPRESSION)
	{
		case 'bz2':
			bzwrite($fp, $code);
		break;

		case 'gz':
			gzwrite($fp, $code);
		break;

		default:
			fwrite($fp, $code);
		break;
	}
	return;
}

/* ------------------ */

function dbcbackup_close($fp)
{
	switch(DBC_COMPRESSION)
	{
		case 'bz2':
			bzclose($fp);
		break;

		case 'gz':
			gzclose($fp);
		break;

		default:
			fclose($fp);
		break;
	}
	return;
}

/* ------------------ */

function dbcbackup_rotate($cfg, $timenow)
{
	$removed = 0;
	if($cfg['rotate'] >= 0)
	{
		$compare = 86400 * $cfg['rotate'];
		if ($handle = opendir($cfg['export_dir'])) 
		{
			$dump_formats = array('gz', 'sql', 'bz2');
			while (false !== ($file = readdir($handle))) 
			{	
				$ext = substr(strrchr($file, '.'), 1);
				if ($file != "." && $file != ".." && in_array(strtolower($ext), $dump_formats)) 
				{ 	
					$name = explode('_', $file);
					list($month, $day, $yearhour, $minute, $seconds) = explode('.', $name[1]);
					list($year, $hour) = explode('-', $yearhour);
					$generated = mktime($hour,  $minute, $seconds, $month, $day, $year);

					if($timenow > $generated + $compare)
					{
						if(@unlink($cfg['export_dir'].'/'.$file))
						{
							$removed++;
						}
					}
				}
			}
		  closedir($handle);
		} 
	}
	return $removed;
}

?>