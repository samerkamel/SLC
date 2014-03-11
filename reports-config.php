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

mysql_select_db($database_SLC, $SLC);
$query_allUnits = "SELECT * FROM units";
$allUnits = mysql_query($query_allUnits, $SLC) or die(mysql_error());
$row_allUnits = mysql_fetch_assoc($allUnits);
$totalRows_allUnits = mysql_num_rows($allUnits);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $row_settings['name']; ?> | Reports</title>

    <!-- DATA TABLES -->
	<link rel="stylesheet" type="text/css" href="js/datatables/media/css/jquery.dataTables.min.css" />
	<link rel="stylesheet" type="text/css" href="js/datatables/media/assets/css/datatables.min.css" />
	<link rel="stylesheet" type="text/css" href="js/datatables/extras/TableTools/media/css/TableTools.min.css" />

 
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
                                        <li>Configuration Report</li>
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left">All units configuration report</h3>
									</div>
									
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
<div class="row">
							<div class="col-md-12">
								<!-- BOX -->
                                Reports are based on configuration, therefore they do not state the CURRENT status of the Unit ( Cycle dependant )<br/><br/>
								<div class="box border purple">
									<div class="box-title">
										<h4><i class="fa fa-table"></i>Units configuration report</h4>
										<div class="tools hidden-xs">
<a href="javascript:;" class="collapse">
												<i class="fa fa-chevron-up"></i>
											</a>
										</div>
									</div>
									<div class="box-body">
										<table id="datatable2" cellpadding="0" cellspacing="0" border="0" class="datatable table table-striped table-bordered table-hover">
										  <thead>
												<tr>
													<th>District</th>
                                                    <th>Street</th>
													<th>Name</th>
													<th>Address</th>
													<th>Unit On/Off</th>
													<th>Timer On/Off</th>
                                                    <th>Phase</th>
                                                </tr>
											</thead>
											<tbody>
												
												<?php 
												if ($totalRows_allUnits>0)
												do { ?>
											    
                                                <tr class="gradeX">
                                                  <td><?php echo $row_allUnits['districtID']; ?></td>
                                                  <td><?php echo $row_allUnits['street']; ?></td>
                                                  <td><?php echo $row_allUnits['name']; ?></td>
                                                  <td><?php echo $row_allUnits['address']; ?></td>
                                                  <td><?php if ( $row_allUnits['enabled']== 1) echo "ON"; else echo "OFF"; ; ?></td>
                                                  <td><?php if ( $row_allUnits['phasetimer']== 1) echo "ON"; else echo "OFF"; ; ?></td>
                                                  <td><?php echo $row_allUnits['phasegroup']; ?></td>
                                                </tr>
                                                <?php } while ($row_allUnits = mysql_fetch_assoc($allUnits)); ?>
</tbody>

<tfoot>
												<tr>
													<th>District</th>
                                                    <th>Street</th>
													<th>Name</th>
													<th>Address</th>
													<th>Unit On/Off</th>
													<th>Timer On/Off</th>
                                                    <th>Phase</th>
                      </tr>
										  </tfoot>
										</table>
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
	<!-- Placed at the end of the document so the pages load faster -->

    <?php include 'footer_js.php'; ?>
        	<!-- DATA TABLES -->
	<script type="text/javascript" src="js/datatables/media/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="js/datatables/media/assets/js/datatables.min.js"></script>
	<script type="text/javascript" src="js/datatables/extras/TableTools/media/js/TableTools.min.js"></script>
	<script type="text/javascript" src="js/datatables/extras/TableTools/media/js/ZeroClipboard.min.js"></script>



	<!-- CUSTOM SCRIPT -->

    

	<script>
		jQuery(document).ready(function() {	

			App.setPage("dynamic_table");  //Set current page

			App.init(); //Initialise plugins and elements

		});
	</script>


	
		






</body>
</html>
<?php
mysql_free_result($settings);

mysql_free_result($allUnits);
?>
