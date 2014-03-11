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
	<title><?php echo $row_settings['name']; ?> | All Districts</title>

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
                                        <li><a href="districts.php">Districts</a></li>
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left">Districts</h3>
									</div>
						
                                    
                                    
                                    
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
					  <div class="row">
						<div class="col-md-12">
                        <div class="box border primary">
									<div class="box-title">
										<h4><i class="fa fa-folder"></i>All system districts</h4>
									</div>
                                    <div class="box-body">
						<?php
						mysql_select_db($database_SLC, $SLC);
						$query_parentDistricts = "SELECT id, name FROM districts WHERE parent = 1 AND id > 1 ORDER BY name ASC";
						$parentDistricts = mysql_query($query_parentDistricts, $SLC) or die(mysql_error());
						$row_parentDistricts = mysql_fetch_assoc($parentDistricts);
						$totalRows_parentDistricts = mysql_num_rows($parentDistricts);
						do { ?>
                       	    <li><a href="district.php?id=<?php echo $row_parentDistricts['id']; ?>"> <?php echo $row_parentDistricts['name']; ?></a>
                                   <?php 
								   	$totalRows_secondDistricts = 0;
								   	$colname_secondDistricts = $row_parentDistricts['id'];
                                   	mysql_select_db($database_SLC, $SLC);
								   	$query_secondDistricts = sprintf("SELECT id, name FROM districts WHERE parent = %s", GetSQLValueString($colname_secondDistricts, "int"));
								   	$secondDistricts = mysql_query($query_secondDistricts, $SLC) or die(mysql_error());
                                   	$row_secondDistricts = mysql_fetch_assoc($secondDistricts);
									$totalRows_secondDistricts = mysql_num_rows($secondDistricts);
									
									if ( $totalRows_secondDistricts > 0 ) { ?>
                                    <ul>
                                    	<?php do { ?>                                    	
                          				<li><a href="district.php?id=<?php echo $row_secondDistricts['id'];?>"> <?php echo $row_secondDistricts['name'];?></a>
                              
                                      
                                        </li>
                                       <?php } while ($row_secondDistricts = mysql_fetch_assoc($secondDistricts)); }; ?>
                                      
                                        
                                <?php if ( $totalRows_secondDistricts > 0 ) { ?></ul><?php }; ?>
                                </li>
                          	<?php } while ($row_parentDistricts = mysql_fetch_assoc($parentDistricts));
							
							?>
                            </div>
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
	 <?php include 'footer_js.php'; ?>
	<script>
		jQuery(document).ready(function() {		
			App.setPage("google_maps");  //Set current page
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