<?php
$lat=$_GET["lat"];
echo "latitude " ." " .$lat ."\n";
$lon=$_GET["lon"];
echo "longitude" ." " .$lon;
$myFile = "currentPosition.txt";
$fh = fopen($myFile, 'a') or die("can't open file");
$stringData = $lat ."," .$lon ."\n";
fwrite($fh, $stringData);
fclose($fh);
?>
