<?php

	include 'controller.php';
 //	echo "salut";
 $wc=getToilettes(351861.03,6789173.05,10.0,10.0);
 	echo"<pre>";
 	print_r($wc);
 		echo"</pre>";


 		
	 $res=json_encode(wc);
	if(!$res)
		echo "ECHEC";
	
	 print_r($res);
	// echo $res;

?>