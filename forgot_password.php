<?php require_once('Connections/SLC.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');
?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');
?>
<?php
// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");
?>
<?php
// Make unified connection variable
$conn_SLC = new KT_connection($SLC, $database_SLC);
?>

<?php
// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("email", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger
?>
<?php
//start Trigger_ForgotPasswordCheckEmail trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPasswordCheckEmail(&$tNG) {
  return Trigger_ForgotPassword_CheckEmail($tNG);
}
//end Trigger_ForgotPasswordCheckEmail trigger

mysql_select_db($database_SLC, $SLC);
$query_settings = "SELECT * FROM settings";
$settings = mysql_query($query_settings, $SLC) or die(mysql_error());
$row_settings = mysql_fetch_assoc($settings);
$totalRows_settings = mysql_num_rows($settings);
?>
<?php
//start Trigger_ForgotPassword_Email trigger
//remove this line if you want to edit the code by hand
function Trigger_ForgotPassword_Email(&$tNG) {
  $emailObj = new tNG_Email($tNG);
  $emailObj->setFrom("{KT_defaultSender}");
  $emailObj->setTo("{email}");
  $emailObj->setCC("");
  $emailObj->setBCC("");
  $emailObj->setSubject("Forgot password email");
  //FromFile method
  $emailObj->setContentFile("includes/mailtemplates/forgot.html");
  $emailObj->setEncoding("ISO-8859-1");
  $emailObj->setFormat("HTML/Text");
  $emailObj->setImportance("Normal");
  return $emailObj->Execute();
}
//end Trigger_ForgotPassword_Email trigger

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
?>
<?php
// Make an update transaction instance
$forgotpass_transaction = new tNG_update($conn_SLC);
$tNGs->addTransaction($forgotpass_transaction);
// Register triggers
$forgotpass_transaction->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Update1");
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$forgotpass_transaction->registerTrigger("BEFORE", "Trigger_ForgotPasswordCheckEmail", 20);
$forgotpass_transaction->registerTrigger("AFTER", "Trigger_ForgotPassword_Email", 1);
$forgotpass_transaction->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
// Add columns
$forgotpass_transaction->setTable("users");
$forgotpass_transaction->addColumn("email", "STRING_TYPE", "POST", "email");
$forgotpass_transaction->setPrimaryKey("email", "STRING_TYPE", "POST", "email");
?>
<?php
// Execute all the registered transactions
$tNGs->executeTransactions();
?>
<?php
// Get the transaction recordset
$rsusers = $tNGs->getRecordset("users");
$row_rsusers = mysql_fetch_assoc($rsusers);
$totalRows_rsusers = mysql_num_rows($rsusers);
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $row_settings['name']; ?> | Forgot Password</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link rel="stylesheet" type="text/css" href="css/cloud-admin.css" >
	
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- UNIFORM -->
	<link rel="stylesheet" type="text/css" href="js/uniform/css/uniform.default.min.css" />
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="css/animatecss/animate.min.css" />
	<!-- FONTS -->
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
</head>
<body class="login">	
	<!-- PAGE -->
	<section id="page">
			<!-- HEADER -->
			<header>
				<!-- NAV-BAR -->
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div id="logo">
								<img src="<?php echo $row_settings['logo']; ?>" height="40" alt="<?php echo $row_settings['name']; ?>" />
							</div>
						</div>
					</div>
				</div>
				<!--/NAV-BAR -->
			</header>
	
			<!-- FORGOT PASSWORD -->
			<section id="login_bg" class="visible">
				<div class="container">
					<div class="row">
						<div class="col-md-4 col-md-offset-4">
							<div class="login-box">
								<h2 class="bigintro">Reset Password</h2>
								<div class="divide-40"></div>
							  <?php
                                echo $tNGs->getErrorMsg();
                            ?>
                              <form role="form" method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
                             <div class="form-group">  <label for="email">Enter your Email address</label>
                                   <i class="fa fa-envelope"></i> <input type="text" class="form-control" name="email" id="email" value="<?php echo KT_escapeAttribute($row_rsusers['email']); ?>" size="32" />
                                </div>
                                    <?php echo $tNGs->displayFieldError("users", "email"); ?>
                       
                                       
                                                <button type="submit" name="KT_Update1" id="KT_Update1" class="btn btn-info" value="Submit" >Send me a new Password</button>
                                            
                                    
                          </form>
								<div class="login-helpers">
									<a href="login.php">Back to Login</a> <br>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!-- FORGOT PASSWORD -->
	</section>
	<!--/PAGE -->
	<!-- JAVASCRIPTS -->
	<!-- Placed at the end of the document so the pages load faster -->
	<!-- JQUERY -->
	<script src="js/jquery/jquery-2.0.3.min.js"></script>
	<!-- JQUERY UI-->
	<script src="js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
	<!-- BOOTSTRAP -->
	<script src="bootstrap-dist/js/bootstrap.min.js"></script>
	
	
	<!-- UNIFORM -->
	<script type="text/javascript" src="js/uniform/jquery.uniform.min.js"></script>
	<!-- BACKSTRETCH -->
	<script type="text/javascript" src="js/backstretch/jquery.backstretch.min.js"></script>
	<!-- CUSTOM SCRIPT -->
	<script src="js/script.js"></script>
	<script>
		jQuery(document).ready(function() {		
			App.setPage("login_bg");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script>

<!-- /JAVASCRIPTS -->
</body>
</html>
<?php
mysql_free_result($settings);
?>
