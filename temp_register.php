<?php require_once('Connections/SLC.php'); ?>
<?php
// Load the common classes
require_once('includes/common/KT_common.php');

// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make a transaction dispatcher instance
$tNGs = new tNG_dispatcher("");

// Make unified connection variable
$conn_SLC = new KT_connection($SLC, $database_SLC);

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

//start Trigger_WelcomeEmail trigger
//remove this line if you want to edit the code by hand
function Trigger_WelcomeEmail(&$tNG) {
  $emailObj = new tNG_Email($tNG);
  $emailObj->setFrom("{KT_defaultSender}");
  $emailObj->setTo("{email}");
  $emailObj->setCC("");
  $emailObj->setBCC("");
  $emailObj->setSubject("Welcome");
  //FromFile method
  $emailObj->setContentFile("includes/mailtemplates/welcome.html");
  $emailObj->setEncoding("ISO-8859-1");
  $emailObj->setFormat("HTML/Text");
  $emailObj->setImportance("Normal");
  return $emailObj->Execute();
}
//end Trigger_WelcomeEmail trigger

// Start trigger
$formValidation = new tNG_FormValidation();
$formValidation->addField("username", true, "text", "", "", "", "");
$formValidation->addField("password", true, "text", "", "", "", "");
$formValidation->addField("email", true, "text", "email", "", "", "");
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$userRegistration = new tNG_insert($conn_SLC);
$tNGs->addTransaction($userRegistration);
// Register triggers
$userRegistration->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$userRegistration->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
$userRegistration->registerTrigger("END", "Trigger_Default_Redirect", 99, "{kt_login_redirect}");
$userRegistration->registerConditionalTrigger("{POST.password} != {POST.re_password}", "BEFORE", "Trigger_CheckPasswords", 50);
$userRegistration->registerTrigger("AFTER", "Trigger_WelcomeEmail", 40);
// Add columns
$userRegistration->setTable("users");
$userRegistration->addColumn("username", "STRING_TYPE", "POST", "username");
$userRegistration->addColumn("password", "STRING_TYPE", "POST", "password");
$userRegistration->addColumn("firstname", "STRING_TYPE", "POST", "firstname");
$userRegistration->addColumn("lastname", "STRING_TYPE", "POST", "lastname");
$userRegistration->addColumn("email", "STRING_TYPE", "POST", "email");
$userRegistration->addColumn("accesslevel", "STRING_TYPE", "VALUE", "");
$userRegistration->addColumn("districtID", "NUMERIC_TYPE", "POST", "districtID");
$userRegistration->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsusers = $tNGs->getRecordset("users");
$row_rsusers = mysql_fetch_assoc($rsusers);
$totalRows_rsusers = mysql_num_rows($rsusers);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="includes/skins/mxkollection3.css" rel="stylesheet" type="text/css" media="all" />
<script src="includes/common/js/base.js" type="text/javascript"></script>
<script src="includes/common/js/utility.js" type="text/javascript"></script>
<script src="includes/skins/style.js" type="text/javascript"></script>
<?php echo $tNGs->displayValidationRules();?>
</head>

<body>
<?php
	echo $tNGs->getErrorMsg();
?>
<form method="post" id="form1" action="<?php echo KT_escapeAttribute(KT_getFullUri()); ?>">
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
      <td class="KT_th">Accesslevel:</td>
      <td><?php echo KT_escapeAttribute($row_rsusers['accesslevel']); ?></td>
    </tr>
    <tr>
      <td class="KT_th"><label for="districtID">districtID:</label></td>
      <td><input type="text" name="districtID" id="districtID" value="<?php echo KT_escapeAttribute($row_rsusers['districtID']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("districtID");?> <?php echo $tNGs->displayFieldError("users", "districtID"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Register" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>