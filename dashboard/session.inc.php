<?php if(empty($_SESSION["staffs"])){
		echo "<script type=\"text/javascript\">document.location.href='signin.php';</script>";
		exit;
 }