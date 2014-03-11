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

$colname_currentStreet = "-1";
if (isset($_GET['id'])) {
  $colname_currentStreet = $_GET['id'];
}
mysql_select_db($database_SLC, $SLC);
$query_currentStreet = sprintf("SELECT * FROM streets WHERE id = %s", GetSQLValueString($colname_currentStreet, "int"));
$currentStreet = mysql_query($query_currentStreet, $SLC) or die(mysql_error());
$row_currentStreet = mysql_fetch_assoc($currentStreet);
$totalRows_currentStreet = mysql_num_rows($currentStreet);

$colname_currentDistrict = $row_currentStreet['districtid'];

mysql_select_db($database_SLC, $SLC);
$query_currentDistrict = sprintf("SELECT * FROM districts WHERE id = %s", GetSQLValueString($colname_currentDistrict, "int"));
$currentDistrict = mysql_query($query_currentDistrict, $SLC) or die(mysql_error());
$row_currentDistrict = mysql_fetch_assoc($currentDistrict);
$totalRows_currentDistrict = mysql_num_rows($currentDistrict);

$colname_parentDistrict=$row_currentDistrict['parent'];
mysql_select_db($database_SLC, $SLC);
$query_parentDistrict = sprintf("SELECT * FROM districts WHERE id = %s", GetSQLValueString($colname_parentDistrict, "int"));
$parentDistrict = mysql_query($query_parentDistrict, $SLC) or die(mysql_error());
$row_parentDistrict = mysql_fetch_assoc($parentDistrict);
$totalRows_parentDistrict = mysql_num_rows($parentDistrict);


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title><?php echo $row_settings['name']; ?> | <?php if ($row_parentDistrict['id'] != "1") {echo $row_parentDistrict['name']." > ";};  echo $row_currentDistrict['name']." > ".$row_currentStreet['name']; ?></title>
	
    <script type="application/javascript">
	var MapsGoogle = function () {
    var mapGeolocation = function () {

        var map = new GMaps({
            div: '#gmap_geo',
            lat: <?php if ($row_currentDistrict['maplat'] != "") echo $row_currentDistrict['maplat']; else echo "30.108417"; ?>,
            lng: <?php if ($row_currentDistrict['maplong'] != "") echo $row_currentDistrict['maplong']; else echo "31.376971"; ?>
        });

		<!-- Marker looper -->


        map.addMarker({
           lat: 30.108287,
            lng: 31.374589,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });
        map.addMarker({
           lat: 30.110106,
            lng: 31.376821,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });
        map.addMarker({
           lat: 30.109086,
            lng: 31.377272,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<span style="color:#000">Unit information here</span>'
            }
        });

    }


    return {
        //main function to initiate map samples
        init: function () {
 
            mapGeolocation();

  
        }

    };

}();
</script>

 
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
                                        <?php if ($row_currentDistrict['parent'] !=1) echo "<li><a href=\"district.php?id=".$row_currentDistrict['parent']."\">".$row_parentDistrict['name']."</a></li>";?>
                                        <li><a href="district.php?id=<?php echo $row_currentDistrict['id']; ?>"><?php echo $row_currentDistrict['name']; ?></a></li>
                                        <li><?php echo $row_currentStreet['name']; ?></li>
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left"><?php echo $row_currentStreet['name']; ?></h3>
									</div>
									
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
					  <div class="row">
						<div class="col-md-12">
                        <!-- CHANGETHIS : Change map type to static and datasource should be on page -->
								<div class="box border primary">
									<div class="box-title">
										<h4><i class="fa fa-globe"></i><?php echo $row_currentDistrict['name']; ?> Map</h4>
										<div class="tools">
									
											
											<a href="javascript:;" class="collapse">
												<i class="fa fa-chevron-up"></i>
											</a>
									
										</div>
									</div>
									<div class="box-body">
										<div id="gmap_geo" class="gmaps"></div>
									</div>
								</div>
    					<!-- End CHANGE : Map -->
    
    
    
								
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

mysql_free_result($currentDistrict);
?>
