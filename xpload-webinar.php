<?php
/**
* @package Xpload Webinar
*/
/*
Plugin Name: Xpload Webinar
Plugin URI: https://xprowebinar.com/
Description: This is a plugin that will allow connection from xpload server and show the current video live stream
Version: 1.0.0
Author: Xpload
Author URI : https://xpload.com/
License: GPLv2 or later
Text Domain: xpload-webinar
*/
/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the license, or (at your option) an later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY of FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public Icense for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
Copyright 2005-2015 Automatic, Inc.
*/
require plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
defined( 'ABSPATH' ) or die( 'Hey, what are you doing here?' );

class XploadWebinar{
	function __construct(){
			// Enqueue Stylesheet and Scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyleScripts') );
			// Add Load Div After Body Tag
			add_action('after_body_open_tag', 'loaderDiv');
			/** Paid Live Webinar Shortcode **/
			add_shortcode( 'xpload_webinar', array( $this , 'xploadWebinarHTML' ) );
			/** Free Live Webinar Shortcode **/
			add_shortcode( 'xpload_webinar_free', array( $this , 'xploadWebinarHTMLPublic' ) );

	}

	function enqueueStyleScripts(){

			if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
				// ENQUEUE CSS
					/** CHAT **/
						// JQUERY
						wp_enqueue_style( 'xpload-jquery-ui-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', __FILE__ );
						// Bootstrap
						wp_enqueue_style( 'xpload-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', __FILE__ );
						// Font-Awesome
						wp_enqueue_style( 'xpload-fontawesome', 'https://pro.fontawesome.com/releases/v5.10.0/css/all.css', __FILE__ );
						// emoji
						wp_enqueue_style( 'xpload-xpload-emoji', plugin_dir_url( __FILE__ ). 'assets/css/emojionearea.css', __FILE__, '1.3.39' );
						// Text color change
						wp_enqueue_style( 'xpload-xpectrum', plugin_dir_url( __FILE__ ). 'assets/css/spectrum.css', __FILE__ );
					/** VIDEO **/
						wp_enqueue_style( 'xpload-video-media', plugin_dir_url( __FILE__ ). 'lib/red5pro/red5pro-media.css', __FILE__ );
					/** NOTIFICATION **/
						wp_enqueue_style( 'xpload-toastr', plugin_dir_url( __FILE__ ). 'assets/css/toastr.min.css', __FILE__ );
					/** CONFIRM **/
						wp_enqueue_style( 'xpload-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css', __FILE__ );
					/** PLUGIN STYLESHEET **/
						wp_enqueue_style( 'xpload-webinar-styles', plugin_dir_url( __FILE__ ). 'assets/css/style.css', __FILE__, '1.3.39' );
						wp_enqueue_style( 'xpload-webinar-default', plugin_dir_url( __FILE__ ). 'assets/css/default.css', __FILE__, '1.3.39' );
				
				/** ENQUEUE JS  **/
					// JQUERY
						wp_deregister_script( 'jquery-ui-core' );
					    wp_register_script('jquery-ui-2', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js',  array( 'jquery' ), '1.12.1', false); 
					    wp_enqueue_script('jquery-ui-2'); 					
					// NOTIFICATION PUSHER
						wp_enqueue_script( 'xpload-pusher', 'https://js.pusher.com/7.0/pusher.min.js', array( 'jquery' ), null, true );
					// TOASTR
						wp_enqueue_script( 'xpload-toastr', plugin_dir_url( __FILE__ ) .'assets/js/toastr.min.js', array( 'jquery' ), null, false );
					// CONFIRM
						wp_enqueue_script( 'xpload-confirm', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', array( 'jquery' ), null, false );
					// EMOJI
						wp_enqueue_script( 'xpload-emoji', plugin_dir_url( __FILE__ ) . 'assets/js/emojionearea.min.js', array( 'jquery' ), null, false );
					// SPECTRUM
						wp_enqueue_script( 'xpload-spectrum', plugin_dir_url( __FILE__ ) . 'assets/js/spectrum.js', array( 'jquery' ), null, false );
					// BOOSTRAP
						wp_enqueue_script( 'xpload-bootstrap-popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js', array( 'jquery' ), null, false );
						wp_enqueue_script( 'xpload-bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array( 'jquery' ), null, false ); 
					// FONT AWESOME
						wp_enqueue_script( 'xpload-fontawesome', 'https://kit.fontawesome.com/a076d05399.js', __FILE__ );

					// TOUCH
						wp_enqueue_script( 'xpload-touch-punch', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js', __FILE__ );
					// VIDEO				
						wp_enqueue_script( 'xpload-adapt', 'https://webrtchacks.github.io/adapter/adapter-latest.js', __FILE__ );
						wp_enqueue_script( 'xpload-red5pro-sdk', plugin_dir_url( __FILE__ ) . 'lib/red5pro/red5pro-sdk.min.js', __FILE__, null, true );
						wp_enqueue_script( 'xpload-screenfull', plugin_dir_url( __FILE__ ) . 'lib/screenfull/screenfull.min.js', __FILE__ );
						

						if ( current_user_can('administrator') && is_user_logged_in() ) :

							wp_enqueue_script( 'xpload-publishers', plugin_dir_url( __FILE__ ) . 'assets/js/publishers.js', __FILE__, '1.3.39', true );
							wp_enqueue_script( 'xpload-publisher', plugin_dir_url( __FILE__ ) . 'script/publisher.js', __FILE__, '1.3.39', true );
							wp_localize_script('xpload-publisher', 'pluginsURL', array(
							    'pluginsURL' => plugins_url(),
							));

						else:
							
							wp_enqueue_style( 'xpload-webinar-user', plugin_dir_url( __FILE__ ). 'assets/css/user.css', __FILE__, '1.3.39' );
							wp_enqueue_script( 'xpload-subscribers', plugin_dir_url( __FILE__ ) . 'assets/js/subscribers.js', __FILE__, '1.3.39', true );
							wp_enqueue_script( 'xpload-subscriber', plugin_dir_url( __FILE__ ) . 'script/subscriber.js', __FILE__, '1.3.39', true );

							if ( !is_user_logged_in() ) :
								wp_enqueue_style( 'xpload-webinar-user', plugin_dir_url( __FILE__ ). 'assets/css/user.css', __FILE__, '1.3.39' );
								wp_enqueue_script( 'xpload-subscribers', plugin_dir_url( __FILE__ ) . 'assets/js/subscribers.js', __FILE__, '1.3.39', true );
								wp_enqueue_script( 'xpload-subscriber', plugin_dir_url( __FILE__ ) . 'script/subscriber.js', __FILE__, '1.3.39', true );
								wp_enqueue_script( 'xpload-free-subscriber', plugin_dir_url( __FILE__ ) . 'assets/js/freesubscriber.js', __FILE__, '1.3.39', true );
							endif;

						endif;
						
						// SITE
						wp_enqueue_script( 'xpload-webinar-script', plugin_dir_url( __FILE__ ) . 'assets/js/script.js', array( 'jquery' ), '1.3.39', true );
						wp_localize_script('xpload-webinar-script', 'pluginsURL', array(
						    'pluginsURL' => plugins_url(),
						));
			}
			
	}

	function xploadWebinarHTML(){
		if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
			$host = 'livetraders-webinar-db.chptae1kylfw.us-east-2.rds.amazonaws.com';
			$user = 'admin';
			$pass = 'tBGaPAcdnPivUYQp2RVL';
			$db_name = 'livetraders_webinar';
			$conn = new mysqli($host, $user, $pass, $db_name);
			if($conn->connect_error){
				die('Connection Error : ' . $conn->connect_error);
			}
			$table_name = 'elementPosition';
	?>	
		<div class="loader"></div>
		<div class="navbar navbar-inverse navbar-fixed-left">
		  <a class="navbar-brand" href="https://livetraders.com/wp-admin/">
		  	<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/logo.png'; ?>" alt="Live Traders">
		  </a>
		  <ul class="nav navbar-nav">
		  	<?php if (current_user_can('administrator')) : ?>
		      <li>
		      	<a href="#" class="startVideoCapture" id="xploadVideoCapture" title="Start Webinar">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/start-stream.png'; ?>" alt="Start Webinar">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id='xploadVideoCapturePause' class='pauseVideoCapture' title="Pause Webinar">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/pause-stream.png'; ?>" alt="Pause Webinar">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id='xploadVideoCaptureStop' class='stopVideoCapture' title="Stop Webinar">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/end-stream.png'; ?>" alt="Stop Webinar">
		      	</a>
		      </li>
		    <?php endif; ?>
		      <li>
		      	<a href="#" id="ChatToggle" title="Chat">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/chat.png'; ?>" alt="Chat Room">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="tradeAnnouncementToggle" title="Trade Announcements">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/trade-announcement.png'; ?>" alt="Trade Announcements">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle1" title="Open House Special">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/special-offer.png'; ?>" alt="Open House Special">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle3" title="PTS">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/PTS.png'; ?>" alt="PTS">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle2" title="Admin Camera">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/presenter-video.png'; ?>" alt="Admin Camera">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="screenShareToggle" title="Share Screen">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/main-screen.png'; ?>" alt="Share Screen">
		      	</a>
		      </li>
		      <!-- <li>
		      	<a href="#" id="chartToggle" title="Statistics">
		      		<img src="<?php //echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/chart.png'; ?>" alt="Statistics">
		      	</a>
		      </li> -->
		      <!-- <li>
		      	<a href="#" id="statisticsToggle" title="Statistics">
		      		<img src="<?php //echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/chart.png'; ?>" alt="Statistics">
		      	</a>
		      </li> -->
		      <li>
		      	<a href="#" id="resetView" title="Reset Layout">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/reset.png'; ?>" alt="Reset">
		      	</a>
		      </li>
		  </ul>
		</div>

		<div class="sound_font_select" id="sound_font_select">
			<div class="soundfontContainer">
				<div class="left">
					<?php if (current_user_can('administrator')) : ?>
		        	<select name="notificationSound" id="notificationSound">
						<option value="" disabled selected>Select Sound</option>
					    <optgroup label="Bell Notification Sound">
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 1.mp3'; ?>">Notification 1</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 2.mp3'; ?>">Notification 2</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 3.mp3'; ?>">Notification 3</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 4.mp3'; ?>">Notification 4</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 5.mp3'; ?>">Notification 5</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 6.mp3'; ?>">Notification 6</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 7.mp3'; ?>">Notification 7</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 8.mp3'; ?>">Notification 8</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>">Notification 9</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 10.mp3'; ?>">Notification 10</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 11.mp3'; ?>">Notification 11</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 12.mp3'; ?>">Notification 12</option>
					    </optgroup>

					    <optgroup label="Cash Register Sound">
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 1.mp3'; ?>">Cash Register 1</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 2.mp3'; ?>">Cash Register 2</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 3.mp3'; ?>">Cash Register 3</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 4.mp3'; ?>">Cash Register 4</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 5.mp3'; ?>">Cash Register 5</option>
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 6.mp3'; ?>">Cash Register 6</option>
					    </optgroup>

					    <optgroup label="Stock Market Sound">
					      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Stock Market/Stock Market.mp3'; ?>">Stock Market</option>
					    </optgroup>
					</select>
					<?php endif; ?>
				</div>
				<div class="right">
					<select name="fontSizeSelect" id="fontSizeSelect">
		        		<option value="16" disabled selected>Font Size</option>
		        		<option value="8">8</option>
		        		<option value="10">10</option>
		        		<option value="12">12</option>
		        		<option value="14">14</option>
		        		<option value="16">16</option>
		        		<option value="18">18</option>
		        		<option value="20">20</option>
		        		<option value="22">22</option>
		        		<option value="24">24</option>
		        		<option value="26">26</option>
		        		<option value="28">28</option>
		        		<option value="30">30</option>
		        		<option value="32">32</option>
		        		<option value="34">34</option>
		        		<option value="36">36</option>
		        		<option value="38">38</option>
		        		<option value="40">40</option>
		        	</select>
				</div>
				<!-- <div>
					<select name="presets" id="presets">
		        		<option value="1" disabled selected>Presets</option>
		        		<option value="1">1</option>
		        		<option value="2">2</option>
		        		<option value="3">3</option>
		        	</select>
				</div> -->
			</div>
		</div>
		<div class="xploadwebinar-container container-fluid" id="xploadwebinar-container">
	<?php
			if (is_user_logged_in()) :
				global $current_user;
				wp_get_current_user();
				$subscriberID = $current_user->ID;
				$subscriberName = $current_user->user_login;
				$userFullName = $current_user->user_firstname . " " . $current_user->user_lastname;
				
				$user_info = get_userdata($subscriberID);
				echo "<input type='hidden'id='thisadmin' value='".implode(', ', $user_info->roles)."'>";
			?>
			<div class="webinar-container row nomargin">
		    	<div class="xploadvideo-container col-12 col-sm-12 col-md-12 col-lg-7 paddingfive" id="xploadvideo-container">

		    		<div class="row" id="videocontainerrow">
		    			<div class="col-sm-12 col-md-12 col-lg-12 nopadding videoContainer2" id="videoContainer2">
				    		<?php 
								$videoContainersql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='videoContainer'";
								$videoContainerItem = $conn->query($videoContainersql);
								$videoContainernum = $videoContainerItem->num_rows;
								if($videoContainernum > 0){
									while($row = mysqli_fetch_assoc($videoContainerItem)) {
								    	$videoContainerstyle = $row['element_style'];
									}
								}else{
									$videoContainerstyle = "";						
								}
							 ?>

					        <?php if (current_user_can('administrator')) : ?>
					        	<input type="hidden" id="adminId" value="<?php echo $subscriberName; ?>">
					        	<input type="hidden" id="currentAdminId" value="<?php echo $subscriberName; ?>">
					        	<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">									
									<div id="header-image" class="nopadding">
										<h2>LIVE TRADERS</h2>
									</div>
									<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay muted></video>
					        	</div>
								
							<?php else : ?>
								
								<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">
									<div id="header-image" class="nopadding">
										<h2>LIVE TRADERS</h2>
									</div>
									<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay></video>
					        	</div>

							<?php endif; ?>
						</div>
					</div>

					<div class="row" id="avatarcontainerow">
						<div class="col-sm-12 col-md-12 col-lg-12 nopadding">
							<div class="avatar-container" id="avatarcontainer">
								<?php 
									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar1style = $row['element_style'];
										}
									}else{
										$avatar1style = "";						
									}

									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar_1style = $row['element_style'];
										}
									}else{
										$avatar_1style = "";						
									}
								 ?>
								<div class="avatar" id="avatar_1" style="<?php echo $avatar_1style ?>">
									<div id="avatar1" style="<?php echo $avatar1style ?>">
										<div class="avatart_text">
											<h3>Open House Special</h3>
										</div>
										
										
										<a href="https://livetraders.com/product/open-house-special/" target="_blank">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/Traders-pack.png'; ?>" alt="Ultimate Stock Trading Starter Pack">
										</a>
									</div>
								</div>

