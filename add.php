<?php require_once('Connections/SLC.php'); ?>
<?php
//MX Widgets3 include
require_once('includes/wdg/WDG.php');

// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_SLC = new KT_connection($SLC, $database_SLC);

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("name", true, "text", "", "", "", "District name is required");
$tNGs->prepareValidation($formValidation);
// End trigger

// Start trigger
$formValidation1 = new tNG_FormValidation();
$formValidation1->addField("name", true, "text", "", "", "", "Name is required.");
$formValidation1->addField("districtid", true, "numeric", "", "", "", "A district is required.");
$tNGs->prepareValidation($formValidation1);
// End trigger

//start Trigger_CheckPasswords trigger
//remove this line if you want to edit the code by hand
function Trigger_CheckPasswords(&$tNG) {
  $myThrowError = new tNG_ThrowError($tNG);
  $myThrowError->setErrorMsg("Passwords do not match.");
  $myThrowError->setField("password");
  $myThrowError->setFieldErrorMsg("The two passwords do not match.");
  return $myThrowError->Execute();
}
//end Trigger_CheckPasswords trigger

// Start trigger
$formValidation3 = new tNG_FormValidation();
$formValidation3->addField("uniqueID", true, "text", "", "", "", "The unit unique ID is required");
$formValidation3->addField("street", true, "text", "", "", "", "A street is required, please add a street first");
$tNGs->prepareValidation($formValidation3);
// End trigger

// Start trigger
$formValidation2 = new tNG_FormValidation();
$formValidation2->addField("username", true, "text", "", "", "", "");
$formValidation2->addField("password", true, "text", "", "", "", "");
$formValidation2->addField("email", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation2);
// End trigger

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

mysql_select_db($database_SLC, $SLC);
$query_settings = "SELECT * FROM settings";
$settings = mysql_query($query_settings, $SLC) or die(mysql_error());
$row_settings = mysql_fetch_assoc($settings);
$totalRows_settings = mysql_num_rows($settings);

mysql_select_db($database_SLC, $SLC);
$query_topDistricts = "SELECT id, name FROM districts WHERE parent = 1 ORDER BY name ASC";
$topDistricts = mysql_query($query_topDistricts, $SLC) or die(mysql_error());
$row_topDistricts = mysql_fetch_assoc($topDistricts);
$totalRows_topDistricts = mysql_num_rows($topDistricts);

mysql_select_db($database_SLC, $SLC);
$query_Recordset1 = "SELECT  id, name FROM districts ORDER BY name";
$Recordset1 = mysql_query($query_Recordset1, $SLC) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);

mysql_select_db($database_SLC, $SLC);
$query_Recordset2 = "SELECT  id, districtid, name FROM streets ORDER BY name";
$Recordset2 = mysql_query($query_Recordset2, $SLC) or die(mysql_error());
$row_Recordset2 = mysql_fetch_assoc($Recordset2);
$totalRows_Recordset2 = mysql_num_rows($Recordset2);

// Make an insert transaction instance
$ins_districts = new tNG_insert($conn_SLC);
$tNGs->addTransaction($ins_districts);
// Register triggers
$ins_districts->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_districts->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$ins_districts->registerTrigger("END", "Trigger_Default_Redirect", 99, "district.php?id={id}");
// Add columns
$ins_districts->setTable("districts");
$ins_districts->addColumn("name", "STRING_TYPE", "POST", "name");
$ins_districts->addColumn("parent", "NUMERIC_TYPE", "POST", "parent");
$ins_districts->addColumn("maplat", "STRING_TYPE", "POST", "maplat");
$ins_districts->addColumn("maplong", "STRING_TYPE", "POST", "maplong");
$ins_districts->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an insert transaction instance
$ins_streets = new tNG_insert($conn_SLC);
$tNGs->addTransaction($ins_streets);
// Register triggers
$ins_streets->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert2");
$ins_streets->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation1);
$ins_streets->registerTrigger("END", "Trigger_Default_Redirect", 99, "street.php?id={id}");
// Add columns
$ins_streets->setTable("streets");
$ins_streets->addColumn("name", "STRING_TYPE", "POST", "name");
$ins_streets->addColumn("districtid", "NUMERIC_TYPE", "POST", "districtid");
$ins_streets->addColumn("maplat", "STRING_TYPE", "POST", "maplat");
$ins_streets->addColumn("maplong", "STRING_TYPE", "POST", "maplong");
$ins_streets->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an insert transaction instance
$ins_users = new tNG_insert($conn_SLC);
$tNGs->addTransaction($ins_users);
// Register triggers
$ins_users->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert3");
$ins_users->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation2);
$ins_users->registerTrigger("END", "Trigger_Default_Redirect", 99, "user.php?id={id}");
$ins_users->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
// Add columns
$ins_users->setTable("users");
$ins_users->addColumn("username", "STRING_TYPE", "POST", "username");
$ins_users->addColumn("password", "STRING_TYPE", "POST", "password");
$ins_users->addColumn("firstname", "STRING_TYPE", "POST", "firstname");
$ins_users->addColumn("lastname", "STRING_TYPE", "POST", "lastname");
$ins_users->addColumn("email", "STRING_TYPE", "POST", "email");
$ins_users->addColumn("accesslevel", "STRING_TYPE", "POST", "accesslevel");
$ins_users->addColumn("districtID", "NUMERIC_TYPE", "POST", "districtID");
$ins_users->setPrimaryKey("id", "NUMERIC_TYPE");

