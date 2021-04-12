<?php
	$host = 'livetraders-webinar-db.chptae1kylfw.us-east-2.rds.amazonaws.com';
	$user = 'admin';
	$pass = 'tBGaPAcdnPivUYQp2RVL';
	$db_name = 'livetraders_webinar';

	$conn = new mysqli($host, $user, $pass, $db_name);

	if($conn->connect_error){
		die('Connection Error : ' . $conn->connect_error);
	}