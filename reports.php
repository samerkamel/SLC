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

mysql_select_db($database_SLC, $SLC);
$query_settings = "SELECT * FROM settings";
$settings = mysql_query($query_settings, $SLC) or die(mysql_error());
$row_settings = mysql_fetch_assoc($settings);
$totalRows_settings = mysql_num_rows($settings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $row_settings['name']; ?> | Reports</title>
 
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
                                        <li><a href="reports.php">Reports</a></li>
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left">System Reports</h3>
									</div>
									
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
					  <div class="row">
						<div class="col-md-12">
								<h3>Select Report</h3>
                                <ul>
                                	<li><a href="reports-config.php">All unit configuration report</a></li>
                                    <li>Violations report</li>
                                    <li>Unit consumption report</li>
                                    <li>Unit Data package renewal</li>
                                </ul>
								
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
		
			App.init(); //Initialise plugins and elements
			MapsGoogle.init(); //Init the google maps
		});
	</script>
	<!-- /JAVASCRIPTS -->
</body>
</html>
<?php
mysql_free_result($settings);
?>
