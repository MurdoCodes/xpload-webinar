<?php
define( 'SHORTINIT', true );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/xpload-webinar/vendor/autoload.php' );

if($_POST['data']){

	$options = array(
		'cluster' => 'us2',
		'useTLS' => true
	);
	$pusherStartWebinar = new Pusher\Pusher(
		'a6e881af5162a58d2816',
		'894b9afe596bb4a7d517',
		'1134044',
		$options
	);

	$pusherStartWebinar->trigger('startwebinar', 'startwebinarevent', $_POST['data']);
	echo $_POST['data'];	  
}