								<?php 
									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar2style = $row['element_style'];
										}
									}else{
										$avatar2style = "";					
									}

									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar_2style = $row['element_style'];
										}
									}else{
										$avatar_2style = "";					
									}
								 ?>
								<div class="avatar" id="avatar_2" style="<?php echo $avatar_2style; ?>">
									<div id="avatar2" style="<?php echo $avatar2style; ?>">
										<div id="presenter" class="avatart_text">
											<h3>Presenter</h3>
										</div>
										
										<?php if (current_user_can('administrator')) : ?>
											<div class="selectvidimage" style="display: flex;">
												<div id="videoShow" style="margin-right: 5%;">
													<input type="radio" id="video" name="showContentVidPic" value="video" checked>
				  									<label for="video">Video</label>
												</div>
												<div id="imageshow">
													<input type="radio" id="image" name="showContentVidPic" value="image">
				  									<label for="image">Image</label>
												</div>
											</div>
											<video id="xprowebinarPublisherCamera" class="red5pro-media red5pro-media-background" autoplay controls muted></video>
											<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" style="display:none;" autoplay controls muted></video>

											<?php $current_user_id = get_current_user_id(); ?>						
											<img src="<?php print get_avatar_url($current_user_id, ['size' => '150']); ?>" id="xprowebinarPublisherImage" style="display:none;"/>
															
										<?php else : ?>
											<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" autoplay controls muted style="width:100% !important;"></video>
											<img src="" id="xprowebinarSubscriberImage"/>
										<?php endif; ?>
									</div>
								</div>
								<?php 
									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar3style = $row['element_style'];
										}
									}else{
										$avatar3style = "";						
									}

									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar_3style = $row['element_style'];
										}
									}else{
										$avatar_3style = "";						
									}
								 ?>
								 <div class="avatar" id="avatar_3" style="<?php echo $avatar_3; ?>">
									<div id="avatar3" style="<?php echo $avatar3style; ?>">							
										<div class="avatart_text">
											<h3>Professional Trading Strategies</h3>
										</div>
										<a href="https://livetraders.com/product/professional-trading-strategies/" target="_blank">
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/pts.png'; ?>" alt="Professional Trading Strategies">
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php 
					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='notification-container'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$notificationstyle = $row['element_style'];
						}
					}else{
						$notificationstyle = "";						
					}

					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='message-notif'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$messagenotifstyle = $row['element_style'];
						}
					}else{
						$messagenotifstyle = "";						
					}
				 ?>

			    <div class="xploadchatnotif-container col-12 col-12 col-sm-12 col-md-12 col-lg-5 paddingfive" id="xploadchatnotif-container">
			    	<div class="messaging-container" id="message-notif" style="<?php echo $messagenotifstyle ?>">
						<div class="notification-container" id="notification-container" style="<?php echo $notificationstyle ?>">
							<div class="notification__title">
						        <h5>Trade Announcements</h5>
						        <div class="volume-control-notif" style="display: flex;justify-content: end;">	
									<i class="fas fa-search notifSound" id="notif-search"></i>
									<i class='fas fa-volume-up notifSound' id="notif-volume-up" ></i>
									<i class='fas fa-volume-mute notifSound xploadhide' id="notif-volume-mute"></i>								
								</div>
						    </div>
							<?php if (current_user_can('administrator')) : ?>
								<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">
									
								</ul>
								<input type="hidden" id="userID" value="<?php echo $subscriberID; ?>">
								<input type="hidden" id="userName" value="<?php echo $userFullName; ?>">
								<div class="notificationInput-container">
									<textarea class="form-control xprowebinarNotification xploadscrollbar" id="notifMsgBox" placeholder="Type your notification here..."></textarea>
									<input type="text" id="notificationColorPicker">
								</div>
							<?php else : ?>
								<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">								
								</ul>
							<?php endif; ?>						
						</div>
					</div>
					<?php 
						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='chat-container'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$chatstyle = $row['element_style'];
							}
						}else{
							$chatstyle = "";						
						}

						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='message-chat'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$messagechatstyle = $row['element_style'];
							}
						}else{
							$messagechatstyle = "";						
						}
					 ?>
					<div class="messaging-container" id="message-chat" style="<?php echo $messagechatstyle; ?>">
						<div class="chat-container" id="chat-container" style="<?php echo $chatstyle; ?>">							    	
					    	<div class="chatbox__title">
						        <h5>
						        	<a href="" id="chatbuttonsubscribe">Chat</a> 
						        	<?php if (current_user_can('administrator')) : ?>
							        	<span class="horizontalBar">|</span>
							        	<a href="" id="show_subscribers">Attendees</a>
						        	<?php endif; ?>
						        </h5>
						        <div class="fontSizeContainer" style="display: flex;">
						        	<div class="volume-control-chat" style="display: flex;">
						        		<!-- Button trigger modal -->
										<i class="fas fa-search chatSound" id="chat-search"></i>	
										<i class='fas fa-volume-up chatSound' id="chat-volume-up"></i>
										<i class='fas fa-volume-mute chatSound xploadhide' id="chat-volume-mute"></i>
									</div>
						        </div>
						    </div>
					    	<ul class="chatbox xploadscrollbar" id="chatbox">
					    		<!-- Display all the chat -->
					    	</ul>
						    <div id="name-group" class="chatboxsubscriber">
						    	<input type="hidden" id="chatUserID" value="<?php echo $subscriberID; ?>">
								<input type="hidden" id="chatUserName" value="<?php echo $userFullName; ?>">
						        <textarea class="form-control msg_box xploadscrollbar" id="chatMsgBox" placeholder="Type your message here..."></textarea>
						    </div>

						    
							<div id='member-list'>
								<div class="user-info-in xploadscrollbar">
									<div class="cc"><i class="fa fa-window-close" aria-hidden="true"></i></div>
									<div class="list">									
									</div>						
								</div>							
							</div>
					    </div>
				    </div>
				</div>
			</div>
			<!--
			<div class="row">
				<div class="col-12 chartcontainer nopadding" id="chartcontainer">
					<div class="col-6 nopadding" style="padding-right:.1%;padding-left: .5%;" id="chart1">
						<div class="tradingview-widget-container">
						  <div id="tradingview_907a0"></div>
						  <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
						  <script type="text/javascript">
						  new TradingView.widget(
						  {
						  "width": "auto",
						  "height": "auto",
						  "symbol": "NASDAQ:AAPL",
						  "interval": "D",
						  "timezone": "Etc/UTC",
						  "theme": "dark",
						  "style": "1",
						  "locale": "en",
						  "toolbar_bg": "#f1f3f6",
						  "enable_publishing": false,
						  "withdateranges": true,
						  "hide_side_toolbar": false,
						  "allow_symbol_change": true,
						  "watchlist": [
						    "NASDAQ:AAPL"
						  ],
						  "container_id": "tradingview_907a0"
							}
						  );
						  </script>
						</div>
					</div>
					<div class="col-6" style="padding-left:.1%;padding-right: 1.3%;" id="chart2">
						<div class="tradingview-widget-container">
						  <div class="tradingview-widget-container__widget"></div>
						  <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-screener.js" async>
						  {
						  "width": "auto",
						  "height": "auto",
						  "defaultColumn": "overview",
						  "defaultScreen": "volume_leaders",
						  "market": "america",
						  "showToolbar": true,
						  "colorTheme": "dark",
						  "locale": "en",
						  "largeChartUrl": "https://livetraders.com/?page_id=152707"
							}
						  </script>
						</div>
					</div>
					
				</div>
			</div>
			-->

			<div class="row">
				<audio autostart="false" width="0" height="0" id="PlayChatSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/chat.mp3'; ?>" type="audio/mpeg">
				</audio>

				<audio autostart="" width="0" height="0" id="PlayNotificationSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>" type="audio/mp3" id="PlayNotificationSoundFile">
				</audio>
				<?php
					else :
						echo "<h1 style='text-align:center;'>Sorry!</br>Your are not allowed to access this page.</br>Please subscribe to one of our products.</br>Thank You!</h1>";
					endif;
				?>
			</div>
		</div>

		<!-- Modal Notification-->
		<div class="modal fade" id="notifSearch" tabindex="-1" role="dialog" aria-labelledby="notifSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Notification</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchNotifItem" placeholder="Enter Trade Announcement..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchNotifList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->

		<!-- Modal Chat-->
		<div class="modal fade" id="chatSearch" tabindex="-1" role="dialog" aria-labelledby="chatSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Chat Messages</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchChatItem" placeholder="Enter Chat Message..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchChatList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->
		<?php
		}
	}

	function xploadWebinarHTMLPublic(){
		if(is_page( 'live-webinar' ) || is_page( 'free-live-trading-stream' ) || is_page( 'free-live-stream' )){
			$host = 'livetraders-webinar-db.chptae1kylfw.us-east-2.rds.amazonaws.com';
			$user = 'admin';
			$pass = 'tBGaPAcdnPivUYQp2RVL';
			$db_name = 'livetraders_webinar';
			$conn = new mysqli($host, $user, $pass, $db_name);
			if($conn->connect_error){
				die('Connection Error : ' . $conn->connect_error);
			}
			$table_name = 'elementPosition';
	?>	
		<div class="loader"></div>
		<div class="navbar navbar-inverse navbar-fixed-left">
		  <a class="navbar-brand" href="https://livetraders.com/wp-admin/">
		  	<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/logo.png'; ?>" alt="Live Traders">
		  </a>
		  <ul class="nav navbar-nav">
		      <li>
		      	<a href="#" id="ChatToggle" title="Chat">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/chat.png'; ?>" alt="Chat Room">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="tradeAnnouncementToggle" title="Trade Announcements">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/trade-announcement.png'; ?>" alt="Trade Announcements">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle1" title="Open House Special">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/special-offer.png'; ?>" alt="Open House Special">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle3" title="PTS">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/PTS.png'; ?>" alt="PTS">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="avatarToggle2" title="Admin Camera">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/presenter-video.png'; ?>" alt="Admin Camera">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="screenShareToggle" title="Share Screen">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/main-screen.png'; ?>" alt="Share Screen">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="statisticsToggle" title="Statistics">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/chart.png'; ?>" alt="Statistics">
		      	</a>
		      </li>
		      <li>
		      	<a href="#" id="resetView" title="Reset Layout">
		      		<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/icons/reset.png'; ?>" alt="Reset">
		      	</a>
		      </li>
		  </ul>
		</div>
		<div class="xploadwebinar-container container-fluid" id="xploadwebinar-container">
			<?php			
				global $current_user;
				wp_get_current_user();
				$subscriberID = $current_user->ID;
				$subscriberName = $current_user->user_login;
				$userFullName = $current_user->user_firstname . " " . $current_user->user_lastname;
				
				$user_info = get_userdata($subscriberID);
				echo "<input type='hidden'id='thisadmin' value='".implode(', ', $user_info->roles)."'>";


				if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
					$ip = $_SERVER['HTTP_CLIENT_IP'];
				} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
					$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
				} else {
					$ip = $_SERVER['REMOTE_ADDR'];
				}
				$subscriberID = $ip;
			?>
			<div class="webinar-container row nomargin">
		    	<div class="xploadvideo-container col-12 col-sm-12 col-md-12 col-lg-7 paddingfive" id="xploadvideo-container">

		    		<div class="row" id="videocontainerrow">
		    			<div class="col-sm-12 col-md-12 col-lg-12 nopadding videoContainer2" id="videoContainer2">
				    		<?php 
								$videoContainersql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='videoContainer'";
								$videoContainerItem = $conn->query($videoContainersql);
								$videoContainernum = $videoContainerItem->num_rows;
								if($videoContainernum > 0){
									while($row = mysqli_fetch_assoc($videoContainerItem)) {
								    	$videoContainerstyle = $row['element_style'];
									}
								}else{
									$videoContainerstyle = "";						
								}
							?>
								
							<div id="videoContainer" style="<?php echo $videoContainerstyle; ?>">
								<div id="header-image" class="nopadding">
									<h2>LIVE TRADERS</h2>
								</div>
								<video id="xprowebinar-subscriber" class="red5pro-media red5pro-media-background" controls autoplay></video>
				        	</div>
						</div>
					</div>

					<div class="row" id="avatarcontainerow">
						<div class="col-sm-12 col-md-12 col-lg-12 nopadding">
							<div class="avatar-container" id="avatarcontainer">
								<?php 
									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar1style = $row['element_style'];
										}
									}else{
										$avatar1style = "";						
									}

									$avatar1sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_1'";
									$avatar1Item = $conn->query($avatar1sql);
									$avatar1num = $avatar1Item->num_rows;
									if($avatar1num > 0){
										while($row = mysqli_fetch_assoc($avatar1Item)) {
									    	$avatar_1style = $row['element_style'];
										}
									}else{
										$avatar_1style = "";						
									}
								 ?>
								<div class="avatar" id="avatar_1" style="<?php echo $avatar_1style ?>">
									<div id="avatar1" style="<?php echo $avatar1style ?>">
										<div style="display: flex;justify-content: space-between;background-color: rgba(0,0,0,0.7);">
											<h3>Open House Special</h3>
										</div>
										<a href="https://livetraders.com/product/open-house-special/" target="_blank">
											<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/Traders-pack.png'; ?>" alt="Ultimate Stock Trading Starter Pack">
										</a>
									</div>
								</div>

								<?php 
									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar2style = $row['element_style'];
										}
									}else{
										$avatar2style = "";					
									}

									$avatar2sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_2'";
									$avatar2Item = $conn->query($avatar2sql);
									$avatar2num = $avatar2Item->num_rows;
									if($avatar2num > 0){
										while($row = mysqli_fetch_assoc($avatar2Item)) {
									    	$avatar_2style = $row['element_style'];
										}
									}else{
										$avatar_2style = "";					
									}
								 ?>
								<div class="avatar" id="avatar_2" style="<?php echo $avatar_2style; ?>">
									<div id="avatar2" style="<?php echo $avatar2style; ?>">
										<div id="presenter" style="display: flex;justify-content: space-between;background-color: rgba(0,0,0,0.7);">
											<h3>Presenter</h3>
										</div>										
										<video id="xprowebinarSubscriberCamera" class="red5pro-media red5pro-media-background" autoplay controls muted style="width:100% !important;"></video>
										<img src="" id="xprowebinarSubscriberImage"/>
									</div>
								</div>
								<?php 
									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar3style = $row['element_style'];
										}
									}else{
										$avatar3style = "";						
									}

									$avatar3sql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='avatar_3'";
									$avatar3Item = $conn->query($avatar3sql);
									$avatar3num = $avatar3Item->num_rows;
									if($avatar3num > 0){
										while($row = mysqli_fetch_assoc($avatar3Item)) {
									    	$avatar_3style = $row['element_style'];
										}
									}else{
										$avatar_3style = "";						
									}
								 ?>
								 <div class="avatar" id="avatar_3" style="<?php echo $avatar_3; ?>">
									<div id="avatar3" style="<?php echo $avatar3style; ?>">							
										<div style="display: flex;justify-content: space-between;background-color: rgba(0,0,0,0.7);">
											<h3>Professional Trading Strategies</h3>
										</div>
										<a href="https://livetraders.com/product/professional-trading-strategies/" target="_blank">
										<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/images/pts.png'; ?>" alt="Professional Trading Strategies">
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php 
					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='notification-container'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$notificationstyle = $row['element_style'];
						}
					}else{
						$notificationstyle = "";						
					}

					$notificationsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='message-notif'";
					$notificationItem = $conn->query($notificationsql);
					$notificationnum = $notificationItem->num_rows;
					if($notificationnum > 0){
						while($row = mysqli_fetch_assoc($notificationItem)) {
					    	$messagenotifstyle = $row['element_style'];
						}
					}else{
						$messagenotifstyle = "";						
					}
				 ?>

			    <div class="xploadchatnotif-container col-12 col-12 col-sm-12 col-md-12 col-lg-5 paddingfive" id="xploadchatnotif-container">
			    	<div class="messaging-container" id="message-notif" style="<?php echo $messagenotifstyle ?>">
						<div class="notification-container" id="notification-container" style="<?php echo $notificationstyle ?>">
							<div class="notification__title">
						        <h5>Trade Announcements</h5>
						        <div class="volume-control-notif" style="display: flex;justify-content: end;">
						        	<?php if (current_user_can('administrator')) : ?>
							        	<select name="notificationSound" id="notificationSound">
											<option value="" disabled selected>Select Sound</option>
										    <optgroup label="Bell Notification Sound">
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 1.mp3'; ?>">Notification 1</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 2.mp3'; ?>">Notification 2</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 3.mp3'; ?>">Notification 3</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 4.mp3'; ?>">Notification 4</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 5.mp3'; ?>">Notification 5</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 6.mp3'; ?>">Notification 6</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 7.mp3'; ?>">Notification 7</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 8.mp3'; ?>">Notification 8</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>">Notification 9</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 10.mp3'; ?>">Notification 10</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 11.mp3'; ?>">Notification 11</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 12.mp3'; ?>">Notification 12</option>
										    </optgroup>

										    <optgroup label="Cash Register Sound">
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 1.mp3'; ?>">Cash Register 1</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 2.mp3'; ?>">Cash Register 2</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 3.mp3'; ?>">Cash Register 3</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 4.mp3'; ?>">Cash Register 4</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 5.mp3'; ?>">Cash Register 5</option>
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Cash Register/Cash Register 6.mp3'; ?>">Cash Register 6</option>
										    </optgroup>

										    <optgroup label="Stock Market Sound">
										      <option value="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Stock Market/Stock Market.mp3'; ?>">Stock Market</option>
										    </optgroup>
										</select>
									<?php endif; ?>
									<!-- Button trigger modal -->
									<i class="fas fa-search notifSound" id="notif-search"></i>
									<i class='fas fa-volume-up notifSound' id="notif-volume-up" ></i>
									<i class='fas fa-volume-mute notifSound xploadhide' id="notif-volume-mute"></i>								
								</div>
						    </div>
							<ul class="notificationBox xploadscrollbar" id="xprowebinarNotificationMsgBox">								
							</ul>					
						</div>
					</div>
					<?php 
						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='chat-container'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$chatstyle = $row['element_style'];
							}
						}else{
							$chatstyle = "";						
						}

						$chatsql = "SELECT element_style FROM $table_name WHERE user_id='$subscriberID' AND element_name='message-chat'";
						$chatItem = $conn->query($chatsql);
						$chatnum = $chatItem->num_rows;
						if($chatnum > 0){
							while($row = mysqli_fetch_assoc($chatItem)) {
						    	$messagechatstyle = $row['element_style'];
							}
						}else{
							$messagechatstyle = "";						
						}
					 ?>
					<div class="messaging-container" id="message-chat" style="<?php echo $messagechatstyle; ?>">
						<div class="chat-container" id="chat-container" style="<?php echo $chatstyle; ?>">							    	
					    	<div class="chatbox__title">
						        <h5>
						        	<a href="" id="chatbuttonsubscribe">Chat</a> 
						        	<?php if (current_user_can('administrator')) : ?>
							        	<span class="horizontalBar">|</span>
							        	<a href="" id="show_subscribers">Attendees</a>
						        	<?php endif; ?>
						        </h5>
						        <div class="fontSizeContainer" style="display: flex;">
						        	<select name="fontSizeSelect" id="fontSizeSelect">
						        		<option value="16" disabled selected>Font Size</option>
						        		<option value="8">8</option>
						        		<option value="10">10</option>
						        		<option value="12">12</option>
						        		<option value="14">14</option>
						        		<option value="16">16</option>
						        		<option value="18">18</option>
						        		<option value="20">20</option>
						        		<option value="22">22</option>
						        		<option value="24">24</option>
						        		<option value="26">26</option>
						        		<option value="28">28</option>
						        		<option value="30">30</option>
						        		<option value="32">32</option>
						        		<option value="34">34</option>
						        		<option value="36">36</option>
						        		<option value="38">38</option>
						        		<option value="40">40</option>
						        	</select>

						        	<div class="volume-control-chat" style="display: flex;">
						        		<!-- Button trigger modal -->
										<i class="fas fa-search chatSound" id="chat-search"></i>	
										<i class='fas fa-volume-up chatSound' id="chat-volume-up"></i>
										<i class='fas fa-volume-mute chatSound xploadhide' id="chat-volume-mute"></i>
									</div>
						        </div>
						    </div>
					    	<ul class="chatbox xploadscrollbar" id="chatbox">
					    		<!-- Display all the chat -->
					    	</ul>
						    <div id="name-group" class="chatboxsubscriber">
						    	<input type="hidden" id="chatUserID" value="<?php echo $subscriberID; ?>">
								<input type="hidden" id="chatUserName" value="<?php echo $userFullName; ?>">
						        <textarea class="form-control msg_box xploadscrollbar" id="chatMsgBox" placeholder="Type your message here..."></textarea>
						    </div>

						    
							<div id='member-list'>
								<div class="user-info-in xploadscrollbar">
									<div class="cc"><i class="fa fa-window-close" aria-hidden="true"></i></div>
									<div class="list">									
									</div>						
								</div>							
							</div>
					    </div>
				    </div>
				</div>
			</div>

			<div class="row">
				<audio autostart="false" width="0" height="0" id="PlayChatSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/chat.mp3'; ?>" type="audio/mpeg">
				</audio>

				<audio autostart="" width="0" height="0" id="PlayNotificationSound">
				  <source src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/audio/Bell Notification/Notification 9.mp3'; ?>" type="audio/mp3" id="PlayNotificationSoundFile">
				</audio>
			</div>
		</div>

		<!-- Modal Notification-->
		<div class="modal fade" id="notifSearch" tabindex="-1" role="dialog" aria-labelledby="notifSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Notification</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchNotifItem" placeholder="Enter Trade Announcement..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchNotifList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->

		<!-- Modal Chat-->
		<div class="modal fade" id="chatSearch" tabindex="-1" role="dialog" aria-labelledby="chatSearch" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title" id="exampleModalLongTitle" style="font-weight: 900;">Search Chat Messages</h1>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="searchChatItem" placeholder="Enter Chat Message..." style="width: 100%;margin-bottom:10px;">
		    	</br>
		        <ul id="searchChatList" style="width: 100%;height: 10vh;overflow: auto;margin: 0 auto;" >
		        	
		        </ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>		        
		      </div>
		    </div>
		  </div>
		</div>
		<!-- Modal -->
		<!-- Modal Get Username-->
		<div class="modal fade" id="modalUserName" tabindex="-1" role="dialog" aria-labelledby="modalUserName" aria-hidden="true" style="background:#000;">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h2 class="modal-title" id="exampleModalLongTitle" style='font-weight: 600;font-color:#000;'>Please enter your email used for registration</h2>
		      </div>
		      <div class="modal-body">
		        <input type="text" id="modalUserNameInput" placeholder="Enter Email..." style="width: 100%;margin-bottom:10px;">
		      </div>
		      <div class="modal-footer">
		        <button id="saveUserName" class="btn btn-success" type="button">Save</button>	        
		      </div>
		    </div>
		  </div>
		</div>
		<?php
		}
	}

	function xploadWebinarChatMessagesTableDB(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'xploadChatMessages';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (		  
					`xpload_chat_id` int(11) NOT NULL AUTO_INCREMENT,
					`xpload_chat_messages`  text NOT NULL,
					`xpload_chat_uid` varchar(11) NOT NULL,
					`xpload_chat_name` varchar(20) NOT NULL,
					`xpload_chat_datetime` varchar(20) NOT NULL,
					`xpload_chat_date` varchar(20) NOT NULL,
					`xpload_chat_color`  text NOT NULL
					PRIMARY KEY (`xpload_chat_id`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	function xploadWebinarNotificationsTableDB(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'xploadChatNotification';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (		  
					`xpload_notif_id` int(11) NOT NULL AUTO_INCREMENT,
					`xpload_notif_messages`  text NOT NULL,
					`xpload_notif_uid` varchar(11) NOT NULL,
					`xpload_notif_name` text NOT NULL,
					`xpload_notif_datetime` text NOT NULL,
					`xpload_notif_date` text NOT NULL,
					`xpload_notif_audiosrc`  text NOT NULL,
					`xpload_notif_color`  text NOT NULL,
					PRIMARY KEY (`id`)
				) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

}

// if class exist
if( class_exists( 'XploadWebinar' ) ){
	$XploadWebinar = new XploadWebinar();
}

// activate_plugin
register_activation_hook( __FILE__, array( $XploadWebinar, 'activateXploadWebinar') );

// deactivate_plugin
register_activation_hook( __FILE__, array( $XploadWebinar, 'deactivateXploadWebinar') );
