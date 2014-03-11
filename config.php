<?php require_once('Connections/SLC.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

function CRC ($string){
$checksum = 0;
  for($i = 0; $i < strlen($string); $i++) {
    $checksum = $checksum ^ ord(substr($string, $i, 1));
  }
  $hexsum = base_convert($checksum,10,16);
return $hexsum;
};
function getUnitID ($string) { return substr($string,6,-6);
};
function getOpCode ($string) { return substr($string,-5,-3);
};
function checkCRC ($string) {
	if (CRC(getUnitID($string).",".getOpCode($string)) == substr($string,-2)) return true;
	else return false;
};

if (checkCRC($_GET["req"]))
 {
date_default_timezone_set('Africa/Cairo'); // Set timezone to avoid server timezone issues

$info = getdate();
$day = $info['mday'];
$date = str_pad($info['mday'], 2, 0, STR_PAD_LEFT);
$month = str_pad($info['mon'], 2, 0, STR_PAD_LEFT);
$year = str_pad($info['year'], 2, 0, STR_PAD_LEFT);
$hour = str_pad($info['hours'], 2, 0, STR_PAD_LEFT);
$min = str_pad($info['minutes'], 2, 0, STR_PAD_LEFT);
$sec = str_pad($info['seconds'], 2, 0, STR_PAD_LEFT);

$current_date = "$year/$month/$date,$hour:$min:$sec,";
$colname_unitConfig = getUnitID($_GET["req"]);
mysql_select_db($database_SLC, $SLC);
$query_unitConfig = sprintf("SELECT * FROM units WHERE uniqueID = %s", GetSQLValueString($colname_unitConfig, "text"));
$unitConfig = mysql_query($query_unitConfig, $SLC) or die(mysql_error());
$row_unitConfig = mysql_fetch_assoc($unitConfig);
$totalRows_unitConfig = mysql_num_rows($unitConfig);
		if ($totalRows_unitConfig < 1)
			echo "ID_ERR";
		else if (getOpCode($_GET["req"]) != 12 )
			echo "OP_ERR";
		else{
			$response = $colname_unitConfig.",13,$current_date".$row_unitConfig['enabled'].",".$row_unitConfig['phasetimer'].",30,".$row_unitConfig['startphase'].",10,1,0,0,18:00:00,06:00:00,30,0";
			echo "\$ALFA,".$response."*".CRC($response);
		}
 
 }
 else 
 {
	 echo "CRC_ERR";
 }
//Request syntax: 	$ALFA,123456,12*28  
//					$ALFA,UNITID,OP*CRC

//Response syntax: 	$ALFA,123456,13,2013/07/16,19:20:30,1,1,30,101,10,1,0,0,18:00:00,06:00:00,30,0*1F									
//					$ALFA,UNITID,OP,___DATE___,__TIME__,E,T,Tm,PHS,DM,P,F,V,ON__TIME,OFF_TIME,UP,D*CRC


// need to use explode instead of substr ( http://au2.php.net/explode )
/*
$data = "foo:*:1023:1000::/home/foo:/bin/sh";
list($user, $pass, $uid, $gid, $gecos, $home, $shell) = explode(":", $data);
echo $user; // foo
echo $pass; // *




echo $_GET["req"];
echo "<br><br>";

echo "<br><br>";
echo getOpCode($_GET["req"]);
echo "<br><br>";
echo CRC(getUnitID($_GET["req"]).",".getOpCode($_GET["req"]));
echo "<br><br>";
if (checkCRC($_GET["req"]))
	echo "CRC Match";
	
*/
?>



<?php
if (checkCRC($_GET["req"]))
 {
	mysql_free_result($unitConfig);
 };
?>
