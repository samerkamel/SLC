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

// Start trigger
$formValidation = new tNG_FormValidation();
$tNGs->prepareValidation($formValidation);
// End trigger

// Make an insert transaction instance
$ins_districs = new tNG_insert($conn_SLC);
$tNGs->addTransaction($ins_districs);
// Register triggers
$ins_districs->registerTrigger("STARTER", "Trigger_Default_Starter", 1, "POST", "KT_Insert1");
$ins_districs->registerTrigger("BEFORE", "Trigger_Default_FormValidation", 10, $formValidation);
// Add columns
$ins_districs->setTable("districs");
$ins_districs->addColumn("name", "STRING_TYPE", "POST", "name");
$ins_districs->setPrimaryKey("id", "NUMERIC_TYPE");

// Execute all the registered transactions
$tNGs->executeTransactions();

// Get the transaction recordset
$rsdistrics = $tNGs->getRecordset("districs");
$row_rsdistrics = mysql_fetch_assoc($rsdistrics);
$totalRows_rsdistrics = mysql_num_rows($rsdistrics);
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
      <td class="KT_th"><label for="name">Name:</label></td>
      <td><input type="text" name="name" id="name" value="<?php echo KT_escapeAttribute($row_rsdistrics['name']); ?>" size="32" />
        <?php echo $tNGs->displayFieldHint("name");?> <?php echo $tNGs->displayFieldError("districs", "name"); ?></td>
    </tr>
    <tr class="KT_buttons">
      <td colspan="2"><input type="submit" name="KT_Insert1" id="KT_Insert1" value="Insert record" /></td>
    </tr>
  </table>
</form>
<p>&nbsp;</p>
</body>
</html>