// Make an insert transaction instance
$ins_units = new tNG_insert($conn_SLC);
$tNGs->addTransaction($ins_units);
// Register triggers
$ins_units->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert4");
$ins_units->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation3);
$ins_units->registerTrigger("END", "Trigger_Default_Redirect", 99, "unit.php?id={id}");
// Add columns
$ins_units->setTable("units");
$ins_units->addColumn("uniqueID", "STRING_TYPE", "POST", "uniqueID");
$ins_units->addColumn("name", "STRING_TYPE", "POST", "name");
$ins_units->addColumn("districtID", "NUMERIC_TYPE", "POST", "districtID");
$ins_units->addColumn("street", "NUMERIC_TYPE", "POST", "street");
$ins_units->addColumn("address", "STRING_TYPE", "POST", "address");
$ins_units->addColumn("latitude", "DOUBLE_TYPE", "POST", "latitude");
$ins_units->addColumn("longitude", "DOUBLE_TYPE", "POST", "longitude");
$ins_units->addColumn("mobile", "STRING_TYPE", "POST", "mobile");
$ins_units->addColumn("renewdate", "DATE_TYPE", "POST", "renewdate");
$ins_units->addColumn("enabled", "CHECKBOX_1_0_TYPE", "POST", "enabled", "1");
$ins_units->addColumn("phasegroup", "NUMERIC_TYPE", "POST", "phasegroup");
$ins_units->addColumn("phasetimer", "CHECKBOX_1_0_TYPE", "POST", "phasetimer", "0");
$ins_units->addColumn("startphase", "STRING_TYPE", "POST", "startphase");
$ins_units->addColumn("ontime", "DATE_TYPE", "POST", "ontime");
$ins_units->addColumn("offtime", "DATE_TYPE", "POST", "offtime");
$ins_units->addColumn("dimmer", "DOUBLE_TYPE", "POST", "dimmer");
$ins_units->addColumn("lowercurrent", "DOUBLE_TYPE", "POST", "lowercurrent");
$ins_units->addColumn("uppercurrent", "DOUBLE_TYPE", "POST", "uppercurrent");
$ins_units->addColumn("lowervoltage", "DOUBLE_TYPE", "POST", "lowervoltage");
$ins_units->addColumn("uppervoltage", "DOUBLE_TYPE", "POST", "uppervoltage");
$ins_units->addColumn("comments", "STRING_TYPE", "POST", "comments");
$ins_units->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsdistricts = $tNGs->getRecordset("districts");
$row_rsdistricts = mysql_fetch_assoc($rsdistricts);
$totalRows_rsdistricts = mysql_num_rows($rsdistricts);

// Get the transaction recordset
$rsstreets = $tNGs->getRecordset("streets");
$row_rsstreets = mysql_fetch_assoc($rsstreets);
$totalRows_rsstreets = mysql_num_rows($rsstreets);

// Get the transaction recordset
$rsusers = $tNGs->getRecordset("users");
$row_rsusers = mysql_fetch_assoc($rsusers);
$totalRows_rsusers = mysql_num_rows($rsusers);

