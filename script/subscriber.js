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
		window.focus();
		$.confirm({
		    title: 'Live Traders',
		    content: 'Live Webinar is starting in a few seconds',
		    buttons: {
		        confirm: function () {
		            $('.live-webinar-popup').remove();
	        		$('.live-webinar-popup').css("display", "none")
	        		onloadSubscriber();
		        },
		        cancel: function () {
		            
		        }
		    }
		});

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

	/**
		**
		* Subscriber Subscribing to chat and added
		* Attendees List
		**
	**/
	function onloadSubscriber(){
		var chatUserID = $("#chatUserID").val();
	 	var chatUserName = $("#chatUserName").val();
	 	var chatIPAddress = "";
	 	var url = pluginsURL.pluginsURL + '/xpload-webinar/php/presence_auth.php';

	 	$.getJSON("https://api.ipify.org/?format=json", function(e) {	 		
		    chatIPAddress = e.ip;
		    console.log(chatIPAddress);

		    if(chatIPAddress != ""){
			    jQuery.ajax({            
		            type: "POST",
		            url: url,
		            data: ({ userId: chatUserID, userName: chatUserName, userIPAddress: chatIPAddress}),
		            dataType: 'json',
		    		cache: false,
			        success: function(response) {
			        	var html = '';
		            	var newmessage = 0;
		            	$.alert({
						    title: 'Congratulations!',
						    content: 'You may now start chatting!',
						});
		            	$('#joinchatcontainer').hide();
		            	$(".chatboxsubscriber").css("display", "block");
			        },
			        error: function(response) {
			            console.log(response);
			        }
		        });
	        }
		});
	}
});