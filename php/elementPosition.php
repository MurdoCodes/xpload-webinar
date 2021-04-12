<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );
require_once('connect.php');
$table_name = 'elementPosition';

if(isset($_POST['userId']) && isset($_POST['userName']) && isset($_POST['elementId']) && isset($_POST['style']) ){

	$user_id = $_POST['userId'];
	$user_name = $_POST['userName'];
	$element_name = $_POST['elementId'];
	$element_style = $_POST['style'];

	$sql = "SELECT * FROM " . $table_name . " WHERE user_id='".$user_id."' AND element_name='".$element_name."'";
	$checkItem = $conn->query($sql);
	$num = $checkItem->num_rows; 
	if($num > 0){		
		$sqlUpdate = "UPDATE $table_name SET element_style='$element_style' WHERE user_id='$user_id' AND element_name='$element_name'";
		if ($conn->query($sqlUpdate) === TRUE) {
		  return true;
		} else {
		  return false;
		}		

	}else{
		$sqlInsert = "INSERT INTO $table_name(elementPos_ID, user_id, user_name, element_name, element_style) VALUES(NULL, '$user_id', '$user_name', '$element_name', '$element_style')";
		$insertPost = $conn->query($sqlInsert);

		if($insertPost){
			return true;
		}else{
			return false;
		}	
		
	}

	$conn->close();
}