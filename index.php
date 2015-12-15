<?php

	include 'controller.php';
 

$wc=getfiches(1);
echo"<pre>";
print_r($wc);
echo "</pre>";

$wc=getImages(80);
echo"<pre>";
print_r($wc);
echo "</pre>";


echo"<pre>";
print_r(lambert93ToWgs84('352914.52','6785627.76'));
echo "</pre>";
?>