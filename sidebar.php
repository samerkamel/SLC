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


?>
               <!-- SIDEBAR.PHP -->
			   <div id="sidebar" class="sidebar">
					<div class="sidebar-menu nav-collapse">
						<div class="divide-20"></div>
                            <ul>
                                <li><a href="index.php"><i class="fa fa-tachometer fa-fw"></i> <span class="menu-text">Dashboard</span></a></li>
                                <li><a href="add.php"><i class="fa fa-plus fa-fw"></i> <span class="menu-text">Add</span></a></li>

                                <li><a href="#"><i class="fa fa-gear fa-fw"></i> <span class="menu-text">Settings</span></a></li>
                                <li><a href="reports.php"><i class="fa fa-clipboard fa-fw"></i> <span class="menu-text">Reports</span></a></li>
                            </ul>
               	 </div>
               	 <div class="box border primary">
							<div class="box-title"><h4><i class="fa fa-sitemap"></i>Unit Selector</h4></div>
							<ul id="navigation"><!-- Start Navigation -->
                        	<?php do { ?>
                       	    <li><a href="district.php?id=<?php echo $row_parentDistricts['id']; ?>"><i class="fa fa-folder"></i> <?php echo $row_parentDistricts['name']; ?></a>
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
                          				<li><a href="district.php?id=<?php echo $row_secondDistricts['id'];?>"><i class="fa fa-folder"></i> <?php echo $row_secondDistricts['name'];?></a>
                                          <?php 
										  	$totalRows_streetDistrict = 0;
											$colname_streetDistrict = $row_secondDistricts['id'];
											mysql_select_db($database_SLC, $SLC);
											$query_streetDistrict = sprintf("SELECT id, name FROM streets WHERE districtid = %s", GetSQLValueString($colname_streetDistrict, "int"));
											$streetDistrict = mysql_query($query_streetDistrict, $SLC) or die(mysql_error());
											$row_streetDistrict = mysql_fetch_assoc($streetDistrict);
											$totalRows_streetDistrict = mysql_num_rows($streetDistrict);
											if ($totalRows_streetDistrict > 0 ){ ?>
                                            <ul>
                                              <?php do { ?>
                                                <li><a href="street.php?id=<?php echo $row_streetDistrict['id']; ?>"><i class="fa fa-road"></i> <?php echo $row_streetDistrict['name'];?></a>
															 <?php	
															$totalRows_unitStreet = 0;
															$colname_unitStreet = $row_streetDistrict['id'];
															mysql_select_db($database_SLC, $SLC);
															$query_unitStreet = sprintf("SELECT id, name FROM units WHERE street = %s", GetSQLValueString($colname_unitStreet, "int"));
															$unitStreet = mysql_query($query_unitStreet, $SLC) or die(mysql_error());
															$row_unitStreet = mysql_fetch_assoc($unitStreet);
															$totalRows_unitStreet = mysql_num_rows($unitStreet);
															if ($totalRows_unitStreet != 0)
																{ ?>
															<ul>
																<?php do { ?>
																<li><a href="unit.php?id=<?php echo $row_unitStreet['id'];?>"><i class="fa fa-bolt"></i> <?php echo $row_unitStreet['name'];?></a></li>
																<?php } while ($row_unitStreet = mysql_fetch_assoc($unitStreet)); ?>
															</ul>
															<?php } ?>
                                                </li>
                                                <?php } while ($row_streetDistrict = mysql_fetch_assoc($streetDistrict)); ?>
                                            </ul>
                                            <?php }; ?>
                                        </li>
                                       <?php } while ($row_secondDistricts = mysql_fetch_assoc($secondDistricts)); }; ?>
                                       <?php 
									   	
										$totalRows_streetDistrict = 0;
										$colname_streetDistrict = $row_parentDistricts['id'];
										mysql_select_db($database_SLC, $SLC);
										$query_streetDistrict = sprintf("SELECT id, name FROM streets WHERE districtid = %s", GetSQLValueString($colname_streetDistrict, "int"));
										$streetDistrict = mysql_query($query_streetDistrict, $SLC) or die(mysql_error());
										$row_streetDistrict = mysql_fetch_assoc($streetDistrict);
										$totalRows_streetDistrict = mysql_num_rows($streetDistrict);
												if ($totalRows_streetDistrict != 0)
													
													{ 
													echo "<ul>";
													do { ?>
												<li><a href="street.php?id=<?php echo $row_streetDistrict['id'];?>"><i class="fa fa-road"></i> <?php echo $row_streetDistrict['name'];?></a>
												<?php	
													$totalRows_unitStreet = 0;
													$colname_unitStreet = $row_streetDistrict['id'];
													mysql_select_db($database_SLC, $SLC);
													$query_unitStreet = sprintf("SELECT id, name FROM units WHERE street = %s", GetSQLValueString($colname_unitStreet, "int"));
													$unitStreet = mysql_query($query_unitStreet, $SLC) or die(mysql_error());
													$row_unitStreet = mysql_fetch_assoc($unitStreet);
													$totalRows_unitStreet = mysql_num_rows($unitStreet);
														if ($totalRows_unitStreet != 0)
															{ ?>
																<ul>
																	<?php do { ?>
																	<li><a href="unit.php?id=<?php echo $row_unitStreet['id'];?>"><i class="fa fa-bolt"></i> <?php echo $row_unitStreet['name'];?></a></li>
																	<?php } while ($row_unitStreet = mysql_fetch_assoc($unitStreet)); ?>
																</ul>
														<?php } ?>
												</li>
												<?php } while ($row_streetDistrict = mysql_fetch_assoc($streetDistrict)); 
												echo "</ul>";	};?>
                                        
                                <?php if ( $totalRows_secondDistricts > 0 ) { ?></ul><?php }; ?>
                                </li>
                          	<?php } while ($row_parentDistricts = mysql_fetch_assoc($parentDistricts));
							
							?>
                               
                               
                                     
                                                  
                            </ul><!-- End Navigation -->
				 </div>
				</div>
				<!-- /SIDEBAR -->
				<?php
mysql_free_result($unitStreet);
?>
               