// Get the transaction recordset
$rsunits = $tNGs->getRecordset("units");
$row_rsunits = mysql_fetch_assoc($rsunits);
$totalRows_rsunits = mysql_num_rows($rsunits);
?>
<!DOCTYPE html>
<html lang="en" xmlns:wdg="http://ns.adobe.com/addt">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $row_settings['name']; ?> | Add</title>

    <link rel="stylesheet" href="css/jquery.mobile-1.4.1.min.css">
	<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
	<script src="includes/common/js/base.js" type="text/javascript"></script>
	<script src="includes/common/js/utility.js" type="text/javascript"></script>
	<script src="includes/skins/style.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
		$(document).bind("mobileinit", function () {
		$.mobile.ajaxEnabled = false;
	});
    </script>
	<script src="http://code.jquery.com/mobile/1.4.1/jquery.mobile-1.4.1.min.js"></script>
	<?php echo $tNGs->displayValidationRules();?>
	<script type="text/javascript" src="includes/common/js/sigslot_core.js"></script>
	<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js"></script>
	<script type="text/javascript" src="includes/wdg/classes/MXWidgets.js.php"></script>
	<script type="text/javascript" src="includes/wdg/classes/JSRecordset.js"></script>
	<script type="text/javascript" src="includes/wdg/classes/DependentDropdown.js"></script>
	<?php
	//begin JSRecordset
	$jsObject_Recordset2 = new WDG_JsRecordset("Recordset2");
	echo $jsObject_Recordset2->getOutput();
	//end JSRecordset
	?>
	 <?php include 'header.php'; ?>
	
	<!-- PAGE -->
	<section id="page">
<?php include 'sidebar.php'; ?>
		<div id="main-content">
			<div class="container">
				<div class="row">
	  <div id="content" class="col-lg-12">
						<!-- PAGE HEADER-->
						<div class="row">
							<div class="col-sm-12">
								<div class="page-header">
	
						      <!-- BREADCRUMBS -->
									<ul class="breadcrumb">
										<li>
											<i class="fa fa-home"></i>
											<a href="index.php">Home</a>
										</li>
                                        <li>
										  <i class="fa fa-plus"></i>
										  <a href="add.php">Add</a>
										</li>
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left">Add</h3>
									</div>
									<div class="description">Add Districts, Streets, Units or Users.</div>
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
					  <div class="row">
						<div class="col-md-12">
								<!-- BOX -->
                           
											 <div class="panel panel-default">
												<div class="panel-body">
													 <div class="tabbable">
														<ul class="nav nav-tabs">
														   <li class="active"><a href="#tab_1_1" data-toggle="tab"><i class="fa fa-building-o"></i> District</a></li>
														   <li><a href="#tab_1_2" data-toggle="tab"><i class="fa fa-ellipsis-h"></i> Street</a></li>
														   <li><a href="#tab_1_3" data-toggle="tab"><i class="fa fa-mobile"></i> Unit</a></li>
                                                           <li><a href="#tab_1_4" data-toggle="tab"><i class="fa fa-user"></i> User</a></li>
														</ul>
														<div class="tab-content">
													      <div class="tab-pane fade in active" id="tab_1_1">
														    <div class="divide-10"></div>
                                                            <?php
	echo $tNGs->getErrorMsg();
?>
                                                            <form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                                                              <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                                                                <tr>
                                                                  <td class="KT_th"><label for="name">Name:</label></td>
                                                                  <td><input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rsdistricts['name']); ?>" size="32" />
                                                                    <?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("districts", "name"); ?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td class="KT_th"><label for="parent">Parent:</label></td>
                                                                  <td><select name="parent" id="parent">
                                                                    <?php 
do {  
?>
                                                                    <option value="<?php echo $row_topDistricts['id']?>"<?php if (!(strcmp($row_topDistricts['id'], $row_rsdistricts['parent']))) {echo "SELECTED";} ?>><?php echo $row_topDistricts['name']?></option>
                                                                    <?php
} while ($row_topDistricts = mysql_fetch_assoc($topDistricts));
  $rows = mysql_num_rows($topDistricts);
  if($rows > 0) {
      mysql_data_seek($topDistricts, 0);
	  $row_topDistricts = mysql_fetch_assoc($topDistricts);
  }
