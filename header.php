<?php require_once('Connections/SLC.php'); ?>
<?php
// Load the tNG classes
require_once('includes/tng/tNG.inc.php');

// Make unified connection variable
$conn_SLC = new KT_connection($SLC, $database_SLC);

//Start Restrict Access To Page
$restrict = new tNG_RestrictAccess($conn_SLC, "");
//Grand Levels: Any
$restrict->Execute();
//End Restrict Access To Page

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
$query_parentDistricts = "SELECT id, name FROM districts WHERE parent = 1 AND id > 1 ORDER BY name ASC";
$parentDistricts = mysql_query($query_parentDistricts, $SLC) or die(mysql_error());
$row_parentDistricts = mysql_fetch_assoc($parentDistricts);
$totalRows_parentDistricts = mysql_num_rows($parentDistricts);

mysql_select_db($database_SLC, $SLC);
$query_childDistricts = "SELECT * FROM districts WHERE parent <> 1 ORDER BY name ASC";
$childDistricts = mysql_query($query_childDistricts, $SLC) or die(mysql_error());
$row_childDistricts = mysql_fetch_assoc($childDistricts);
$totalRows_childDistricts = mysql_num_rows($childDistricts);

mysql_select_db($database_SLC, $SLC);
$query_streets = "SELECT * FROM streets ORDER BY name ASC";
$streets = mysql_query($query_streets, $SLC) or die(mysql_error());
$row_streets = mysql_fetch_assoc($streets);
$totalRows_streets = mysql_num_rows($streets);

mysql_select_db($database_SLC, $SLC);
$query_allUnits = "SELECT * FROM units ORDER BY uniqueID ASC";
$allUnits = mysql_query($query_allUnits, $SLC) or die(mysql_error());
$row_allUnits = mysql_fetch_assoc($allUnits);
$totalRows_allUnits = mysql_num_rows($allUnits);

mysql_select_db($database_SLC, $SLC);
$query_allUsers = "SELECT * FROM users WHERE accesslevel > '1l' ORDER BY firstname ASC";
$allUsers = mysql_query($query_allUsers, $SLC) or die(mysql_error());
$row_allUsers = mysql_fetch_assoc($allUsers);
$totalRows_allUsers = mysql_num_rows($allUsers);
?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- STYLESHEETS --><!--[if lt IE 9]><script src="js/flot/excanvas.min.js"></script><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script><![endif]-->
	<link rel="stylesheet" type="text/css" href="css/cloud-admin.css" >
	<link rel="stylesheet" type="text/css"  href="css/themes/default.css" id="skin-switcher" >
	<link rel="stylesheet" type="text/css"  href="css/responsive.css" >
	<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
   	<link rel="stylesheet" href="css/jquery.treeview.css" />
	<link rel="stylesheet" type="text/css" href="js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
  
</head>
<body>
                        <!-- TREE DATA SOURCE -->
                        <script type="text/javascript">
                        
                        var DataSourceTree = function(options) {
                            this._data 	= options.data;
                            this._delay = options.delay;
                        }
                        
                        DataSourceTree.prototype.data = function(options, callback) {
                            var self = this;
                            var $data = null;
                        
                            if(!("name" in options) && !("type" in options)){
                                $data = this._data;//the root tree
                                callback({ data: $data });
                                return;
                            }
                            else if("type" in options && options.type == "folder") {
                                if("additionalParameters" in options && "children" in options.additionalParameters)
                                    $data = options.additionalParameters.children;
                                else $data = {}//no data
                            }
                            
                            if($data != null) setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 1) + 200);    };
                        
                        var tree_data = {}
                        var treeDataSource = new DataSourceTree({data: tree_data});
                       	var tree_data_2 = {}
                        var treeDataSource2 = new DataSourceTree({data: tree_data_2});
                        var tree_data_3 = {}
                        var treeDataSource3 = new DataSourceTree({data: tree_data_3});
                        
                        </script>
                        <!-- END TREE DATASOURCE -->
	<!-- HEADER -->
    <header class="navbar clearfix" id="header">
      <div class="container">
        <div class="navbar-brand">
          <!-- COMPANY LOGO -->
          <a href="index.php"> <img src="<?php echo $row_settings['logo']; ?>" alt="<?php echo $row_settings['name']; ?>" class="img-responsive" height="30" width="120"></a>
          <div class="visible-xs"> <a href="#" class="team-status-toggle switcher btn dropdown-toggle"> <i class="fa fa-users"></i></a></div>
          <div id="sidebar-collapse" class="sidebar-collapse btn"> <i class="fa fa-bars"  data-icon1="fa fa-bars"  data-icon2="fa fa-bars" ></i></div>
        </div>
        <ul class="nav navbar-nav pull-left hidden-xs" id="navbar-left">
          <li class="dropdown"> <a href="#" class="team-status-toggle dropdown-toggle tip-bottom" data-toggle="tooltip" title="View System Status"> <i class="fa fa-gears"></i> <span class="name">System Status</span> <i class="fa fa-angle-down"></i></a></li>
        </ul>
        <ul class="nav navbar-nav pull-right">
          <li class="dropdown" id="header-notification"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-bell"></i> <span class="badge">1</span></a>
            <ul class="dropdown-menu notification">
              <li class="dropdown-title"> <span><i class="fa fa-bell"></i> 1 Violation</span></li>
              <li> <a href="#"> <span class="label label-danger"><i class="fa fa-exclamation-triangle"></i></span> <span class="body"> <span class="message">Example violation text. </span> <span class="time"> <i class="fa fa-clock-o"></i> <span>Just now</span></span></span></a></li>
              <li class="footer"> <a href="#">See all violations <i class="fa fa-arrow-circle-right"></i></a></li>
            </ul>
          </li>
          <li class="dropdown user" id="header-user"> <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <img alt="" src="" /> <span class="username">User Profile</span> <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
              <li><a href="#"><i class="fa fa-cog"></i> Account Settings</a></li>
              <li><a href="logout.php"><i class="fa fa-power-off"></i> Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="container team-status" id="team-status">
        <div id="scrollbar">
          <div class="handle"></div>
        </div>
        <div id="teamslider">
          <ul class="team-list">    
          </ul>
        </div>
      </div>
    </header>
    <!--/HEADER -->
    <?php /*?>	<?php
mysql_free_result($parentDistricts);

mysql_free_result($childDistricts);

mysql_free_result($streets);

mysql_free_result($allUnits);

mysql_free_result($allUsers);
?><?php */?>
