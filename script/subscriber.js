jQuery(function($) {

	// Camera Share
	(function (red5prosdk2) {  
		var subscriber = new red5prosdk.RTCSubscriber();
		subscriber.init({
		protocol: 'wss',
		port: 443,
		host: 'xprowebinar.com',
		app: 'live',
		streamName: 'xprowebinarscreenshare',
		rtcConfiguration: {
		  iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
		  iceCandidatePoolSize: 2,
		  bundlePolicy: 'max-bundle'
		},
		mediaElementId: 'xprowebinarSubscriberCamera',
		subscriptionId: 'admincamera' + Math.floor(Math.random() * 0x10000).toString(16),
		videoEncoding: 'NONE',
		audioEncoding: 'NONE',
		autoLayoutOrientation: true
		})
		.then(function(subscriber) {

			return subscriber.subscribe();
		})
		.then(function(subscriber) {
			var q = confirm("Live Webinar already started JOIN NOW!");
		    if (q == true) {
		        $('.live-webinar-popup').remove();
		        $('.live-webinar-popup').css("display", "none");
		        screensharesubscriber();
		    } else {
		        $('.live-webinar-popup').remove();
		        $('.live-webinar-popup').css("display", "none")
		    }
		})
		.catch(function(error) {
			// alert("ERROR");
			// return subscriber.subscribe();
		});

	})(window.red5prosdk);
	

	function screensharesubscriber(){
		(function (red5prosdk) {
		  // Create a new instance of the WebRTC subcriber.
		  var subscriber2 = new red5prosdk.RTCSubscriber();
		  // Initialize
		  subscriber2.init({
		    protocol: 'wss',
		    port: 443,
		    host: 'xprowebinar.com',
		    app: 'live',
		    streamName: 'adminstream',
		    rtcConfiguration: {
		      iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
		      iceCandidatePoolSize: 2,
		      bundlePolicy: 'max-bundle'
		    }, // See https://developer.mozilla.org/en-US/docs/Web/API/RTCPeerConnection/RTCPeerConnection#RTCConfiguration_dictionary
		    mediaElementId: 'xprowebinar-subscriber',
		    subscriptionId: 'adminstream' + Math.floor(Math.random() * 0x10000).toString(16),
		    videoEncoding: 'NONE',
		    audioEncoding: 'NONE',
		    autoLayoutOrientation: true
		  })
		  .then(function(subscriber) {
		    return subscriber2.subscribe();	    
		  })
		  .then(function(subscriber) {
		  })
		  .catch(function(error) {
		  });
		})(window.red5prosdk);
	}


	// Starting Webinar
	var pusherWebinar = new Pusher('a6e881af5162a58d2816', {
	  cluster: 'us2'
	});
	var channelWebinar = pusherWebinar.subscribe('startwebinar');

	channelWebinar.bind('startwebinarevent', function(data) {
		// var currentAdminId = $('#currentAdminId').val();
		// var thisadmin = $('#thisadmin').val();

		// if(data != currentAdminId && thisadmin != "administrator"){
		// 	// remove start webinar button
		// 	var captureButton = $('.startVideoCapture');
		//   	var pauseCaptureButton = $('.pauseVideoCapture');
		//   	var stopCaptureButton = $('.stopVideoCapture');

		// 	captureButton.css('display','initial');
		// 	pauseCaptureButton.css("display", "none");
		// 	stopCaptureButton.css("display", "initial");

		// 	// Remove Publisher Camera on other admins
		// 	$( "#xprowebinarPublisherCamera" ).remove();
		// 	// Append video for other admin to subscribe to webinar
			
		// 	if(!$('#xprowebinarPublisherCamera').length){
		// 		alert("Admin Camera does not exist");
		// 		$( "#xprowebinarSubscriberCamera" ).css('display', 'inherit');
		// 	}
		// }

		window.focus();
		var r = confirm("Live Webinar is starting in a few seconds");		
		document.getElementById("presenter").style.display = "none";
	    if (r == true) {
	        $('.live-webinar-popup').remove();
	        $('.live-webinar-popup').css("display", "none")
	    } else {
	        $('.live-webinar-popup').remove();
	        $('.live-webinar-popup').css("display", "none")
	    }

		// Screen share
		(function (red5prosdk) {
		  // Create a new instance of the WebRTC subcriber.
		  var subscriber = new red5prosdk.RTCSubscriber();
		  // Initialize
		  subscriber.init({
		    protocol: 'wss',
		    port: 443,
		    host: 'xprowebinar.com',
		    app: 'live',
		    streamName: 'adminstream',
		    rtcConfiguration: {
		      iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
		      iceCandidatePoolSize: 2,
		      bundlePolicy: 'max-bundle'
		    }, // See https://developer.mozilla.org/en-US/docs/Web/API/RTCPeerConnection/RTCPeerConnection#RTCConfiguration_dictionary
		    mediaElementId: 'xprowebinar-subscriber',
		    subscriptionId: 'adminstream' + Math.floor(Math.random() * 0x10000).toString(16),
		    videoEncoding: 'NONE',
		    audioEncoding: 'NONE',
		    autoLayoutOrientation: true
		  })
		  .then(function(subscriber) {
		    // `subcriber` is the WebRTC Subscriber instance.
		    $('.xploadchatnotif-container .notification-container').css("height", "53.4vw");
		    $('.xploadchatnotif-container .chat-container').css("height", "49vw");
		    $('.xploadwebinar-container').css("margin-bottom", "12vh");
		    return subscriber.subscribe();
		  })
		  .then(function(subscriber) {
		    // subscription is complete.
		    // playback should begin immediately due to
		    //   declaration of `autoplay` on the `video` element.
		  })
		  .catch(function(error) {
		    // A fault occurred while trying to initialize and playback the stream.
		    $('.xploadchatnotif-container .notification-container').css("height", "53.4vw");
		    $('.xploadchatnotif-container .chat-container').css("height", "49vw");
		    $('.xploadwebinar-container').css("margin-bottom", "12vh");	    
		    return subscriber.subscribe();
		  });
		})(window.red5prosdk);

		// Camera Share
		(function (red5prosdk2) {  
			var subscriber = new red5prosdk.RTCSubscriber();
			subscriber.init({
			protocol: 'wss',
			port: 443,
			host: 'xprowebinar.com',
			app: 'live',
			streamName: 'xprowebinarscreenshare',
			rtcConfiguration: {
			  iceServers: [{urls: 'stun:stun2.l.google.com:19302'}],
			  iceCandidatePoolSize: 2,
			  bundlePolicy: 'max-bundle'
			},
			mediaElementId: 'xprowebinarSubscriberCamera',
			subscriptionId: 'admincamera' + Math.floor(Math.random() * 0x10000).toString(16),
			videoEncoding: 'NONE',
			audioEncoding: 'NONE',
			autoLayoutOrientation: true
			})
			.then(function(subscriber) {
			return subscriber.subscribe();
			})
			.then(function(subscriber) {
			})
			.catch(function(error) {
			return subscriber.subscribe();
			});

		})(window.red5prosdk);

		var vid = document.getElementById("xprowebinar-subscriber");
		if(vid.muted == true){
			vid.muted = false;
		}else{
			vid.muted = false;
		}
	});
});