?>
                                                                  </select>
                                                                    <?php echo $tNGs->displayFieldError("districts", "parent"); ?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td class="KT_th"><label for="maplat">Map Latitude:</label></td>
                                                                  <td><input type="text" name="maplat" id="maplat" value="<?php echo KT_escapeAttribute($row_rsdistricts['maplat']); ?>" size="32" />
                                                                    <?php echo $tNGs->displayFieldHint("maplat");?> <?php echo $tNGs->displayFieldError("districts", "maplat"); ?></td>
                                                                </tr>
                                                                <tr>
                                                                  <td class="KT_th"><label for="maplong">Map Longitude:</label></td>
                                                                  <td><input type="text" name="maplong" id="maplong" value="<?php echo KT_escapeAttribute($row_rsdistricts['maplong']); ?>" size="32" />
                                                                    <?php echo $tNGs->displayFieldHint("maplong");?> <?php echo $tNGs->displayFieldError("districts", "maplong"); ?></td>
                                                                </tr>
                                                                <tr class="KT_buttons">
                                                                  <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Add District" /></td>
                                                                </tr>
                                                              </table>
                                                            </form>
                                                            <p>&nbsp;</p>
                                                         </div>
														   <div class="tab-pane fade" id="tab_1_2">
																<div class="divide-10"></div>
                                                                <form method="post" id="form2" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                                                                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                                                                    <tr>
                                                                      <td class="KT_th"><label for="name">Name:</label></td>
                                                                      <td><input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rsstreets['name']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("streets", "name"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="districtid">District:</label></td>
                                                                      <td><select name="districtid" id="districtid">
                                                                        <?php 
																			do {   
																			  if ($row_topDistricts['id'] > 1){
																			?>
																	<option value="<?php echo $row_topDistricts['id']?>"<?php if (!(strcmp($row_topDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>><?php echo $row_topDistricts['name']?></option>
																	<?php
																					
																						do {
																							if ($row_childDistricts['parent'] == $row_topDistricts['id']){
																						?>
																				<option value="<?php echo $row_childDistricts['id']?>"<?php if (!(strcmp($row_childDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>>&nbsp;—<?php echo $row_childDistricts['name']?></option>
																				<?php
																							};} while ($row_childDistricts = mysql_fetch_assoc($childDistricts));
																						  $rows = mysql_num_rows($childDistricts);
																						  if($rows > 0) {
																							  mysql_data_seek($childDistricts, 0);
																							  $row_childDistricts = mysql_fetch_assoc($childDistricts);
																						  }
																					
																			
																			 };} while ($row_topDistricts = mysql_fetch_assoc($topDistricts));
																			  $rows = mysql_num_rows($topDistricts);
																			  if($rows > 0) {
																				  mysql_data_seek($topDistricts, 0);
																				  $row_topDistricts = mysql_fetch_assoc($topDistricts);
																			 }
																			?>
                                                                      </select>
                                                                        <?php echo $tNGs->displayFieldError("streets", "districtid"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="maplat">Map Latitude:</label></td>
                                                                      <td><input type="text" name="maplat" id="maplat" value="<?php echo KT_escapeAttribute($row_rsstreets['maplat']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("maplat");?> <?php echo $tNGs->displayFieldError("streets", "maplat"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="maplong">Map Longitude: </label></td>
                                                                      <td><input type="text" name="maplong" id="maplong" value="<?php echo KT_escapeAttribute($row_rsstreets['maplong']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("maplong");?> <?php echo $tNGs->displayFieldError("streets", "maplong"); ?></td>
                                                                    </tr>
                                                                    <tr class="KT_buttons">
                                                                      <td colspan="2"><input type="submit" name="KT_Insert2" id="KT_Insert2" value="Add Street" /></td>
                                                                    </tr>
                                                                  </table>
                                                                </form>
                                                           </div>
														   <div class="tab-pane fade" id="tab_1_3">
															 <div class="divide-10"></div>
                                                             <form method="post" id="form4" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                                                               <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                                                                 <tr>
                                                                   <td class="KT_th"><label for="uniqueID">Unique ID:</label></td>
                                                                   <td><input type="text" name="uniqueID" id="uniqueID" value="<?php echo KT_escapeAttribute($row_rsunits['uniqueID']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("uniqueID");?> <?php echo $tNGs->displayFieldError("units", "uniqueID"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="name">Name:</label></td>
                                                                   <td><input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rsunits['name']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("units", "name"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="districtID">District:</label></td>
                                                                   <td><select name="districtID" id="districtID">
                                                                     <?php 
																			do {   
																			  if ($row_topDistricts['id'] > 1){
																			?>
																	<option value="<?php echo $row_topDistricts['id']?>"<?php if (!(strcmp($row_topDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>><?php echo $row_topDistricts['name']?></option>
																	<?php
																					
																						do {
																							if ($row_childDistricts['parent'] == $row_topDistricts['id']){
																						?>
																				<option value="<?php echo $row_childDistricts['id']?>"<?php if (!(strcmp($row_childDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>>&nbsp;—<?php echo $row_childDistricts['name']?></option>
																				<?php
																							};} while ($row_childDistricts = mysql_fetch_assoc($childDistricts));
																						  $rows = mysql_num_rows($childDistricts);
																						  if($rows > 0) {
																							  mysql_data_seek($childDistricts, 0);
																							  $row_childDistricts = mysql_fetch_assoc($childDistricts);
																						  }
																					
																			
																			 };} while ($row_topDistricts = mysql_fetch_assoc($topDistricts));
																			  $rows = mysql_num_rows($topDistricts);
																			  if($rows > 0) {
																				  mysql_data_seek($topDistricts, 0);
																				  $row_topDistricts = mysql_fetch_assoc($topDistricts);
																			 }
																			?>
                                                                   </select>
                                                                     <?php echo $tNGs->displayFieldError("units", "districtID"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="street">Street:</label></td>
                                                                   <td><select wdg:subtype="DependentDropdown" name="street" id="street" wdg:type="widget" wdg:recordset="Recordset2" wdg:displayfield="name" wdg:valuefield="id" wdg:fkey="districtid" wdg:triggerobject="districtID">
                                                             </select>
                                                                     <?php echo $tNGs->displayFieldError("units", "street"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="address">Address:</label></td>
                                                                   <td><input type="text" name="address" id="address" value="<?php echo KT_escapeAttribute($row_rsunits['address']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("address");?> <?php echo $tNGs->displayFieldError("units", "address"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="latitude">Map Latitude:</label></td>
                                                                   <td><input type="text" name="latitude" id="latitude" value="<?php echo KT_escapeAttribute($row_rsunits['latitude']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("latitude");?> <?php echo $tNGs->displayFieldError("units", "latitude"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="longitude">Map Longitude:</label></td>
                                                                   <td><input type="text" name="longitude" id="longitude" value="<?php echo KT_escapeAttribute($row_rsunits['longitude']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("longitude");?> <?php echo $tNGs->displayFieldError("units", "longitude"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="mobile">Mobile:</label></td>
                                                                   <td><input type="text" name="mobile" id="mobile" value="<?php echo KT_escapeAttribute($row_rsunits['mobile']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("mobile");?> <?php echo $tNGs->displayFieldError("units", "mobile"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="renewdate">Renewdate:</label></td>
                                                                   <td><input type="text" name="renewdate" id="renewdate" value="<?php echo KT_formatDate($row_rsunits['renewdate']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("renewdate");?> <?php echo $tNGs->displayFieldError("units", "renewdate"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="enabled">Enabled:</label></td>
                                                                   <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rsunits['enabled']),"1"))) {echo "checked";} ?> type="checkbox" data-role="flipswitch" name="enabled" id="enabled" value="1" />
                                                                     <?php echo $tNGs->displayFieldError("units", "enabled"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="phasegroup">Phasegroup:</label></td>
                                                                   <td><input type="text" name="phasegroup" id="phasegroup" value="<?php echo KT_escapeAttribute($row_rsunits['phasegroup']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("phasegroup");?> <?php echo $tNGs->displayFieldError("units", "phasegroup"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="phasetimer">Phasetimer:</label></td>
                                                                   <td><input  <?php if (!(strcmp(KT_escapeAttribute($row_rsunits['phasetimer']),"1"))) {echo "checked";} ?> type="checkbox" name="phasetimer" data-role="flipswitch" id="phasetimer" value="1" />
                                                                     <?php echo $tNGs->displayFieldError("units", "phasetimer"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="startphase">Startphase:</label></td>
                                                                   <td><input type="text" name="startphase" id="startphase" value="<?php echo KT_escapeAttribute($row_rsunits['startphase']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("startphase");?> <?php echo $tNGs->displayFieldError("units", "startphase"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="ontime">Ontime:</label></td>
                                                                   <td><input type="text" name="ontime" id="ontime" value="<?php echo KT_formatDate($row_rsunits['ontime']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("ontime");?> <?php echo $tNGs->displayFieldError("units", "ontime"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="offtime">Offtime:</label></td>
                                                                   <td><input type="text" name="offtime" id="offtime" value="<?php echo KT_formatDate($row_rsunits['offtime']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("offtime");?> <?php echo $tNGs->displayFieldError("units", "offtime"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="dimmer">Dimmer:</label></td>
                                                                   <td><input type="range"  value="50" min="0" max="100" name="dimmer" id="dimmer" value="<?php echo KT_escapeAttribute($row_rsunits['dimmer']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("dimmer");?> <?php echo $tNGs->displayFieldError("units", "dimmer"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="lowercurrent">Lowercurrent:</label></td>
                                                                   <td><input type="text" name="lowercurrent" id="lowercurrent" value="<?php echo KT_escapeAttribute($row_rsunits['lowercurrent']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("lowercurrent");?> <?php echo $tNGs->displayFieldError("units", "lowercurrent"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="uppercurrent">Uppercurrent:</label></td>
                                                                   <td><input type="text" name="uppercurrent" id="uppercurrent" value="<?php echo KT_escapeAttribute($row_rsunits['uppercurrent']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("uppercurrent");?> <?php echo $tNGs->displayFieldError("units", "uppercurrent"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="lowervoltage">Lowervoltage:</label></td>
                                                                   <td><input type="text" name="lowervoltage" id="lowervoltage" value="<?php echo KT_escapeAttribute($row_rsunits['lowervoltage']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("lowervoltage");?> <?php echo $tNGs->displayFieldError("units", "lowervoltage"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="uppervoltage">Uppervoltage:</label></td>
                                                                   <td><input type="text" name="uppervoltage" id="uppervoltage" value="<?php echo KT_escapeAttribute($row_rsunits['uppervoltage']); ?>" size="32" />
                                                                     <?php echo $tNGs->displayFieldHint("uppervoltage");?> <?php echo $tNGs->displayFieldError("units", "uppervoltage"); ?></td>
                                                                 </tr>
                                                                 <tr>
                                                                   <td class="KT_th"><label for="comments">Comments:</label></td>
                                                                   <td><textarea name="comments" id="comments" cols="50" rows="5"><?php echo KT_escapeAttribute($row_rsunits['comments']); ?></textarea>
                                                                     <?php echo $tNGs->displayFieldHint("comments");?> <?php echo $tNGs->displayFieldError("units", "comments"); ?></td>
                                                                 </tr>
                                                                 <tr class="KT_buttons">
                                                                   <td colspan="2"><input type="submit" name="KT_Insert4" id="KT_Insert4" value="Add new unit" /></td>
                                                                 </tr>
                                                               </table>
                                                             </form>
                                                             <p>&nbsp;</p>
                                                           </div>
                                                           <div class="tab-pane fade" id="tab_1_4">
																<div class="divide-10"></div>
                                                                <form method="post" id="form3" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                                                                  <table cellpadding="2" cellspacing="0" class="KT_tngtable">
                                                                    <tr>
                                                                      <td class="KT_th"><label for="username">Username:</label></td>
                                                                      <td><input type="text" name="username" id="username" value="<?php echo KT_escapeAttribute($row_rsusers['username']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("username");?> <?php echo $tNGs->displayFieldError("users", "username"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="password">Password:</label></td>
                                                                      <td><input type="password" name="password" id="password" value="" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("password");?> <?php echo $tNGs->displayFieldError("users", "password"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="re_password">Re-type Password:</label></td>
                                                                      <td><input type="password" name="re_password" id="re_password" value="" size="32" /></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="firstname">Firstname:</label></td>
                                                                      <td><input type="text" name="firstname" id="firstname" value="<?php echo KT_escapeAttribute($row_rsusers['firstname']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("firstname");?> <?php echo $tNGs->displayFieldError("users", "firstname"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="lastname">Lastname:</label></td>
                                                                      <td><input type="text" name="lastname" id="lastname" value="<?php echo KT_escapeAttribute($row_rsusers['lastname']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("lastname");?> <?php echo $tNGs->displayFieldError("users", "lastname"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="email">Email:</label></td>
                                                                      <td><input type="text" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsusers['email']); ?>" size="32" />
                                                                        <?php echo $tNGs->displayFieldHint("email");?> <?php echo $tNGs->displayFieldError("users", "email"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="accesslevel">Access level:</label></td>
                                                                      <td><select name="accesslevel" id="accesslevel">
                                                                        <option value="1" <?php if (!(strcmp(1, KT_escapeAttribute($row_rsusers['accesslevel'])))) {echo "SELECTED";} ?>>Alfa</option>
                                                                        <option value="2" <?php if (!(strcmp(2, KT_escapeAttribute($row_rsusers['accesslevel'])))) {echo "SELECTED";} ?>>Admin</option>
                                                                        <option value="3" <?php if (!(strcmp(3, KT_escapeAttribute($row_rsusers['accesslevel'])))) {echo "SELECTED";} ?>>User</option>
                                                                        <option value="4" <?php if (!(strcmp(4, KT_escapeAttribute($row_rsusers['accesslevel'])))) {echo "SELECTED";} ?>>Reporter</option>
                                                                      </select>
                                                                        <?php echo $tNGs->displayFieldError("users", "accesslevel"); ?></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td class="KT_th"><label for="districtID">District:</label></td>
                                                                      <td><select name="districtID" id="districtID">
                                                                          <?php 
																			do {   
																			 
																			?>
																	<option value="<?php echo $row_topDistricts['id']?>"<?php if (!(strcmp($row_topDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>><?php echo $row_topDistricts['name']?></option>
																	<?php
																					
																						do {
																							if ($row_childDistricts['parent'] == $row_topDistricts['id']){
																						?>
																				<option value="<?php echo $row_childDistricts['id']?>"<?php if (!(strcmp($row_childDistricts['id'], $row_rsstreets['districtid']))) {echo "SELECTED";} ?>>&nbsp;—<?php echo $row_childDistricts['name']?></option>
																				<?php
																							};} while ($row_childDistricts = mysql_fetch_assoc($childDistricts));
																						  $rows = mysql_num_rows($childDistricts);
																						  if($rows > 0) {
																							  mysql_data_seek($childDistricts, 0);
																							  $row_childDistricts = mysql_fetch_assoc($childDistricts);
																						  }
																					
																			
																			 } while ($row_topDistricts = mysql_fetch_assoc($topDistricts));
																			  $rows = mysql_num_rows($topDistricts);
																			  if($rows > 0) {
																				  mysql_data_seek($topDistricts, 0);
																				  $row_topDistricts = mysql_fetch_assoc($topDistricts);
																			 }
																			?>
                                                                      </select>
                                                                        <?php echo $tNGs->displayFieldError("users", "districtID"); ?></td>
                                                                    </tr>
                                                                    <tr class="KT_buttons">
                                                                      <td colspan="2"><input type="submit" name="KT_Insert3" id="KT_Insert3" value="Add New User" /></td>
                                                                    </tr>
                                                                  </table>
                                                                </form>
                                                                <p>&nbsp;</p>
                                                           </div>
														</div>
													 </div>
											   </div>
											 </div>
										 
								<!-- /BOX -->
					    </div>
					  </div>
						<!-- /ROW 4 -->

<div class="footer-tools">
							<span class="go-top">
								<i class="fa fa-chevron-up"></i> Top
							</span>
						</div>
					</div><!-- /CONTENT-->
				</div>
			</div>
		</div>
	</section>
	<!--/PAGE -->
	<!-- JAVASCRIPTS -->
<?php include 'footer_js.php'; ?>




<script>
		jQuery(document).ready(function() {		
			App.setPage("google_maps");  //Set current page
			App.setPage("treeview");  //Set current page
			App.init(); //Initialise plugins and elements
			MapsGoogle.init(); //Init the google maps
		});
	</script>
	<!-- /JAVASCRIPTS -->
</body>
</html>
<?php
mysql_free_result($settings);

mysql_free_result($topDistricts);

mysql_free_result($Recordset1);

mysql_free_result($Recordset2);
?>
