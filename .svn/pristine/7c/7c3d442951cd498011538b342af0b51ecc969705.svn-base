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
	<title><?php echo $row_settings['name']; ?> | Dashboard</title>
    <script type="application/javascript">
	var MapsGoogle = function () {
    var mapGeolocation = function () {

        var map = new GMaps({
            div: '#gmap_geo',
            lat: 30.108417,
            lng: 31.376971
        });

        GMaps.geolocate({
            success: function (position) {
                map.setCenter(position.coords.latitude, position.coords.longitude);
            },
            error: function (error) {
                alert('Geolocation failed: ' + error.message);
            },
            not_supported: function () {
                alert("Your browser does not support geolocation");
            },
            always: function () {
                //alert("Geolocation Done!");
            }
        });

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
									</ul>
									<!-- /BREADCRUMBS -->
									<div class="clearfix">
										<h3 class="content-title pull-left"><?php echo $row_settings['name']; ?> Dashboard</h3>
									</div>
									
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
	
						<!-- ROW 2 -->
					  <div class="row">
						<div class="col-md-12">
								<!-- BOX -->
								<div class="box border primary">
									<div class="box-title">
										<h4><i class="fa fa-globe"></i>Units Map</h4>
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
								<!-- /BOX -->
					    </div>
					  </div><div class="row">
						<div class="col-md-12">
								<!-- BOX -->
								<div class="box ">
									<div class="box-title">
										<h4><i class="fa fa-globe"></i>System Overview</h4>
										<div class="tools">
											<a href="javascript:;" class="collapse">
												<i class="fa fa-chevron-up"></i>
											</a>
									
										</div>
									</div>
									<div class="box-body">
										<div class="col-md-3">
                                        	<h4>Districts: <?php echo $totalRows_parentDistricts + $totalRows_childDistricts ?></h4>
                                            <ul>
                                            	<li>Parent Districts: <?php echo $totalRows_parentDistricts ?></li>
                                                <li>Child Districts: <?php echo $totalRows_childDistricts ?></li>
                                        </div>
                                        <div class="col-md-3">
                                        	<h4>Streets: <?php echo $totalRows_streets ?></h4>
                                        </div>
                                        <div class="col-md-3">
                                        	<h4>Units: <?php echo $totalRows_allUnits ?></h4>
                                        </div>
                                              <div class="col-md-3">
                                        	<h4>Users:  <?php echo $totalRows_allUsers ?></h4>
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
?>
