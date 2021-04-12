<?php
define( 'SHORTINIT', true );
require_once('connect.php');
$table_name = 'livetraders_xploadChatMessages';

if(isset($_POST['date'])){

	$date = $_POST['date'];
	$sql = "SELECT * FROM " . $table_name . " WHERE xpload_chat_date = '" . $date . "'";
	$result = $conn->query($sql);

	$rows = array();
	while($row = mysqli_fetch_assoc($result)) {
	    $rows[] = $row;
	}

	echo json_encode($rows);
	$conn->close();
}