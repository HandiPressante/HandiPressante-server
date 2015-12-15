<?php


 require_once '../controller/toilettes.php';


$wc=getfiches(1);
echo"<pre>";
print_r($wc);
echo "</pre>";

$wc=getImages(80);
echo"<pre>";
print_r($wc);
echo "</pre>";

$wc=getToilettesRange(351861.03,6789173.05,1000,1000);
echo"<pre>";
print_r($wc);
echo "</pre>";
?>